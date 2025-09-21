<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Notification;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    protected $listeners = [
        'refreshNotifications' => 'loadNotifications',
    ];

    public function mount($type = null)
    {   
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Notification::latest()
            ->take(3)
            ->get();

        $this->unreadCount = Notification::where('is_read', false)->count();
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
