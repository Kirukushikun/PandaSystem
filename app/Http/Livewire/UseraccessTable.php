<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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
        
        // $response = Http::withHeaders([
        //     'x-api-key' => '123456789bgc'
        // ])
        // ->withOptions([
        //     'verify' => storage_path('cacert.pem'),
        // ])
        // ->post('https://bfcgroup.ph/api/v1/users');

        // if ($response->successful()) {
        //     $json = $response->json();

        //     // Check if data key exists, otherwise store entire response
        //     $this->users = $json['data'] ?? $json;

        //     Log::info('Fetched Users:', $this->users);
        // } else {
        //     $this->users = [];
        //     session()->flash('error', 'Failed to fetch users. Status: ' . $response->status());
        //     Log::info('API Error: ' . $response->status());
        // }

        // Temporary static data from the actual API response
        $this->users = [
            [
                "id" => "2",
                "first_name" => "Michael Adam",
                "last_name" => "Trinidad",
                "middle_name" => null,
                "created_at" => null,
                "updated_at" => "2025-08-27T03:20:14.000000Z"
            ],
            [
                "id" => "3",
                "first_name" => "Ghel",
                "last_name" => "Dantes",
                "middle_name" => null,
                "created_at" => "2022-06-30T16:44:47.000000Z",
                "updated_at" => "2025-07-24T16:32:43.000000Z"
            ],
            // ... include the rest as needed
        ];
    }

    public function manageAccess($userId, $action, $role)
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
            return session()->flash('notif', [
                'type' => 'error',
                'header' => 'Invalid Role',
                'message' => 'The selected role is not recognized.'
            ]);
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
            // Create new user if granting
            if ($action === 'grant') {
                $defaultAccess[$key] = true;
                $user = User::create([
                    'id' => $userId,
                    'access' => $defaultAccess
                ]);

                $this->noreloadNotif("success", "User Created", "New user created with {$role} Module access granted.");
            }
        }

        // Update access for existing user
        $access = $user->access ?? $defaultAccess;
        $access[$key] = $action === 'grant';

        // If no module is true → delete user
        if (!in_array(true, $access, true)) {
            $user->delete();
            
            return session()->flash('notif', [
                'type' => 'success',
                'header' => 'User Removed',
                'message' => "User {$userId} was removed because no active module access remains."
            ]);
        }

        // Otherwise, update access
        $user->access = $access;
        $user->save();

        $this->dispatch('accessUpdated'); // Notify table
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
