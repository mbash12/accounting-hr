<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__("Company Information"))
                ->schema([
                    TextInput::make("name")->label(__("Name"))->required()->columnSpanFull(),
                    Textarea::make("description")->label(__("Description"))->columnSpanFull(),
                    TextInput::make("tax_id")->label(__("Tax ID")),
                    Toggle::make("is_ppn")
                        ->label(__("PPN (PKP)"))
                        ->helperText(__("Aktifkan jika perusahaan ini terdaftar sebagai Pengusaha Kena Pajak (PKP). Non-aktif = Non-PPN."))
                        ->default(false)
                        ->onIcon("heroicon-m-check-badge")
                        ->offIcon("heroicon-m-x-circle")
                        ->onColor("success")
                        ->offColor("gray")
                        ->columnSpanFull(),
                    FileUpload::make("tax_document")
                        ->label(__("Tax ID Document"))
                        // ->helperText(__("Upload document related to tax ID"))
                        ->disk("public")
                        ->directory("companies/tax-documents")
                        ->acceptedFileTypes([
                            "application/pdf",
                            "image/jpeg",
                            "image/png",
                            "image/jpg",
                        ])
                        ->maxSize(5120),
                    Select::make("business_type_id")
                        ->relationship("businessType", "name")
                        ->label(__("Business Type"))
                        ->required(),
                ])
                ->columns(2),

            // Section::make(__("Fiscal Settings"))
            //     ->schema([
            //         DatePicker::make("fiscal_year_start")->label(__("Fiscal Year Start"))->required(),
            //         DatePicker::make("fiscal_year_end")->label(__("Fiscal Year End"))->required(),
            //         TextInput::make("tax_period")
            //             ->label(__("Tax Period"))
            //             ->required()
            //             ->default("monthly"),
            //         Toggle::make("is_active")->label(__("Active"))->required(),
            //     ])
            //     ->columns(2),

            Section::make(__("Addresses"))
                ->schema([
                    Textarea::make("billing_address_line_1")
                        ->label(__("Billing Address"))
                        ->columnSpanFull(),
                    // Hidden billing fields
                    Hidden::make("billing_address_line_2")->default(null),
                    Hidden::make("billing_city")->default(null),
                    Hidden::make("billing_state")->default(null),
                    Hidden::make("billing_postal_code")->default(null),
                    Hidden::make("billing_country")->default(null),

                    Textarea::make("delivery_address_line_1")
                        ->label(__("Delivery Address"))
                        ->columnSpanFull(),
                    // Hidden delivery fields
                    Hidden::make("delivery_address_line_2")->default(null),
                    Hidden::make("delivery_city")->default(null),
                    Hidden::make("delivery_state")->default(null),
                    Hidden::make("delivery_postal_code")->default(null),
                    Hidden::make("delivery_country")->default(null),
                ])
                ->columns(1),

            Section::make(__("Additional Information"))
                ->schema([
                    FileUpload::make("photo")
                        ->label(__("Company Logo"))
                        ->avatar()
                        ->disk("public")
                        ->directory("companies")
                        ->maxSize(2048),
                    Select::make("created_by_user_id")
                        ->relationship("createdByUser", "name")
                        ->default(function () {
                            return auth()->id();
                        })
                        ->disabled()
                        ->required()
                        ->label(__("Created By")),
                ])
                ->columns(2),
        ]);
    }
}
