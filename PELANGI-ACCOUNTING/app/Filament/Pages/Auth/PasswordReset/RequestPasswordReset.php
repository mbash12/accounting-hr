<?php

namespace App\Filament\Pages\Auth\PasswordReset;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BasePasswordResetRequest;
use Filament\Notifications\Notification;

class RequestPasswordReset extends BasePasswordResetRequest
{
    public function getView(): string
    {
        return 'filament.pages.auth.password-reset.request';
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
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label(__('Email Address'))
            ->placeholder(__('Enter your email address'))
            ->required()
            ->email()
            ->maxLength(255)
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->extraAttributes(['class' => '!border-b border-gray-300 rounded-none !shadow-none h-[45px] leading-2xl']);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getRequestFormAction(),
        ];
    }

    protected function getRequestFormAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('request')
            ->label(__('Send Password Reset Link'))
            ->submit('request')
            ->size('lg')
            ->extraAttributes([
                'class' => 'w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 rounded-lg transition-all duration-200',
                'wire:target' => 'request',
                'wire:loading.attr' => 'disabled',
            ]);
    }

    protected function getSentNotification(string $status): ?Notification
    {
        return Notification::make()
            ->title(__('Password Reset Link Sent'))
            ->body(__('If an account with that email address exists, we have sent you a password reset link.'))
            ->success();
    }
}
