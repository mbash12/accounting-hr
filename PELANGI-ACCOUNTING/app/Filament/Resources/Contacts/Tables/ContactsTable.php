<?php

namespace App\Filament\Resources\Contacts\Tables;

use App\Filament\Actions\ExportContactsAction;
use App\Filament\Actions\ImportContactsAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")->label(__("Name"))->searchable()->weight("bold"),
                TextColumn::make("contact_code")
                    ->searchable()
                    ->copyable()
                    ->label(__("Code")),
                TextColumn::make("contact_person")
                    ->searchable()
                    ->label(__("Contact Person"))
                    ->placeholder(__("N/A")),
                TextColumn::make("email")->label(__("Email"))->searchable()->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("phone")->label(__("Phone"))->searchable()->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("types")
                    ->label(__("Types"))
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $types = [];
                        if ($record->is_customer) {
                            $types[] = __("Customer");
                        }
                        if ($record->is_supplier) {
                            $types[] = __("Supplier");
                        }
                        if ($record->is_employee) {
                            $types[] = __("Employee");
                        }
                        if ($record->is_sales) {
                            $types[] = __("Sales");
                        }
                        return empty($types) ? [__("N/A")] : $types;
                    })
                    ->color(
                        fn($state): string => match (is_array($state) ? count($state) : 0) {
                            0 => "gray",
                            1 => "success",
                            2 => "warning",
                            default => "info",
                        },
                    ),

                IconColumn::make("is_pkp")->boolean()->label(__("PKP")),

                IconColumn::make("is_active")->boolean()->label(__("Active")),

                TextColumn::make("createdByUser.name")
                    ->label(__("Created By"))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                ImportContactsAction::make(),
                ExportContactsAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
