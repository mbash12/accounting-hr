<?php

namespace App\Filament\Resources\FixedAssets\RelationManagers;

use App\Filament\Forms\Components\RoundedIntegerMoneyInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Transactions';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('transaction_type')
                ->options([
                    'acquisition' => 'Acquisition',
                    'depreciation' => 'Depreciation',
                    'revaluation' => 'Revaluation',
                    'disposal' => 'Disposal',
                    'impairment' => 'Impairment',
                ])
                ->required()
                ->label('Transaction Type'),
            DatePicker::make('date')
                ->required()
                ->default(now())
                ->label('Date'),
            TextInput::make('reference_no')
                ->maxLength(255)
                ->label('Reference No.'),
            ...RoundedIntegerMoneyInput::schema(
                name: 'journal_value',
                label: 'Journal Value',
                required: true,
            ),
            Textarea::make('description')
                ->rows(2)
                ->columnSpanFull()
                ->label('Description'),
            Toggle::make('create_journal')
                ->label('Auto Create Journal')
                ->helperText('Create journal entry based on accounts in the asset category')
                ->default(false)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Date'),
                TextColumn::make('transaction_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'acquisition' => 'Acquisition',
                        'depreciation' => 'Depreciation',
                        'revaluation' => 'Revaluation',
                        'disposal' => 'Disposal',
                        'impairment' => 'Impairment',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'acquisition' => 'success',
                        'depreciation' => 'warning',
                        'disposal' => 'danger',
                        default => 'gray',
                    })
                    ->label('Type'),
                TextColumn::make('journal_value')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->label('Value'),
                IconColumn::make('create_journal')
                    ->boolean()
                    ->label('Journal'),
                TextColumn::make('reference_no')
                    ->label('Reference'),
            ])
            ->defaultSort('date', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Create Transaction')
                    ->modalHeading(__('Create Fixed Asset Transaction'))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['company_id'] = $this->getOwnerRecord()->company_id;
                        $data['created_by_user_id'] = auth()->id();
                        return $data;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
