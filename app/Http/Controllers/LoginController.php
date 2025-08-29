<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function postLogin(Request $request)
    {
        try {
            // 1. Prepare authentication API request
            $base_uri = config('services.auth_api.base_uri');
            $api_key = config('services.auth_api.api_key');
            $auth_user_api_key = config('services.auth_api.auth_user_api_key');

            // 2. Attempt to authenticate via external API
            $authResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $api_key,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($base_uri . '/api/v1/auth/login', [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ]);

            // 3. Check if external API authentication was successful
            if ($authResponse->successful()) {
                $data = $authResponse->json();

                // Extract token and user email from API response
                $token = $data['token'] ?? null;
                $token_expires = $data['expires_at'] ?? null;
                $email = $data['email'] ?? null;

                // Store authentication data in session
                session([
                    'auth_token' => $token,
                    'token_expires' => $token_expires,
                    'email' => $email
                ]);

                // 4. Retrieve user ID from your system using the authenticated email
                $userResponse = Http::withHeaders([
                    'x-api-key' => $auth_user_api_key,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->get($base_uri . "/api/v1/users/get-user-id?email=" . $email);

                // Handle failed user ID retrieval
                if (!$userResponse->successful()) {
                    return back()->withErrors([
                        'login' => 'Failed to retrieve user information from the system.'
                    ])->withInput();
                }

                $userData = $userResponse->json();
                $user = User::find($userData['id'] ?? null); // May return null if not registered

                // 5. Log in the user if found in your system
                if ($user) {
                    Auth::loginUsingId($user->id);
                    return redirect()->route('home'); // Use route instead of direct view
                }

                // 6. If user not found in your database, show "Not authorized"
                return back()->withErrors([
                    'login' => 'You are not authorized to access this system.'
                ])->withInput();
            }

            // 7. Handle external API authentication failure (wrong email/password)
            return back()->withErrors([
                'login' => $authResponse->json()['message'] ?? 'Incorrect username or password.'
            ])->withInput();

        } catch (\Exception $e) {
            // 8. Handle unexpected errors (API down, network issues, etc.)
            return back()->withErrors([
                'login' => 'Authentication failed: ' . $e->getMessage()
            ])->withInput();
        }
    }


    public function logout(){
        Auth::logout(); // Logs the user out
        return redirect('/login'); // Redirects the user to the login page
    }
}