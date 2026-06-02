<?php

namespace App\Filament\Actions;

use App\Filament\Resources\BankReconciliations\BankReconciliationResource;
use App\Models\BankAccount;
use App\Services\BankReconciliationService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ImportBankReconciliationAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'importBankReconciliation')
            ->label(__('Import Bank Statement'))
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                Select::make('bank_account_id')
                    ->label(__('Bank Account'))
                    ->options(fn() => BankAccount::query()
                        ->where('is_active', true)
                        ->when(
                            session('selected_company_id') && session('selected_company_id') !== 'all',
                            fn($q) => $q->where('company_id', session('selected_company_id'))
                        )
                        ->get()
                        ->mapWithKeys(fn($ba) => [$ba->id => "{$ba->account_number} - {$ba->account_name} ({$ba->bank?->name})"])
                    )
                    ->searchable()
                    ->required(),

                FileUpload::make('file')
                    ->label(__('Bank Statement File'))
                    ->helperText(__('Upload bank statement (.xlsx). Columns: Date, Description, Debit, Credit.'))
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(2048)
                    ->required()
                    ->disk('local')
                    ->directory('temp/bank-recon'),
            ])
            ->modalHeading(__('Import Bank Statement'))
            ->modalDescription(__('Upload a bank statement to auto-match against open invoices. Perfect matches are auto-confirmed; ambiguous matches are flagged for review.'))
            ->modalSubmitActionLabel(__('Upload & Match'))
            ->modalWidth('lg')
            ->extraModalActions([
                Action::make('download_template')
                    ->label(__('Download Template'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        try {
                            return \Maatwebsite\Excel\Facades\Excel::download(
                                new \App\Exports\BankReconciliationTemplateExport(),
                                'template-bank-statement.xlsx'
                            );
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title(__('Failed'))
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->action(function (array $data, $livewire) {
                try {
                    $filePath = Storage::disk('local')->path($data['file']);
                    $bankAccountId = (int) $data['bank_account_id'];

                    $companyId = session('selected_company_id');
                    if (! $companyId || $companyId === 'all') {
                        $companyId = BankAccount::find($bankAccountId)?->company_id;
                    } else {
                        $companyId = (int) $companyId;
                    }

                    if (! $companyId) {
                        throw new \RuntimeException(__('Please select a company.'));
                    }

                    $service = app(BankReconciliationService::class);
                    $reconciliation = $service->importFromExcel($filePath, $bankAccountId, $companyId);

                    Storage::disk('local')->delete($data['file']);

                    $total = $reconciliation->items()->count();
                    $matched = $reconciliation->items()->where('match_status', 'matched')->count();
                    $suggested = $reconciliation->items()->where('match_status', 'suggested')->count();
                    $unmatched = $reconciliation->items()->where('match_status', 'unmatched')->count();

                    Notification::make()
                        ->success()
                        ->title(__('Statement Imported'))
                        ->body(__(':total line(s). :matched auto-matched, :suggested suggested, :unmatched unmatched.', [
                            'total' => $total,
                            'matched' => $matched,
                            'suggested' => $suggested,
                            'unmatched' => $unmatched,
                        ]))
                        ->send();

                    if (method_exists($livewire, 'redirect')) {
                        $livewire->redirect(BankReconciliationResource::getUrl('view', ['record' => $reconciliation->id]));
                    }
                } catch (\Exception $e) {
                    if (isset($data['file'])) {
                        Storage::disk('local')->delete($data['file']);
                    }
                    Notification::make()
                        ->danger()
                        ->title(__('Import Failed'))
                        ->body($e->getMessage())
                        ->send();
                }
            });
    }
}
