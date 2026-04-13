<?php

namespace App\Filament\Resources\JournalEntries\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class JournalEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("entry_number")
                    ->label(__("Entry No."))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("date")
                    ->label(__("Date"))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make("reference_no")
                    ->label(__("Reference"))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make("amount")
                    ->label(__("Total"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable(),
                TextColumn::make("description")
                    ->label(__("Description"))
                    ->searchable()
                    ->toggleable(),
                IconColumn::make("is_posted")
                    ->label(__("Posted"))
                    ->boolean()
                    ->toggleable(),
                TextColumn::make("department.name")
                    ->label(__("Department"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make("postedByUser.name")
                    ->label(__("Posted By"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->label(__("Created At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->label(__("Updated At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("deleted_at")
                    ->label(__("Deleted At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_posted')
                    ->label(__('Posted'))
                    ->options([
                        1 => __('Yes'),
                        0 => __('No'),
                    ])
                    ->placeholder(__('All')),
                TrashedFilter::make(),
            ])
            ->defaultSort('date', 'desc')
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    \Filament\Actions\Action::make('printVoucher')
                        ->label(__('Cetak Voucher'))
                        ->icon('heroicon-o-printer')
                        ->url(fn (\App\Models\JournalEntry $record) => route('journal-voucher.print-voucher', $record->id))
                        ->openUrlInNewTab(),
                    \App\Filament\Actions\ViewJournalVoucherAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
