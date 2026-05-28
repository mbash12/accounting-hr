<?php

namespace App\Filament\Resources\BiometricEmployees;

use App\Filament\Resources\BiometricEmployees\Pages\EditBiometricEmployee;
use App\Filament\Resources\BiometricEmployees\Pages\ListBiometricEmployees;
use App\Models\BiometricEmployee;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BiometricEmployeeResource extends Resource
{
    protected static ?string $model = BiometricEmployee::class;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Biometric Employees');
    }

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('machine_user_id')
                    ->label(__('Machine ID'))
                    ->required()
                    ->numeric()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                \Filament\Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                \Filament\Forms\Components\Select::make('employee_id')
                    ->label(__('Employee'))
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('machine_user_id', 'asc')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('machine_user_id')
                    ->label(__('Machine ID'))
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('employee.name')
                    ->label(__('Employee'))
                    ->placeholder(__('Not linked')),
                \Filament\Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('Active')),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBiometricEmployees::route('/'),
            'edit' => EditBiometricEmployee::route('/{record}/edit'),
        ];
    }
}
