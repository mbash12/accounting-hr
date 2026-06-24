<div class="fi-auth-login-root">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        .fi-auth-login-root {
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            position: fixed;
            inset: 0;
            background-image: url('{{ asset('auth-bg.png') }}');
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fi-input {
            height: 45px;
            border: none;
            padding: 0;
        }

        .fi-input-wrp {
            --tw-ring-shadow: none;
        }

        .fi-input-wrp-suffix {
            border-inline-start: none;
        }

        /* Loading state styles */
        .fi-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .fi-loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Notification styles */
        .fi-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            transform: translateX(0);
            transition: all 0.3s ease;
        }

        .fi-notification.error {
            background-color: #ef4444;
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .fi-notification.success {
            background-color: #10b981;
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Hide Filament's loading spinner */
        .fi-loading-indicator {
            display: none !important;
        }

        @media (max-width: 640px) {
            .fi-auth-login-root {
                overflow-y: auto;
                align-items: center;
                padding-top: 2rem;
                padding-bottom: 2rem;
                background-image: none !important;
                background-color: #f0f9ff;
            }

            .fi-auth-login-root .login-card {
                width: 100% !important;
                max-width: 400px !important;
                padding: 1.5rem !important;
            }

            .fi-auth-login-root .login-logo {
                height: 5rem !important;
            }

            .fi-notification {
                left: 10px;
                right: 10px;
                max-width: none;
            }
        }

        @media (max-width: 480px) {
            .fi-auth-login-root {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }

            .fi-auth-login-root .login-card {
                max-width: 340px !important;
                padding: 1.25rem !important;
                border-radius: 0.75rem !important;
            }

            .fi-auth-login-root .login-logo {
                height: 4rem !important;
            }

            .fi-auth-login-root h2 {
                font-size: 1.25rem !important;
            }
        }
    </style>

    <!-- Filament's built-in notification system -->
    <style>
        /* Override notification positioning for our custom layout */
        .filament-notifications {
            z-index: 9999 !important;
        }
    </style>

    <div class="w-full h-full flex">
        <div class="w-full sm:w-1/2 flex flex-col items-center justify-center px-6 sm:px-0">
            <div class="fi-auth-grid">
                <!-- Left Column - Password Reset Request Form -->
                <img src="{{ asset('logo.png') }}" alt="Logo" class="login-logo h-24 mx-auto mb-6">

                <div class="login-card bg-white p-8 !rounded-3xl shadow-lg w-[450px]">
                    <h2 class="text-2xl font-light mb-6">Forgot Password</h2>
                    <p class="text-gray-600 text-sm mb-6">Enter your email address and we will send you a link to reset your password.</p>

                    <!-- Form with loading state -->
                    <form wire:submit="request"
                          class="fi-auth-form"
                          wire:target="request"
                          wire:loading.class="fi-loading">

                        {{ $this->form }}

                        <!-- Error display for form validation -->
                        @error('data.email')
                            <div class="text-red-500 text-sm mt-2 mb-4 p-3 bg-red-50 rounded-lg border border-red-200">
                                <span class="font-medium">Email Error:</span> {{ $message }}
                            </div>
                        @enderror

                        <div class="flex flex-col gap-4 mt-6">
                            <div>
                                <!-- Request Reset button with loading state -->
                                {{ $this->getRequestFormAction() }}
                            </div>

                            <a href="{{ filament()->getLoginUrl() }}"
                               class="fi-auth-link text-blue-600 hover:text-blue-800 text-sm transition-colors text-center">
                                Back to Sign In
                            </a>
                        </div>
                    </form>

                    <div class="text-center text-gray-500 mt-6 text-sm">
                       @ {{ date('Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts

    <!-- Custom script for basic form validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Livewire errors more gracefully
            Livewire.hook('component.failed', (component, message, stack) => {
                console.error('Component error:', message);
            });
        });
    </script>
</div>
