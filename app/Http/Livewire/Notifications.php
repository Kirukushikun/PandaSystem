<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Notification;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $perPage = 3; // start with 3

    protected $listeners = [
        'refreshNotifications' => 'loadNotifications',
    ];

    public function mount($type = null)
    {   
        $this->loadNotifications();
    }

    public function loadMore()
    {
        $this->perPage += 3;
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Notification::latest()
            ->take($this->perPage)
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
