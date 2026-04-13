<?php

namespace App\Filament\Resources\Permits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Pengajuan Izin / Cuti'))
                    ->schema([
                        Select::make('employee_id')
                            ->label(__('Karyawan'))
                            ->relationship(
                                name: 'employee', 
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => \App\Services\CompanyFilterService::applyCompanyFilter($query)
                            )
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('type')
                            ->label(__('Tipe'))
                            ->options([
                                'sick' => __('Sakit'),
                                'annual_leave' => __('Cuti Tahunan'),
                                'unpaid_leave' => __('Cuti Diluar Tanggungan'),
                                'maternity_leave' => __('Cuti Melahirkan'),
                                'other_permit' => __('Izin Lainnya'),
                            ])
                            ->required(),
                        DatePicker::make('start_date')
                            ->label(__('Tanggal Mulai'))
                            ->required(),
                        DatePicker::make('end_date')
                            ->label(__('Tanggal Selesai'))
                            ->required(),
                        Textarea::make('reason')
                            ->label(__('Alasan'))
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'pending' => __('Menunggu'),
                                'approved' => __('Disetujui'),
                                'rejected' => __('Ditolak'),
                            ])
                            ->default('pending')
                            ->required(),
                        FileUpload::make('attachment_path')
                            ->label(__('Lampiran (Surat Dokter, dll)'))
                            ->directory('permits')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
