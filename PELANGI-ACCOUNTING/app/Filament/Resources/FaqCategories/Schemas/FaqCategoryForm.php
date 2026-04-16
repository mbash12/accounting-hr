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
                Section::make(__('Kategori FAQ'))
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label(__('Nama Kategori')),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->label(__('Urutan')),
                        Select::make('company_id')
                            ->relationship('company', 'name')
                            ->disabled()
                            ->label(__('Perusahaan')),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make(__('Daftar Pertanyaan'))
                    ->schema([
                        Repeater::make('faqs')
                            ->relationship('faqs')
                            ->schema([
                                TextInput::make('question')
                                    ->required()
                                    ->label(__('Pertanyaan')),
                                RichEditor::make('answer')
                                    ->required()
                                    ->label(__('Jawaban')),
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->label(__('Urutan')),
                            ])
                            ->orderColumn('sort_order')
                            ->columns(1)
                            ->label('')
                    ])
                    ->columnSpanFull()
            ]);
    }
}
