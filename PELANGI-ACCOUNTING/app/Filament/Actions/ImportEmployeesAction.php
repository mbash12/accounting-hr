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
                    ->label('Employee Data File')
                    ->helperText('Upload Excel file (.xlsx) with columns: employee_id, name, email, nik, npwp, department_code, position, hire_date, status, ptkp_status, bank_name, bank_account_number, bank_account_holder, bpjs_kesehatan_number, bpjs_ketenagakerjaan_number, basic_salary, active_status')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(2048)
                    ->required()
                    ->reactive(),
            ])
            ->modalHeading('Import Employees')
            ->modalDescription('Upload Excel file with employee data. You can download the template below to see the expected format.')
            ->extraModalActions([
                \Filament\Actions\Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return Excel::download(
                                new \App\Exports\EmployeesTemplateExport(),
                                'employee-import-template.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Template Download Failed')
                                ->body('An error occurred while downloading employee template: ' . $e->getMessage())
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
                        ->body('Employee data imported successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Import Failed')
                        ->body('An error occurred while importing employees: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
