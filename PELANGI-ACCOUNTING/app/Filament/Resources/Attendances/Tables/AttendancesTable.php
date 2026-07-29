<?php

namespace App\Filament\Resources\Attendances\Tables;

use App\Filament\Actions\ExportAttendancesAction;
use App\Filament\Actions\ImportAttendancesAction;
use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\AttendanceClock;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->recordUrl(fn ($record) => AttendanceResource::getUrl('view', ['record' => $record]))
            ->columns([
                TextColumn::make('employee.name')
                    ->label(__('Employee'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('check_in')
                    ->label(__('Check-in Time'))
                    ->time(),
                TextColumn::make('check_out')
                    ->label(__('Check-out Time'))
                    ->time(),
                TextColumn::make('late_minutes')
                    ->label(__('Late (Min)'))
                    ->numeric()
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('early_departure_minutes')
                    ->label(__('Early (Min)'))
                    ->numeric()
                    ->sortable()
                    ->color('danger'),
                TextColumn::make('status')
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
                    }),
            ])
            ->filters([
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['from']) {
                            $query->whereDate('date', '>=', $data['from']);
                        }
                        if ($data['to']) {
                            $query->whereDate('date', '<=', $data['to']);
                        }
                    }),
                SelectFilter::make('employee_id')
                    ->label(__('Employee'))
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('department_id')
                    ->label(__('Department'))
                    ->relationship('employee.department', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('clock_source')
                    ->label(__('Clock Source'))
                    ->options(AttendanceClock::sourceOptions())
                    ->query(fn ($query, array $data) => $query->when(
                        $data['value'] ?? null,
                        fn ($q, $source) => $q->whereHas('clocks', fn ($cq) => $cq->where('source', $source))
                    )),
            ])
            ->toolbarActions([
                ImportAttendancesAction::make(),
                ExportAttendancesAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }
}
