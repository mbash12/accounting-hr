<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use App\Filament\Pages\Auth\PasswordReset\PasswordReset;
use App\Http\Middleware\SetDefaultCompany;
use App\Livewire\Components\CompanySelector\CompanySelector;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\ManageOpeningBalances;
use App\Filament\Pages\ManageAccountMappings;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\Livewire;

class MainPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('main')
            ->path('main')
            ->login(Login::class)
            ->passwordReset(
                RequestPasswordReset::class,
                PasswordReset::class
            )

            ->colors([
                'primary' => Color::Sky,
            ])
            ->sidebarFullyCollapsibleOnDesktop()
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                function (): string {
                    return '<div class="fi-brand-logo">
                        <img src="' . asset('thelogo.png') . '" alt="Logo">
                    </div>';
                },
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                function (): string {
                    $userName = auth()->user() ? e(auth()->user()->name) : '';
                    return '
                        <link rel="icon" type="image/x-icon" href="' . asset('fav.png') . '">
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                        <link rel="stylesheet" href="' . asset('css/filament/admin/theme.css') . '">
                        <script>
                            // Force light mode immediately
                            document.documentElement.classList.remove("dark");
                            document.documentElement.classList.add("light");
                            document.documentElement.setAttribute("data-theme", "light");

                            // Clear any theme preferences
                            localStorage.removeItem("theme");
                            localStorage.removeItem("filament-theme");
                            localStorage.setItem("theme", "light");

                            document.addEventListener("DOMContentLoaded", function () {
                                const userMenu = document.querySelector(".fi-user-menu .fi-link");
                                if (userMenu && "' . $userName . '") {
                                    userMenu.setAttribute("data-user-name", "' . $userName . '");
                                }

                                // Continuously enforce light mode
                                setInterval(() => {
                                    document.documentElement.classList.remove("dark");
                                    document.documentElement.classList.add("light");
                                }, 100);
                            });

                            // Also enforce light mode after DOM is loaded
                            window.addEventListener("load", function () {
                                document.documentElement.classList.remove("dark");
                                document.documentElement.classList.add("light");
                            });
                        </script>
                    ';
                },
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                function (): string {
                    $userName = auth()->user() ? e(auth()->user()->name) : '';
                    $isLoginPage = request()->routeIs('filament.main.auth.login');
                    return '
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                        <link rel="stylesheet" href="' . asset('css/filament/admin/theme.css') . '">
                        <script>
                            // Force light mode immediately
                            document.documentElement.classList.remove("dark");
                            document.documentElement.classList.add("light");
                            document.documentElement.setAttribute("data-theme", "light");

                            // Clear any theme preferences
                            localStorage.removeItem("theme");
                            localStorage.removeItem("filament-theme");
                            localStorage.setItem("theme", "light");

                            document.addEventListener("DOMContentLoaded", function () {
                                const userMenu = document.querySelector(".fi-user-menu .fi-link");
                                if (userMenu && "' . $userName . '") {
                                    userMenu.setAttribute("data-user-name", "' . $userName . '");
                                }

                                // Continuously enforce light mode
                                setInterval(() => {
                                    document.documentElement.classList.remove("dark");
                                    document.documentElement.classList.add("light");
                                }, 100);
                            });

                            // Also enforce light mode after DOM is loaded
                            window.addEventListener("load", function () {
                                document.documentElement.classList.remove("dark");
                                document.documentElement.classList.add("light");
                            });
                        </script>
                    ';
                },
            )
            // ->renderHook(
            //     PanelsRenderHook::BODY_START,
            //     function (): string {
            //         $isLoginPage = request()->is('main/login') || request()->routeIs('filament.main.auth.login');
            //         if (!$isLoginPage) return '';

            //         return '
            //             <div class="fi-auth-right-content" style="display: none;">
            //                 <img src="' . asset('thelogo.png') . '" alt="Logo">
            //                 <h2>Welcome to Accounting System</h2>
            //                 <p>Manage your finances with ease and precision. Your complete accounting solution.</p>
            //             </div>

            //             <script>
                //                 document.addEventListener("DOMContentLoaded", function() {
                //                     // Add right column content
                //                     const rightContent = document.querySelector(".fi-auth-right-content");
                //                     const authCard = document.querySelector(".fi-auth-card > div");

                //                     if (rightContent && authCard && authCard.children.length === 1) {
                //                         // Create right column
                //                         const rightColumn = document.createElement("div");
                //                         rightColumn.innerHTML = rightContent.innerHTML;
                //                         authCard.appendChild(rightColumn);
                //                     }
                //                 });
                //             </script>
            //         ';
            //     },
            // )

            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                function (): string {
                    return \Blade::render('<livewire:custom-topbar />');
                },
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_START,
                function (): string {
                    if (auth()->check() && auth()->user()->companies()->count() > 0) {
                        return \Blade::render('<livewire:components.company-selector.company-selector />');
                    }
                    return '';
                },
            )
            ->darkMode(false)
            ->profile()
            ->globalSearch(false)
            // ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->navigationGroups([
                NavigationGroup::make(__('Entity Management'))
                    ->icon(asset('icons/users.svg')),
                NavigationGroup::make(__('Supporting Data'))
                    ->icon(asset('icons/master-data.svg')),
                NavigationGroup::make(__('Master Data'))
                    ->icon(asset('icons/master-data.svg')),
                NavigationGroup::make(__('Sales'))
                    ->icon(asset('icons/sales.svg')),
                NavigationGroup::make(__('Purchasing'))
                    ->icon(asset('icons/purchase.svg')),
                NavigationGroup::make(__('HR & Payroll'))
                    ->icon(asset('icons/users.svg')),
                NavigationGroup::make(__('Cash & Bank'))
                    ->icon(asset('icons/cash.svg')),
                NavigationGroup::make(__('Ledger'))
                    ->icon(asset('icons/ledger.svg')),
                NavigationGroup::make(__('Buku Besar'))
                    ->icon(asset('icons/ledger.svg')),
                NavigationGroup::make(__('Laporan Keuangan'))
                    ->icon(asset('icons/report.svg')),
                NavigationGroup::make(__('Laporan HR & Payroll'))
                    ->icon(asset('icons/report.svg')),
            ])

            // ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
                ManageOpeningBalances::class,
                ManageAccountMappings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationIcon(null),
            ])
            ->authMiddleware([
                Authenticate::class,
                SetDefaultCompany::class,
            ]);
    }
}