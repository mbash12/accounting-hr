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
                    ->label(__("Supplier Name"))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make("total_payable")
                    ->label(__("Total Payable"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make("total_paid")
                    ->label(__("Paid"))
                    ->numeric()
                    ->money("IDR")
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make("total_outstanding")
                    ->label(__("Outstanding"))
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
                        ->label(__('Details'))
                        ->url(fn ($record) => PayableListResource::getUrl('view', ['record' => $record])),
                ]),
            ])
            ->defaultSort('latest_invoice_date', 'desc')
            ->recordUrl(fn ($record) => PayableListResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading(__('No payables found'))
            ->emptyStateDescription(__('All invoices have been paid.'));
    }
}


