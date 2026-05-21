<?php

namespace App\Filament\Resources\FaqCategories\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('FAQ Categories'))
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label(__('Category Name')),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->label(__('Order')),
                        Select::make('company_id')
                            ->relationship('company', 'name')
                            ->disabled()
                            ->label(__('Company')),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make(__('Questions'))
                    ->schema([
                        Repeater::make('faqs')
                            ->relationship('faqs')
                            ->schema([
                                TextInput::make('question')
                                    ->required()
                                    ->label(__('Question')),
                                RichEditor::make('answer')
                                    ->required()
                                    ->label(__('Answer')),
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->label(__('Order')),
                            ])
                            ->orderColumn('sort_order')
                            ->columns(1)
                            ->label('')
                    ])
                    ->columnSpanFull()
            ]);
    }
}
