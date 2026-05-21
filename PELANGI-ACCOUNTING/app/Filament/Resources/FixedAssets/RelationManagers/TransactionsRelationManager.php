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

    protected static ?string $title = 'Transaksi';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('transaction_type')
                ->options([
                    'acquisition' => 'Perolehan',
                    'depreciation' => 'Penyusutan',
                    'revaluation' => 'Revaluasi',
                    'disposal' => 'Pelepasan',
                    'impairment' => 'Penurunan Nilai',
                ])
                ->required()
                ->label('Jenis Transaksi'),
            DatePicker::make('date')
                ->required()
                ->default(now())
                ->label('Tanggal'),
            TextInput::make('reference_no')
                ->maxLength(255)
                ->label('No. Referensi'),
            ...RoundedIntegerMoneyInput::schema(
                name: 'journal_value',
                label: 'Nilai Jurnal',
                required: true,
            ),
            Textarea::make('description')
                ->rows(2)
                ->columnSpanFull()
                ->label('Keterangan'),
            Toggle::make('create_journal')
                ->label('Buat Jurnal Otomatis')
                ->helperText('Buat jurnal entry berdasarkan akun di kategori aset')
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
                    ->label('Tanggal'),
                TextColumn::make('transaction_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'acquisition' => 'Perolehan',
                        'depreciation' => 'Penyusutan',
                        'revaluation' => 'Revaluasi',
                        'disposal' => 'Pelepasan',
                        'impairment' => 'Penurunan Nilai',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'acquisition' => 'success',
                        'depreciation' => 'warning',
                        'disposal' => 'danger',
                        default => 'gray',
                    })
                    ->label('Jenis'),
                TextColumn::make('journal_value')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->label('Nilai'),
                IconColumn::make('create_journal')
                    ->boolean()
                    ->label('Jurnal'),
                TextColumn::make('reference_no')
                    ->label('Referensi'),
            ])
            ->defaultSort('date', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Transaksi')
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
