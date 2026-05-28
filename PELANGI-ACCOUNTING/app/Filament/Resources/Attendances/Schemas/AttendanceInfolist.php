<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Models\AttendanceClock;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Summary'))
                    ->schema([
                        TextEntry::make('employee.name')
                            ->label(__('Employee')),
                        TextEntry::make('date')
                            ->label(__('Date'))
                            ->date(),
                        TextEntry::make('check_in')
                            ->label(__('Check-in'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('check_out')
                            ->label(__('Check-out'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('late_minutes')
                            ->label(__('Late (min)')),
                        TextEntry::make('early_departure_minutes')
                            ->label(__('Early Departure (min)')),
                        TextEntry::make('status')
                            ->label(__('Status'))
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'present' => __('Present'),
                                'late' => __('Late'),
                                'absent' => __('Absent'),
                                'permit' => __('Permit'),
                                'leave' => __('Leave'),
                                default => $state,
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'present' => 'success',
                                'late' => 'warning',
                                'absent' => 'danger',
                                'permit' => 'info',
                                'leave' => 'gray',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),

                Section::make(__('Clock Timeline'))
                    ->schema([
                        RepeatableEntry::make('clocks')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('clocked_at')
                                    ->label(__('Time'))
                                    ->dateTime(),
                                TextEntry::make('type')
                                    ->label(__('Dir'))
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        AttendanceClock::TYPE_IN => 'IN',
                                        AttendanceClock::TYPE_OUT => 'OUT',
                                        default => $state,
                                    })
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        AttendanceClock::TYPE_IN => 'success',
                                        AttendanceClock::TYPE_OUT => 'warning',
                                        default => 'gray',
                                    }),
                                TextEntry::make('source')
                                    ->label(__('Source'))
                                    ->formatStateUsing(fn (string $state): string => AttendanceClock::sourceOptions()[$state] ?? $state)
                                    ->badge(),
                                TextEntry::make('location')
                                    ->label(__('Map'))
                                    ->placeholder('-')
                                    ->url(fn ($record): ?string => $record->latitude && $record->longitude
                                        ? 'https://www.google.com/maps?q=' . $record->latitude . ',' . $record->longitude
                                        : null)
                                    ->openUrlInNewTab()
                                    ->formatStateUsing(fn ($record): string => $record->latitude && $record->longitude
                                        ? '📍 ' . number_format((float) $record->latitude, 6) . ', ' . number_format((float) $record->longitude, 6)
                                        : '-'),
                                ImageEntry::make('photo_path')
                                    ->label(__('Photo'))
                                    ->disk('public')
                                    ->visibility('public')
                                    ->size(50)
                                    ->square()
                                    ->placeholder('-'),
                                TextEntry::make('notes')
                                    ->label(__('Notes'))
                                    ->placeholder('-'),
                            ])
                            ->columns(6)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
