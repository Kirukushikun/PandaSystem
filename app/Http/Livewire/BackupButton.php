<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;

class BackupButton extends Component
{   

    public function runArtisanCommand()
    {
        // Run your artisan command
        Artisan::call('backup:run');

        $this->dispatch('notif', type: 'success', header: 'Backup success', message: 'System backup successfully');
    }

    public function render()
    {
        return view('livewire.backup-button');
    }
}
