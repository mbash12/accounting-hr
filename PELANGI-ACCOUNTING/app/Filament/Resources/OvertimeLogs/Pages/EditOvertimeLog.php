<?php

namespace App\Filament\Resources\OvertimeLogs\Pages;

use App\Filament\Resources\OvertimeLogs\OvertimeLogResource;
use App\Filament\Resources\OvertimeLogs\Schemas\OvertimeLogForm;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditOvertimeLog extends EditRecord
{
    protected static string $resource = OvertimeLogResource::class;

    public function form(Schema $schema): Schema
    {
        return OvertimeLogForm::configure($schema, disabled: true);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label(__('Approve'))
                ->color('success')
                ->icon('heroicon-o-check')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'draft')
                ->action(function () {
                    $this->record->update([
                        'status' => 'approved',
                        'approved_by_user_id' => auth()->id(),
                    ]);
                    Notification::make()
                        ->title(__('Overtime approved'))
                        ->success()
                        ->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
            Action::make('reject')
                ->label(__('Reject'))
                ->color('danger')
                ->icon('heroicon-o-x-mark')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'draft')
                ->action(function () {
                    $this->record->update([
                        'status' => 'rejected',
                        'approved_by_user_id' => auth()->id(),
                    ]);
                    Notification::make()
                        ->title(__('Overtime rejected'))
                        ->danger()
                        ->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
            DeleteAction::make(),
        ];
    }
}
