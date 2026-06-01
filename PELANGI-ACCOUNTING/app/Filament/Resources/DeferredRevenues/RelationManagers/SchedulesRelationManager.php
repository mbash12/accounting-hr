<?php

namespace App\Filament\Resources\DeferredRevenues\RelationManagers;

use App\Models\DeferredRevenueSchedule;
use App\Services\DeferredRevenueService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Amortization Schedule';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('period_number')
                    ->label(__('Period'))
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('period_start')
                    ->date('d/m/Y')
                    ->label(__('From')),
                TextColumn::make('period_end')
                    ->date('d/m/Y')
                    ->label(__('To')),
                TextColumn::make('planned_amount')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->label(__('Planned Amount')),
                TextColumn::make('recognized_amount')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->label(__('Recognized')),
                TextColumn::make('recognized_date')
                    ->date('d/m/Y')
                    ->label(__('Recognition Date'))
                    ->placeholder(__('—')),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'recognized' => 'Recognized',
                        'reversed' => 'Reversed',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'recognized' => 'success',
                        'reversed' => 'danger',
                        default => 'gray',
                    })
                    ->label(__('Status')),
                TextColumn::make('journalEntry.entry_number')
                    ->label(__('Journal Entry'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->label(__('Notes'))
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('period_number', 'asc')
            ->headerActions([
                Action::make('generateSchedule')
                    ->label(__('Generate Schedule'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading(__('Generate Amortization Schedule'))
                    ->modalDescription(__('This will regenerate the schedule from the contract parameters. Existing pending entries will be replaced.'))
                    ->action(function () {
                        $service = app(DeferredRevenueService::class);
                        $service->generateSchedule($this->getOwnerRecord());

                        Notification::make()
                            ->title(__('Schedule generated'))
                            ->success()
                            ->send();

                        $this->resetTable();
                    })
                    ->visible(fn () => in_array($this->getOwnerRecord()->status, ['draft', 'active'])),
            ])
            ->actions([
                Action::make('recognize')
                    ->label(__('Recognize'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(__('Recognize Revenue'))
                    ->modalDescription(__('This will create a journal entry to recognize this period\'s revenue.'))
                    ->action(function (DeferredRevenueSchedule $record) {
                        $service = app(DeferredRevenueService::class);

                        try {
                            $journalEntry = $service->recognizeRevenue($record);

                            if ($journalEntry) {
                                Notification::make()
                                    ->title(__('Revenue recognized'))
                                    ->body("Journal entry: {$journalEntry->entry_number}")
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title(__('Could not recognize revenue'))
                                    ->body(__('Please check that account mappings are configured for Deferred Revenue.'))
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title(__('Error'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }

                        $this->resetTable();
                    })
                    ->visible(fn (DeferredRevenueSchedule $record) => $record->status === 'pending'),

                Action::make('reverse')
                    ->label(__('Reverse'))
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(__('Reverse Recognition'))
                    ->modalDescription(__('This will delete the journal entry and reset this period to pending.'))
                    ->action(function (DeferredRevenueSchedule $record) {
                        $service = app(DeferredRevenueService::class);

                        try {
                            $service->reverseRecognition($record);

                            Notification::make()
                                ->title(__('Recognition reversed'))
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title(__('Error'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }

                        $this->resetTable();
                    })
                    ->visible(fn (DeferredRevenueSchedule $record) => $record->status === 'recognized'),

                Action::make('viewJournal')
                    ->label(__('View Journal'))
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->url(fn (DeferredRevenueSchedule $record) => $record->journal_entry_id
                        ? route('filament.main.resources.journal-entries.edit', $record->journal_entry_id)
                        : null)
                    ->openUrlInNewTab()
                    ->visible(fn (DeferredRevenueSchedule $record) => $record->journal_entry_id !== null),
            ]);
    }
}
