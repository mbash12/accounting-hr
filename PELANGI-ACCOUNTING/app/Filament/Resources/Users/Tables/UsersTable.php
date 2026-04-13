<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")->label(__("Name"))->searchable(),
                TextColumn::make("email")
                    ->label(__("Email address"))
                    ->searchable(),
                TextColumn::make("roles.name")
                    ->label(__("Roles"))
                    ->badge()
                    ->separator(", ")
                    ->formatStateUsing(fn($state) => str($state)->headline()),
                TextColumn::make("companies.name")
                    ->label(__("Companies"))
                    ->badge()
                    ->color("success"),
                // TextColumn::make("email_verified_at")
                //     ->label(__("Email Verified At"))
                //     ->dateTime()
                //     ->sortable(),
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
            ])
            ->filters([
                SelectFilter::make("roles")
                    ->relationship("roles", "name")
                    ->multiple()
                    ->searchable()
                    ->label(__("Filter by Roles"))
                    ->placeholder(__("All Roles")),
                SelectFilter::make("companies")
                    ->relationship(
                        "companies",
                        "name",
                        fn($query) => $query->select(
                            "companies.id",
                            "companies.name",
                        ),
                    )
                    ->multiple()
                    ->searchable()
                    ->label(__("Filter by Companies"))
                    ->placeholder(__("All Companies")),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
