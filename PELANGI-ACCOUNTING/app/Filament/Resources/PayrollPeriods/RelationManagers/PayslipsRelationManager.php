<?php

namespace App\Filament\Resources\PayrollPeriods\RelationManagers;

use App\Models\Payslip;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class PayslipsRelationManager extends RelationManager
{
    protected static string $relationship = 'payslips';

    protected static ?string $title = 'Payslips';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            // Read-only mostly
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->label('Payslip No.')->searchable(),
                TextColumn::make('employee.name')->label('Employee')->searchable(),
                TextColumn::make('basic_salary')->money('IDR'),
                TextColumn::make('gross_salary')->money('IDR'),
                TextColumn::make('net_salary')->money('IDR'),
            ])
            ->defaultSort('number', 'asc')
            ->recordActions([
                Action::make('downloadSlip')
                    ->label(__('Download Payslip'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Payslip $record): string => route('payslip.pdf.single', $record->id))
                    ->openUrlInNewTab(),
            ]);
    }
}
