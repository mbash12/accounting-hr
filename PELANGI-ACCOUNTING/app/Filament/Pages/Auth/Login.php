<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function getView(): string
    {
        return 'filament.pages.auth.login';
    }

    protected function throwFailureValidationException(): never
    {
        // Show a notification for failed login
        Notification::make()
            ->title(__('Login Failed'))
            ->body(__('Invalid email address or password. Please try again.'))
            ->danger()
            ->send();

        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    protected function getRateLimitedNotification(TooManyRequestsException $exception): ?Notification
    {
        return Notification::make()
            ->title(__('Too Many Login Attempts'))
            ->body(__("Please wait {$exception->secondsUntilAvailable} seconds before trying again."))
            ->danger()
            ->send();
    }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent()
                    ->label(__('Email Address'))
                    ->placeholder(__('Enter your email address'))
                    ->required()
                    ->email()
                    ->maxLength(255),
                $this->getPasswordFormComponent()
                    ->label(__('Password'))
                    ->placeholder(__('Enter your password'))
                    ->required()
                    ->revealable()
                    ->maxLength(255),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('login')
            ->label(__('Email Address'))
            ->placeholder(__('Enter your email address'))
            ->required()
            ->email()
            ->maxLength(255)
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->extraAttributes(['class' => '!border-b border-gray-300 rounded-none !shadow-none h-[45px] leading-2xl']);
    }

    protected function getPasswordFormComponent(): TextInput
    {
        return TextInput::make('password')
            ->label(__('Password'))
            ->placeholder(__('Enter your password'))
            ->required()
            ->password()
            ->revealable()
            ->maxLength(255)
            ->extraInputAttributes(['tabindex' => 2])
            ->extraAttributes(['class' => '!border-b border-gray-300 rounded-none !shadow-none h-[45px] leading-2xl']);
    }

    protected function getRememberFormComponent(): \Filament\Forms\Components\Checkbox
    {
        return \Filament\Forms\Components\Checkbox::make('remember')
            ->label(__('Remember me'))
            ->default(false)
            ->extraAttributes(['tabindex' => 3, 'class' => 'text-primary-600 focus:ring-primary-500']);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $login = $data['login'];

        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $loginType => $login,
            'password' => $data['password'],
        ];
    }

    /**
     * Override authenticate to handle 'login' field instead of 'email'
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        // Extract email from login field for rate limiting
        $login = $data['login'] ?? '';
        $email = filter_var($login, FILTER_VALIDATE_EMAIL) ? $login : '';

        if ($this->isLoginRateLimited($email)) {
            return null;
        }

        /** @var SessionGuard $authGuard */
        $authGuard = Filament::auth();

        $authProvider = $authGuard->getProvider();
        $credentials = $this->getCredentialsFromFormData($data);

        $user = $authProvider->retrieveByCredentials($credentials);

        if (!$user || !$authProvider->validateCredentials($user, $credentials)) {
            $this->userUndertakingMultiFactorAuthentication = null;

            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        if (!$authGuard->attemptWhen($credentials, function (Authenticatable $user): bool {
            if (!($user instanceof FilamentUser)) {
                return true;
            }

            return $user->canAccessPanel(Filament::getCurrentOrDefaultPanel());
        }, $data['remember'] ?? false)) {
            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function hasFullWidthForm(): bool
    {
        return true;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
        ];
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(__('Login'))
            ->submit('authenticate')
            ->size('lg')
            ->extraAttributes([
                'class' => 'w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 rounded-lg transition-all duration-200',
                'wire:target' => 'authenticate',
                'wire:loading.attr' => 'disabled',
            ]);
    }
}
