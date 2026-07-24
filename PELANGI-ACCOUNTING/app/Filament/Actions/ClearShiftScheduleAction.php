<?php

namespace App\Filament\Actions;

use App\Models\ShiftSchedule;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ClearShiftScheduleAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'clearSchedule')
            ->label('Clear Data')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Clear all shift schedules?')
            ->modalDescription('This will permanently delete all shift schedules for the current month, scoped to the current company. This action cannot be undone.')
            ->modalSubmitActionLabel('Yes, clear all')
            ->authorize(fn () => auth()->check() && auth()->user()->can('Delete:ShiftSchedule'))
            ->action(function ($livewire) {
                $year  = (int) $livewire->year;
                $month = (int) $livewire->month;
                $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
                    ? (int) session('selected_company_id') : null;

                $firstDate = CarbonImmutable::create($year, $month, 1);
                $lastDate  = $firstDate->endOfMonth();

                $deleted = ShiftSchedule::query()
                    ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                    ->whereBetween('date', [$firstDate->toDateString(), $lastDate->toDateString()])
                    ->forceDelete();

                Notification::make()
                    ->title('Schedules cleared')
                    ->body("Deleted {$deleted} schedule rows for {$firstDate->format('F Y')}.")
                    ->success()
                    ->send();

                // Trigger refresh board via event — page listen ke 'shift-schedule-uploaded'
                $livewire->dispatch('shift-schedule-uploaded');
            });
    }
}
