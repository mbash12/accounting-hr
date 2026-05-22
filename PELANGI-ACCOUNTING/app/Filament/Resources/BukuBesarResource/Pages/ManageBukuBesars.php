<?php

namespace App\Filament\Resources\BukuBesarResource\Pages;

use App\Filament\Resources\BukuBesarResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBukuBesars extends ManageRecords
{
    protected static string $resource = BukuBesarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for General Ledger view
        ];
    }

    public function getFooter(): ?\Illuminate\Contracts\View\View
    {
        $table = $this->getTable();
        $accountIdFilter = $table->getFilter('account_id');
        $companyIdFilter = $table->getFilter('company_id');
        $dateFilter = $table->getFilter('date');

        $accountId = $accountIdFilter ? ($accountIdFilter->getState()['account_id'] ?? null) : null;
        $companyId = $companyIdFilter ? ($companyIdFilter->getState()['value'] ?? null) : session('selected_company_id');
        
        if ($companyId === 'all') { 
            $companyId = null; 
        }

        if ($accountId === 'all') {
            $accountId = null;
        }

        $dateState = $dateFilter ? ($dateFilter->getState() ?? []) : [];
        $fromDate = $dateState['from'] ?? null;
        $untilDate = $dateState['until'] ?? null;

        $saldoAwal = 0;
        
        if ($accountId) {
            $obRecord = \App\Models\OpeningBalance::where('account_id', $accountId)
                ->when($companyId, fn($q) => $q->where('company_id', $companyId))
                ->first();

            $obRecord = \App\Models\OpeningBalance::where('account_id', $accountId)
                ->when($companyId, fn($q) => $q->where('company_id', $companyId))
                ->first();

            if ($obRecord) {
$baseAmount = (float) $obRecord->amount;
                if ($obRecord->balance_type === 'credit') {
                    $baseAmount = -1 * $baseAmount;
                }
                
                $saldoAwal = $baseAmount;
                
                $historicalMutasi = 0;
                if ($fromDate) {
                     $historicalMutasi = \App\Models\JournalEntryItem::where('account_id', $accountId)
                        ->when($companyId, fn($q) => $q->whereHas('journalEntry', fn($jq) => $jq->where('company_id', $companyId)))
                        ->whereHas('journalEntry', function($q) use ($obRecord, $fromDate) {
                            $q->whereDate('date', '>=', $obRecord->date); 
                            $q->whereDate('date', '<', $fromDate);
                            $q->where(function($sq) {
                                $sq->where('sub_module', '!=', 'opening_balance')
                                   ->orWhereNull('sub_module');
                            });
                        })
                        ->selectRaw('COALESCE(SUM(CAST(debit AS NUMERIC)), 0) - COALESCE(SUM(CAST(credit AS NUMERIC)), 0) as balance')
                        ->value('balance') ?? 0;
                }
                    
                $saldoAwal += $historicalMutasi;

            } else {
                $historicalMutasi = 0;
                if ($fromDate) {
                    $historicalMutasi = \App\Models\JournalEntryItem::where('account_id', $accountId)
                        ->when($companyId, fn($q) => $q->whereHas('journalEntry', fn($jq) => $jq->where('company_id', $companyId)))
                        ->whereHas('journalEntry', function($q) use ($fromDate) {
                            $q->whereDate('date', '<', $fromDate);
                            $q->where(function($sq) {
                                $sq->where('sub_module', '!=', 'opening_balance')
                                   ->orWhereNull('sub_module');
                            });
                        })
                        ->selectRaw('COALESCE(SUM(CAST(debit AS NUMERIC)), 0) - COALESCE(SUM(CAST(credit AS NUMERIC)), 0) as balance')
                        ->value('balance') ?? 0;
                }
                $saldoAwal = $historicalMutasi;
            }
        }

        $query = $this->getAllTableSummaryQuery();
        
        $totalDebit = (float) (clone $query)->selectRaw('COALESCE(SUM(CAST(debit AS NUMERIC)), 0) as total')->value('total') ?? 0;
        $totalKredit = (float) (clone $query)->selectRaw('COALESCE(SUM(CAST(credit AS NUMERIC)), 0) as total')->value('total') ?? 0;
        
        $mutasiBalance = $totalDebit - $totalKredit;

        $saldoAkhir = $saldoAwal + $mutasiBalance;

        return view('filament.tables.summaries.buku-besar-footer', [
            'saldoAwal' => $saldoAwal,
            'mutasiBalance' => $mutasiBalance,
            'saldoAkhir' => $saldoAkhir,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalKredit,
        ]);
    }
}
