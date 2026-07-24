<?php

namespace App\Filament\Pages\Reports;

use App\Models\Account;
use App\Models\Company;
use App\Models\JournalEntryItem;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class TrialBalance extends Page implements HasForms
{
    use HasPageShield, InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.trial-balance';

    protected static string|UnitEnum|null $navigationGroup = 'Financial Reports';

    protected static ?string $navigationLabel = 'Trial Balance';

    protected static ?string $title = 'Trial Balance';

    public function getTitle(): string
    {
        return 'Trial Balance';
    }

    public function getHeading(): string
    {
        return 'Trial Balance';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->startOfYear()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);
    }

    public function form($form)
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->label('From Date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->validate())
                    ->default(now()->startOfYear()),

                DatePicker::make('end_date')
                    ->label('To Date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->validate())
                    ->default(now())
                    ->suffixAction(function () {
                        return \Filament\Actions\Action::make('filter_date')
                            ->icon('heroicon-m-funnel')
                            ->action('filterReport')
                            ->color('primary');
                    }),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function filterReport(): void
    {
        $this->validate();
    }

    public function downloadPdf()
    {
        $reportData = $this->getReportData();

        if (isset($reportData['error']) || ! $reportData['company']) {
            return;
        }

        $pdf = Pdf::loadView('filament.pages.reports.trial-balance-pdf', $reportData)
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Trial_Balance_'.now()->format('Ymd').'.pdf');
    }

    public function getReportData(): array
    {
        $startDate = filled($this->data['start_date'] ?? null)
            ? $this->data['start_date']
            : now()->startOfYear()->format('Y-m-d');
        $endDate = filled($this->data['end_date'] ?? null)
            ? $this->data['end_date']
            : now()->format('Y-m-d');
        $companyId = session('selected_company_id');

        if (! $companyId || $companyId === 'all') {
            return [
                'rows' => collect(),
                'company' => null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => 'Please select a specific company from the global selector to view the report.',
            ];
        }

        $company = Company::find($companyId);
        $allAccounts = Account::withTrashed()
            ->where('company_id', $companyId)
            ->orderBy('code')
            ->get();

        $yearStart = \Carbon\Carbon::parse($startDate)->startOfYear()->format('Y-m-d');
        $hasPostedOpeningJournal = \App\Models\JournalEntry::query()
            ->where('company_id', $companyId)
            ->where('sub_module', 'opening_balance')
            ->where('is_posted', true)
            ->exists();

        // ── Prior years movements (Before yearStart) ──────
        $priorYearMovements = JournalEntryItem::select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereHas('journalEntry', function ($q) use ($yearStart, $companyId) {
                $q->where('company_id', $companyId)
                    ->whereDate('date', '<', $yearStart)
                    ->where('is_posted', true);
            })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        // ── Current year prior movements (Between yearStart and startDate - 1) ──────
        $currentYearPriorMovements = JournalEntryItem::select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereHas('journalEntry', function ($q) use ($yearStart, $startDate, $companyId) {
                $q->where('company_id', $companyId)
                    ->whereDate('date', '>=', $yearStart)
                    ->where(function ($query) use ($startDate) {
                        $query->whereDate('date', '<', $startDate)
                            ->orWhere(function ($openingQuery) use ($startDate) {
                                $openingQuery->whereDate('date', $startDate)
                                    ->where('sub_module', 'opening_balance');
                            });
                    })
                    ->where('is_posted', true);
            })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        // ── Period movements: posted transactions IN [startDate, endDate] ─────
        $periodMovements = JournalEntryItem::select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
            ->whereHas('journalEntry', function ($q) use ($startDate, $endDate, $companyId) {
                $q->where('company_id', $companyId)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->where('is_posted', true)
                    ->where(function ($query) {
                        $query->whereNull('sub_module')
                            ->orWhere('sub_module', '!=', 'opening_balance');
                    });
            })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        // Calculate prior year net income to move to Retained Earnings
        $priorYearNetIncome = 0;
        foreach ($allAccounts as $account) {
            $isNominal = $account->isRevenueAccount() || $account->isExpenseAccount();
            if ($isNominal) {
                $priorMov = $priorYearMovements->get($account->id);
                $priorD = $priorMov ? (float) $priorMov->total_debit : 0;
                $priorC = $priorMov ? (float) $priorMov->total_credit : 0;
                $openingBalance = $account->reportOpeningBalance($hasPostedOpeningJournal);
                $isDebitNormal = $account->isDebitNormal();

                $priorNet = $isDebitNormal
                    ? ($openingBalance + $priorD - $priorC)
                    : ($openingBalance + $priorC - $priorD);

                if ($isDebitNormal) {
                    $priorYearNetIncome -= $priorNet;
                } else {
                    $priorYearNetIncome += $priorNet;
                }
            }
        }

        $rows = collect();

        foreach ($allAccounts as $account) {
            $priorYMov = $priorYearMovements->get($account->id);
            $currYMov = $currentYearPriorMovements->get($account->id);
            $periodMov = $periodMovements->get($account->id);

            $priorYDebit = $priorYMov ? (float) $priorYMov->total_debit : 0;
            $priorYCredit = $priorYMov ? (float) $priorYMov->total_credit : 0;

            $currYDebit = $currYMov ? (float) $currYMov->total_debit : 0;
            $currYCredit = $currYMov ? (float) $currYMov->total_credit : 0;

            $periodDebit = $periodMov ? (float) $periodMov->total_debit : 0;
            $periodCredit = $periodMov ? (float) $periodMov->total_credit : 0;

            $openingBalance = $account->reportOpeningBalance($hasPostedOpeningJournal);
            $isDebitNormal = $account->isDebitNormal();
            $isNominal = $account->isRevenueAccount() || $account->isExpenseAccount();

            // Opening saldo calculation safely isolating nominal accounts
            if ($isNominal) {
                if ($isDebitNormal) {
                    $openNetBalance = $currYDebit - $currYCredit;
                } else {
                    $openNetBalance = $currYCredit - $currYDebit;
                }
            } else {
                if ($isDebitNormal) {
                    $openNetBalance = $openingBalance + $priorYDebit - $priorYCredit + $currYDebit - $currYCredit;
                } else {
                    $openNetBalance = $openingBalance + $priorYCredit - $priorYDebit + $currYCredit - $currYDebit;
                }
            }

            // Split opening saldo into D/K columns
            if ($isDebitNormal) {
                $openSaldoDebit = $openNetBalance >= 0 ? $openNetBalance : 0;
                $openSaldoCredit = $openNetBalance < 0 ? abs($openNetBalance) : 0;
            } else {
                $openSaldoCredit = $openNetBalance >= 0 ? $openNetBalance : 0;
                $openSaldoDebit = $openNetBalance < 0 ? abs($openNetBalance) : 0;
            }

            // Ending saldo = opening net + period movements
            if ($isDebitNormal) {
                $endNetBalance = $openNetBalance + $periodDebit - $periodCredit;
            } else {
                $endNetBalance = $openNetBalance + $periodCredit - $periodDebit;
            }

            // Split ending saldo into D/K columns
            if ($isDebitNormal) {
                $endSaldoDebit = $endNetBalance >= 0 ? $endNetBalance : 0;
                $endSaldoCredit = $endNetBalance < 0 ? abs($endNetBalance) : 0;
            } else {
                $endSaldoCredit = $endNetBalance >= 0 ? $endNetBalance : 0;
                $endSaldoDebit = $endNetBalance < 0 ? abs($endNetBalance) : 0;
            }

            // Skip rows where everything is zero
            if ($openSaldoDebit == 0 && $openSaldoCredit == 0 &&
            $periodDebit == 0 && $periodCredit == 0 &&
            $endSaldoDebit == 0 && $endSaldoCredit == 0) {
                continue;
            }

            $rows->push([
                'account_id' => $account->id,
                'code' => $account->code,
                'name' => $account->name,
                'open_debit' => $openSaldoDebit,
                'open_credit' => $openSaldoCredit,
                'period_debit' => $periodDebit,
                'period_credit' => $periodCredit,
                'end_debit' => $endSaldoDebit,
                'end_credit' => $endSaldoCredit,
            ]);
        }

        // Inject Dynamic Retained Earnings if applicable (skip when prior year period closing already posted)
        $skipDynamicPriorRe = app(\App\Services\PeriodClosingService::class)
            ->hasPostedClosingBefore((int) $companyId, $startDate);

        if (abs($priorYearNetIncome) >= 0.01 && ! $skipDynamicPriorRe) {
            $maxEquityCode = $allAccounts->filter(fn ($a) => str_starts_with($a->code, '3'))->max('code');
            $reCode = $maxEquityCode ? $maxEquityCode.'-RE' : '3999';

            $openRetainedDebit = $priorYearNetIncome < 0 ? abs($priorYearNetIncome) : 0;
            $openRetainedCredit = $priorYearNetIncome >= 0 ? $priorYearNetIncome : 0;

            $rows->push([
                'account_id' => null,
                'code' => $reCode,
                'name' => 'Retained Earnings (Auto Transfer)',
                'open_debit' => $openRetainedDebit,
                'open_credit' => $openRetainedCredit,
                'period_debit' => 0,
                'period_credit' => 0,
                'end_debit' => $openRetainedDebit,
                'end_credit' => $openRetainedCredit,
            ]);
        }

        $rows = $rows->sortBy('code')->values();

        $imbalance = [
            'opening' => round((float) $rows->sum('open_debit') - (float) $rows->sum('open_credit'), 2),
            'period' => round((float) $rows->sum('period_debit') - (float) $rows->sum('period_credit'), 2),
            'ending' => round((float) $rows->sum('end_debit') - (float) $rows->sum('end_credit'), 2),
        ];

        return [
            'rows' => $rows,
            'imbalance' => $imbalance,
            'company' => $company,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
