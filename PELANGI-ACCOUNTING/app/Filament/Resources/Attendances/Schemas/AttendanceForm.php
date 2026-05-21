<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Attendance Details'))
                    ->schema([
                        Select::make('employee_id')
                            ->label(__('Employee'))
                            ->relationship(
                                name: 'employee', 
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query) {
                                    $companyId = session('selected_company_id');
                                    if ($companyId) {
                                        $query->where('company_id', $companyId);
                                    } elseif (auth()->check()) {
                                        $ids = auth()->user()->companies()->pluck('companies.id');
                                        if ($ids->isNotEmpty()) $query->whereIn('company_id', $ids);
                                    }
                                }
                            )
                            ->required()
                            ->searchable()
                            ->preload(),
                        DatePicker::make('date')
                            ->label(__('Date'))
                            ->required()
                            ->default(now()),
                        TimePicker::make('check_in')
                            ->label(__('Check-in Time')),
                        TimePicker::make('check_out')
                            ->label(__('Check-out Time')),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'present' => __('Present'),
                                'late' => __('Late'),
                                'absent' => __('Absent'),
                                'permit' => __('Permit'),
                                'leave' => __('Leave'),
                            ])
                            ->default('present')
                            ->required(),
                        Textarea::make('notes_in')
                            ->label(__('Check-in Notes'))
                            ->columnSpanFull(),
                        Textarea::make('notes_out')
                            ->label(__('Check-out Notes'))
                            ->columnSpanFull(),
                    ])->columns(2),
                Section::make(__('Location & Proof'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('lat_in')->label(__('Check-in Lat'))->numeric(),
                        TextInput::make('lng_in')->label(__('Check-in Lng'))->numeric(),
                        TextInput::make('lat_out')->label(__('Check-out Lat'))->numeric(),
                        TextInput::make('lng_out')->label(__('Check-out Lng'))->numeric(),
                        FileUpload::make('photo_in_path')
                            ->label(__('Check-in Photo'))
                            ->image()
                            ->disk('public')
                            ->directory('attendances')
                            ->visibility('public')
                            ->formatStateUsing(function ($state) {
                                if (! is_string($state) || $state === '') {
                                    return $state;
                                }

                                // Normalize values from API/manual uploads into storage-relative paths.
                                if (Str::startsWith($state, '/storage/')) {
                                    return Str::after($state, '/storage/');
                                }

                                if (Str::contains($state, '/storage/')) {
                                    return Str::after($state, '/storage/');
                                }

                                return $state;
                            }),
                        FileUpload::make('photo_out_path')
                            ->label(__('Check-out Photo'))
                            ->image()
                            ->disk('public')
                            ->directory('attendances')
                            ->visibility('public')
                            ->formatStateUsing(function ($state) {
                                if (! is_string($state) || $state === '') {
                                    return $state;
                                }

                                // Normalize values from API/manual uploads into storage-relative paths.
                                if (Str::startsWith($state, '/storage/')) {
                                    return Str::after($state, '/storage/');
                                }

                                if (Str::contains($state, '/storage/')) {
                                    return Str::after($state, '/storage/');
                                }

                                return $state;
                            }),
                    ])->columns(2),
            ]);
    }
}
