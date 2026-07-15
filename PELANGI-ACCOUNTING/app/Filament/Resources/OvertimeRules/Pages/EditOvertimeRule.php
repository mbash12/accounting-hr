<?php

namespace App\Filament\Resources\OvertimeRules\Pages;

use App\Filament\Resources\OvertimeRules\OvertimeRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOvertimeRule extends EditRecord
{
    protected static string $resource = OvertimeRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected ?bool $wasMarkedAsDefault = null;

    protected function beforeSave(): void
    {
        $this->wasMarkedAsDefault = $this->record->isDirty('is_default')
            && $this->record->is_default === true;
    }

    protected function afterSave(): void
    {
        if ($this->wasMarkedAsDefault !== true) {
            return;
        }

        Notification::make()
            ->title(__('Default rule updated'))
            ->body(__('This rule is now the only default for the company. Other default rules have been automatically unmarked.'))
            ->success()
            ->send();
    }
}
