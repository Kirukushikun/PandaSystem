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
            $base_uri = config('services.auth_api.base_uri');
            $api_key = config('services.auth_api.api_key');
            $auth_user_api_key = config('services.auth_api.auth_user_api_key');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $api_key,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($base_uri . '/api/v1/auth/login', [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ]);

            if($response->successful()) {
                $response = $response->json();
                $token = $response['token'] ?? null;
                $token_expires = $response['expires_at'] ?? null;
                $email = $response['email'] ?? null;

                // Store authentication data in session
                session(['auth_token' => $token, 'token_expires' => $token_expires, 'email' => $email]);

                // Get user ID using email
                $query_id = Http::withHeaders([
                    'x-api-key' => $auth_user_api_key,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->get($base_uri . "/api/v1/users/get-user-id?email=" . $email);

                // Validate and login user using Laravel Auth
                try {

                    $user = User::find($query_id['id']); // returns null if not found
                    if ($user) {
                        Auth::loginUsingId($user->id);
                        return view('home');
                    } else {
                        session()->flash('notif', [
                            'type' => 'failed',
                            'header' => 'Unauthorized User',
                            'message' => 'Sorry you are not authorized to access this system'
                        ]);
                        return redirect()->route('login');
                    }

                    // return response()->json([
                    //     'success' => true,
                    //     'message' => 'Authentication successful',
                    // ], 200);
                }
                catch(\Throwable $e) {
                    return abort(500);
                }
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication failed: ' . $response->json()['message'] ?? 'Unknown error',
                ], $response->status());
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout(){
        Auth::logout(); // Logs the user out
        return redirect('/login'); // Redirects the user to the login page
    }
}