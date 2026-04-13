<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CustomTopbar extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Check if the user model has notifications trait
            if (method_exists($user, 'notifications')) {
                $this->notifications = $user->notifications()->latest()->take(5)->get();
                $this->unreadCount = $user->unreadNotifications()->count();
            } else {
                $this->notifications = collect([]);
                $this->unreadCount = 0;
            }
        }
    }

    public function markAsRead($notificationId)
    {
        if (Auth::check() && method_exists(Auth::user(), 'notifications')) {
            $notification = Auth::user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                $this->loadNotifications();
            }
        }
    }

    public function markAllAsRead()
    {
        if (Auth::check() && method_exists(Auth::user(), 'unreadNotifications')) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.custom-topbar');
    }
}
