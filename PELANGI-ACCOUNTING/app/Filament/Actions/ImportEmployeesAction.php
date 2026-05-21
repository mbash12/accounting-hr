<?php

namespace App\Filament\Actions;

use App\Imports\EmployeesImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ImportEmployeesAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'import')
            ->label('Import')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('File Data Karyawan')
                    ->helperText('Unggah file Excel (.xlsx) dengan kolom: employee_id, name, email, nik, npwp, department_code, position, hire_date, status, ptkp_status, bank_name, bank_account_number, bank_account_holder, bpjs_kesehatan_number, bpjs_ketenagakerjaan_number, basic_salary, active_status')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(2048)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Karyawan')
            ->modalDescription('Upload Excel file with karyawan. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\EmployeesTemplateExport(),
                                'template-impor-karyawan.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading template karyawan: ' . $e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data) {
                try {
                    Excel::import(new EmployeesImport, $data['file']);

                    Notification::make()
                        ->success()
                        ->title('Import Successful')
                        ->body('Data karyawan berhasil diimpor.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing karyawan: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
