<?php

namespace App\Filament\Resources\ReceivableLists\Tables;

use App\Filament\Resources\ReceivableLists\ReceivableListResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReceivableListsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label(__("Customer Name"))
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make("total_receivable")
                    ->label(__("Total Business Receivable"))
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
                    ->label(__("Remaining"))
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
                        ->url(fn ($record) => ReceivableListResource::getUrl('view', ['record' => $record])),
                ]),
            ])
            ->defaultSort('latest_invoice_date', 'desc')
            ->recordUrl(fn ($record) => ReceivableListResource::getUrl('view', ['record' => $record]))
            ->emptyStateHeading(__('No receivables found'))
            ->emptyStateDescription(__('All invoices have been paid.'));
    }
}

