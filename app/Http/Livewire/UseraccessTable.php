<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UseraccessTable extends Component
{   
    public $users = [];

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
            $this->users = $json['data'] ?? $json;

            Log::info('Fetched Users:', $this->users);
        } else {
            $this->users = [];
            session()->flash('error', 'Failed to fetch users. Status: ' . $response->status());
            Log::info('API Error: ' . $response->status());
        }
    }

    public function mount()
    {
        $this->fetchUsers();
    }

    public function render()
    {
        return view('livewire.useraccess-table');
    }
}
