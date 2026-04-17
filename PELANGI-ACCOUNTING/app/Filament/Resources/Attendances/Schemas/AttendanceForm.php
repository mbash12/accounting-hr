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

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Detail Kehadiran'))
                    ->schema([
                        Select::make('employee_id')
                            ->label(__('Karyawan'))
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
                            ->label(__('Tanggal'))
                            ->required()
                            ->default(now()),
                        TimePicker::make('check_in')
                            ->label(__('Jam Masuk')),
                        TimePicker::make('check_out')
                            ->label(__('Jam Keluar')),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'present' => __('Hadir'),
                                'late' => __('Terlambat'),
                                'absent' => __('Alpa'),
                                'permit' => __('Izin'),
                                'leave' => __('Cuti'),
                            ])
                            ->default('present')
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('Catatan'))
                            ->columnSpanFull(),
                    ])->columns(2),
                Section::make(__('Lokasi & Bukti'))
                    ->collapsible()
                    ->schema([
                        TextInput::make('lat_in')->label(__('Lat Masuk'))->numeric(),
                        TextInput::make('lng_in')->label(__('Lng Masuk'))->numeric(),
                        TextInput::make('lat_out')->label(__('Lat Keluar'))->numeric(),
                        TextInput::make('lng_out')->label(__('Lng Keluar'))->numeric(),
                        FileUpload::make('photo_in_path')
                            ->label(__('Foto Masuk'))
                            ->image()
                            ->directory('attendances'),
                        FileUpload::make('photo_out_path')
                            ->label(__('Foto Keluar'))
                            ->image()
                            ->directory('attendances'),
                    ])->columns(2),
            ]);
    }
}
