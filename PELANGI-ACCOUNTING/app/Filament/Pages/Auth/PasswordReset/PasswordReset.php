<?php

namespace App\Filament\Pages\Auth\PasswordReset;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\PasswordReset\ResetPassword as BasePasswordReset;
use Filament\Notifications\Notification;

class PasswordReset extends BasePasswordReset
{
    public function getView(): string
    {
        return 'filament.pages.auth.password-reset.reset';
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
                    ->label(__('New Password'))
                    ->placeholder(__('Enter your new password'))
                    ->required()
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->maxLength(255),
                $this->getPasswordConfirmationFormComponent()
                    ->label(__('Confirm New Password'))
                    ->placeholder(__('Confirm your new password'))
                    ->required()
                    ->password()
                    ->revealable()
                    ->same('password')
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
            ->extraAttributes(['class' => '!border-b border-gray-300 rounded-none !shadow-none h-[45px] leading-2xl']);
    }

    protected function getPasswordFormComponent(): TextInput
    {
        return TextInput::make('password')
            ->label(__('New Password'))
            ->placeholder(__('Enter your new password'))
            ->required()
            ->password()
            ->revealable()
            ->minLength(8)
            ->maxLength(255)
            ->extraInputAttributes(['tabindex' => 2])
            ->extraAttributes(['class' => '!border-b border-gray-300 rounded-none !shadow-none h-[45px] leading-2xl']);
    }

    protected function getPasswordConfirmationFormComponent(): TextInput
    {
        return TextInput::make('password_confirmation')
            ->label(__('Confirm New Password'))
            ->placeholder(__('Confirm your new password'))
            ->required()
            ->password()
            ->revealable()
            ->same('password')
            ->maxLength(255)
            ->extraInputAttributes(['tabindex' => 3])
            ->extraAttributes(['class' => '!border-b border-gray-300 rounded-none !shadow-none h-[45px] leading-2xl']);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getResetPasswordFormAction(),
        ];
    }

    public function getResetPasswordFormAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('resetPassword')
            ->label(__('Reset Password'))
            ->submit('resetPassword')
            ->size('lg')
            ->extraAttributes([
                'class' => 'w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 rounded-lg transition-all duration-200',
                'wire:target' => 'resetPassword',
                'wire:loading.attr' => 'disabled',
            ]);
    }

    protected function getSuccessNotification(string $status): ?Notification
    {
        return Notification::make()
            ->title(__('Password Reset Successful'))
            ->body(__('Your password has been successfully reset. You can now log in with your new password.'))
            ->success();
    }
}
