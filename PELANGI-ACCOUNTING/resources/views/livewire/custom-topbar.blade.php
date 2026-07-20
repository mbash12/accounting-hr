<div>
    <style>
        @media (min-width: 640px) {
            .sm\:block {
                display: block !important;
            }
        }
        /* Fix SVG icon colors in dropdowns */
        .fi-topbar:not([x-show]) svg {
            color: inherit !important;
        }
        /* Ensure icons are visible in dropdowns - override the color inheritance */
        div[style*="position: absolute"][x-show] svg {
            color: #374151 !important; /* This affects stroke="currentColor" */
            stroke: #374151 !important; /* Explicitly set stroke for SVG paths */
        }
        /* Specific icon colors for notification mark-as-read */
        div[style*="position: absolute"][x-show] button[wire\:click*="markAsRead"] svg {
            color: #0ea5e9 !important;
            stroke: #0ea5e9 !important;
        }
        /* Specific icon colors for logout button */
        div[style*="position: absolute"][x-show] form button[type="submit"] svg {
            color: #dc2626 !important;
            stroke: #dc2626 !important;
        }
    </style>
    <div style="display: flex; align-items: center; gap: 1rem; flex: 1; justify-content: flex-end; padding: 0 1rem;">
        <!-- Notifications -->
    <div style="position: relative;" x-data="{ open: false }">
        <button 
            @click="open = !open"
            style="position: relative; padding: 0.5rem; border-radius: 0.5rem; transition: background-color 0.2s; color: white; background: none; border: none; cursor: pointer;"
            onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.15)'"
            onmouseout="this.style.backgroundColor='transparent'"
        >
            <svg style="width: 1.5rem; height: 1.5rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @if($unreadCount > 0)
                <span style="position: absolute; top: 0.25rem; right: 0.25rem; display: flex; height: 1.25rem; width: 1.25rem; align-items: center; justify-content: center; border-radius: 9999px; background-color: #ef4444; font-size: 0.75rem; font-weight: 700; color: white;">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </button>

        <!-- Notifications Dropdown -->
        <div 
            x-show="open"
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            style="position: absolute; right: 0; margin-top: 0.5rem; width: 20rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; z-index: 50; display: none;"
        >
            <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: #111827 !important;">Notifications</h3>
                @if($unreadCount > 0)
                    <button 
                        wire:click="markAllAsRead"
                        style="font-size: 0.75rem; color: #0ea5e9; background: none; border: none; cursor: pointer;"
                        onmouseover="this.style.color='#0284c7'"
                        onmouseout="this.style.color='#0ea5e9'"
                    >
                        Mark all as read
                    </button>
                @endif
            </div>
            
            <div style="max-height: 24rem; overflow-y: auto;">
                @forelse($notifications as $notification)
                    <div style="padding: 1rem; border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s; {{ $notification->read_at ? '' : 'background-color: #eff6ff;' }}"
                         onmouseover="this.style.backgroundColor='#f9fafb'"
                         onmouseout="this.style.backgroundColor='{{ $notification->read_at ? 'white' : '#eff6ff' }}'">
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <div style="flex: 1;">
                                <p style="font-size: 0.875rem; color: #111827 !important;">
                                    {{ $notification->data['message'] ?? 'New notification' }}
                                </p>
                                <p style="font-size: 0.75rem; color: #6b7280 !important; margin-top: 0.25rem;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(!$notification->read_at)
                                <button 
                                    wire:click="markAsRead('{{ $notification->id }}')"
                                    style="color: #0ea5e9; background: none; border: none; cursor: pointer;"
                                    onmouseover="this.style.color='#0284c7'"
                                    onmouseout="this.style.color='#0ea5e9'"
                                >
                                    <svg style="width: 1rem; height: 1rem; color: #0ea5e9 !important;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="padding: 2rem; text-align: center;">
                        <svg style="width: 3rem; height: 3rem; margin: 0 auto; color: #d1d5db !important;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p style="font-size: 0.875rem; color: #6b7280 !important; margin-top: 0.5rem;">No notifications</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- User Menu with Name -->
    <div style="position: relative;" x-data="{ open: false }">
        <button 
            @click="open = !open"
            style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; border-radius: 0.5rem; transition: background-color 0.2s; background: none; border: none; cursor: pointer;"
            onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.15)'"
            onmouseout="this.style.backgroundColor='transparent'"
        >
            <div style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-weight: 600; background-color: rgba(255, 255, 255, 0.3); color: white;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div style="text-align: left; display: none;" class="sm:block">
                <p style="font-size: 0.875rem; font-weight: 600; color: white;">
                    {{ auth()->user()->name }}
                </p>
                <p style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.8);">
                    {{ auth()->user()->email }}
                </p>
            </div>
            <svg style="width: 1rem; height: 1rem; color: white;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- User Dropdown Menu -->
        <div 
            x-show="open"
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            style="position: absolute; right: 0; margin-top: 0.5rem; width: 14rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; z-index: 50; display: none;"
        >
            <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                <p style="font-size: 0.875rem; font-weight: 600; color: #111827 !important;">{{ auth()->user()->name }}</p>
                <p style="font-size: 0.75rem; color: #6b7280 !important; margin-top: 0.25rem;">{{ auth()->user()->email }}</p>
            </div>
            
            <div style="padding: 0.5rem 0;">
                @if(\Filament\Facades\Filament::hasProfile())
                    <a href="{{ \Filament\Facades\Filament::getProfileUrl() }}" 
                       style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; font-size: 0.875rem; color: #374151 !important; text-decoration: none;"
                       onmouseover="this.style.backgroundColor='#f3f4f6'"
                       onmouseout="this.style.backgroundColor='transparent'">
                        <svg style="width: 1rem; height: 1rem; color: #374151 !important;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                @endif
                @if(\App\Filament\Pages\ManageDataCleanup::canAccess())
                    <a href="{{ \App\Filament\Pages\ManageDataCleanup::getUrl() }}"
                       style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; font-size: 0.875rem; color: #374151 !important; text-decoration: none;"
                       onmouseover="this.style.backgroundColor='#f3f4f6'"
                       onmouseout="this.style.backgroundColor='transparent'">
                        <svg style="width: 1rem; height: 1rem; color: #374151 !important;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Data Cleanup') }}
                    </a>
                @endif
            </div>

            <div style="border-top: 1px solid #e5e7eb; padding: 0.5rem 0;">
                <form method="POST" action="{{ route('filament.main.auth.logout') }}">
                    @csrf
                    <button type="submit" 
                            style="display: flex; align-items: center; gap: 0.75rem; width: 100%; padding: 0.5rem 1rem; font-size: 0.875rem; color: #dc2626 !important; background: none; border: none; cursor: pointer; text-align: left;"
                            onmouseover="this.style.backgroundColor='#fef2f2'"
                            onmouseout="this.style.backgroundColor='transparent'">
                        <svg style="width: 1rem; height: 1rem; color: #dc2626 !important;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>
