<?php

namespace App\Filament\Resources\PayableLists\Tables;

use App\Filament\Resources\PayableLists\PayableListResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class PayableListsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label(__("Nama Pemasok"))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make("total_payable")
                    ->label(__("Total Hutang Usaha"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make("total_paid")
                    ->label(__("Dibayar"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make("total_outstanding")
                    ->label(__("Sisa"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable()
                    ->alignEnd()
                    ->color('warning')
                    ->weight('bold'),
            ])

            ->actions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\ViewAction::make()
                        ->label(__('Detail'))
                        ->url(fn ($record) => PayableListResource::getUrl('view', ['record' => $record])),
                ]),
            ])
            ->defaultSort('latest_invoice_date', 'desc')
            ->recordUrl(fn ($record) => PayableListResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading(__('Tidak ada hutang ditemukan'))
            ->emptyStateDescription(__('Semua faktur telah dibayar.'));
    }
}


