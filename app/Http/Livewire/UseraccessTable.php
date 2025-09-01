<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class UseraccessTable extends Component
{   
    public $users = [];
    public $dbUsers = [];

    protected $listeners = ['accessUpdated' => '$refresh'];

    public function mount()
    {
        $this->fetchUsers();

        // Step 2: Fetch local DB users (with access JSON) and map by ID
        $this->dbUsers = User::all()->keyBy('id');
    }

    public function fetchUsers()
    {
        $response = Http::withHeaders([
            'x-api-key' => '123456789bgc'
        ])
        ->withOptions([
            'verify' => storage_path('cacert.pem'),
        ])
        ->post('https://bfcgroup.ph/api/v1/users');

        if ($response->successful()) {
            $json = $response->json();

            // Check if data key exists, otherwise store entire response
            $users = $json['data'] ?? $json;
            
            // Decrypt the user IDs
            $this->users = array_map(function($user) {
                try {
                    $user['id'] = Crypt::decryptString($user['id']);
                } catch (\Exception $e) {
                    Log::error('Failed to decrypt user ID for: ' . $user['first_name'] . ' ' . $user['last_name']);
                }
                return $user;
            }, $users);

            Log::info('Fetched Users:', $this->users);
        } else {
            $this->users = [];
            session()->flash('error', 'Failed to fetch users. Status: ' . $response->status());
            Log::info('API Error: ' . $response->status());
        }
    }

    public function manageAccess($userId, $action, $role, $name = null)
    {   
        // Map the role to the JSON key
        $roleMap = [
            'Requestor'      => 'RQ_Module',
            'Division Head'  => 'DH_Module',
            'HR Preparer'    => 'HRP_Module',
            'HR Approver'    => 'HRA_Module',
            'Final Approver' => 'FA_Module'
        ];

        if (!isset($roleMap[$role])) {
            session()->flash('notif', [
                'type' => 'error',
                'header' => 'Invalid Role',
                'message' => 'The selected role is not recognized.'
            ]);
            return;
        }

        $key = $roleMap[$role];

        // Default access template (all false)
        $defaultAccess = [
            'RQ_Module' => false,
            'DH_Module' => false,
            'HRP_Module' => false,
            'HRA_Module' => false,
            'FA_Module' => false
        ];

        // Find the user by ID
        $user = User::find($userId);

        if (!$user) {
            // Only create new user if granting access
            if ($action === 'grant') {
                $defaultAccess[$key] = true;
                $newUser = User::create([
                    'id' => $userId,
                    'name' => $name,
                    'access' => $defaultAccess
                ]);
                
                // CRUCIAL: Update the dbUsers collection with the new user
                $this->dbUsers->put($userId, $newUser);
                
                $this->noreloadNotif("success", "User Created", "New user created with {$role} Module access granted.");
            } else {
                // Can't revoke from non-existent user
                $this->noreloadNotif("error", "User Not Found", "Cannot revoke access from a user that doesn't exist.");
            }
            return;
        }

        // Update access for existing user
        $access = $user->access ?? $defaultAccess;
        $access[$key] = $action === 'grant';

        // Check if all modules will be false after the update
        if (!in_array(true, $access, true)) {
            // Delete user if no active modules remain
            $user->delete();
            
            // CRUCIAL: Remove from dbUsers collection
            $this->dbUsers->forget($userId);
            
            $this->noreloadNotif('success', 'User Removed', "User {$userId} was removed because no active module access remains.");
            return;
        }

        // Update and save user access
        $user->access = $access;
        $user->save();
        
        // CRUCIAL: Update the dbUsers collection with the updated user
        $this->dbUsers->put($userId, $user);
        
        $this->noreloadNotif('success', ucfirst($action) . ' Successful', "The user's access for {$role} Module has been successfully {$action}ed.");
    }

    public function render()
    {
        return view('livewire.useraccess-table');
    }

    private function noreloadNotif($type, $header, $message)
    {
        $this->dispatch('notif', type: $type, header: $header, message: $message);
    }
}
