<?php

namespace App\Filament\Pages\Reports;

use App\Models\Account;
use App\Models\Company;
use App\Models\JournalEntryItem;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use UnitEnum;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class TrialBalance extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.trial-balance';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan Keuangan';

    protected static ?string $navigationLabel = 'Neraca Saldo';

    protected static ?string $title = 'Neraca Saldo';

    public function getTitle(): string
    {
        return 'Neraca Saldo';
    }

    public function getHeading(): string
    {
        return 'Neraca Saldo';
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
            ->label('Dari Tanggal')
            ->required()
            ->default(now()->startOfYear()),

            DatePicker::make('end_date')
            ->label('Sampai Tanggal')
            ->required()
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

        if (isset($reportData['error']) || !$reportData['company']) {
            return;
        }

        $pdf = Pdf::loadView('filament.pages.reports.trial-balance-pdf', $reportData)
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Neraca_Saldo_' . now()->format('Ymd') . '.pdf');
    }

    public function getReportData(): array
    {
        $startDate = $this->data['start_date'] ?? now()->startOfYear()->format('Y-m-d');
        $endDate = $this->data['end_date'] ?? now()->format('Y-m-d');
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            return [
                'rows' => collect(),
                'company' => null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => 'Silakan pilih perusahaan tertentu dari pemilih global untuk melihat laporan.',
            ];
        }

        $company = Company::find($companyId);
        $allAccounts = Account::where('company_id', $companyId)
            ->where('is_header', false)
            ->orderBy('code')
            ->get();

        $yearStart = \Carbon\Carbon::parse($startDate)->startOfYear()->format('Y-m-d');

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
                ->whereDate('date', '<', $startDate)
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
                ->where('is_posted', true);
        })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        // Calculate prior year net income to move to Retained Earnings
        $priorYearNetIncome = 0;
        foreach ($allAccounts as $account) {
            $root = substr($account->code, 0, 1);
            $isNominal = in_array($root, ['4', '5', '6', '7', '8', '9']);
            if ($isNominal) {
                $priorMov = $priorYearMovements->get($account->id);
                $priorD = $priorMov ? (float)$priorMov->total_debit : 0;
                $priorC = $priorMov ? (float)$priorMov->total_credit : 0;
                $openingBalance = (float)($account->opening_balance ?? 0);
                $isDebitNormal = in_array($root, ['5', '6', '7', '9']);

                $priorNet = $isDebitNormal
                    ? ($openingBalance + $priorD - $priorC)
                    : ($openingBalance + $priorC - $priorD);

                if ($isDebitNormal) {
                    $priorYearNetIncome -= $priorNet;
                }
                else {
                    $priorYearNetIncome += $priorNet;
                }
            }
        }

        $rows = collect();

        foreach ($allAccounts as $account) {
            $priorYMov = $priorYearMovements->get($account->id);
            $currYMov = $currentYearPriorMovements->get($account->id);
            $periodMov = $periodMovements->get($account->id);

            $priorYDebit = $priorYMov ? (float)$priorYMov->total_debit : 0;
            $priorYCredit = $priorYMov ? (float)$priorYMov->total_credit : 0;

            $currYDebit = $currYMov ? (float)$currYMov->total_debit : 0;
            $currYCredit = $currYMov ? (float)$currYMov->total_credit : 0;

            $periodDebit = $periodMov ? (float)$periodMov->total_debit : 0;
            $periodCredit = $periodMov ? (float)$periodMov->total_credit : 0;

            $openingBalance = (float)($account->opening_balance ?? 0);
            $root = substr($account->code, 0, 1);

            $isDebitNormal = in_array($root, ['1', '5', '6', '7', '9']);
            $isNominal = in_array($root, ['4', '5', '6', '7', '8', '9']);

            // Opening saldo calculation safely isolating nominal accounts
            if ($isNominal) {
                if ($isDebitNormal) {
                    $openNetBalance = $currYDebit - $currYCredit;
                }
                else {
                    $openNetBalance = $currYCredit - $currYDebit;
                }
            }
            else {
                if ($isDebitNormal) {
                    $openNetBalance = $openingBalance + $priorYDebit - $priorYCredit + $currYDebit - $currYCredit;
                }
                else {
                    $openNetBalance = $openingBalance + $priorYCredit - $priorYDebit + $currYCredit - $currYDebit;
                }
            }

            // Split opening saldo into D/K columns
            if ($isDebitNormal) {
                $openSaldoDebit = $openNetBalance >= 0 ? $openNetBalance : 0;
                $openSaldoCredit = $openNetBalance < 0 ? abs($openNetBalance) : 0;
            }
            else {
                $openSaldoCredit = $openNetBalance >= 0 ? $openNetBalance : 0;
                $openSaldoDebit = $openNetBalance < 0 ? abs($openNetBalance) : 0;
            }

            // Ending saldo = opening net + period movements
            if ($isDebitNormal) {
                $endNetBalance = $openNetBalance + $periodDebit - $periodCredit;
            }
            else {
                $endNetBalance = $openNetBalance + $periodCredit - $periodDebit;
            }

            // Split ending saldo into D/K columns
            if ($isDebitNormal) {
                $endSaldoDebit = $endNetBalance >= 0 ? $endNetBalance : 0;
                $endSaldoCredit = $endNetBalance < 0 ? abs($endNetBalance) : 0;
            }
            else {
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

        // Inject Dynamic Retained Earnings if applicable
        if (abs($priorYearNetIncome) >= 0.01) {
            $maxEquityCode = $allAccounts->filter(fn($a) => str_starts_with($a->code, '3'))->max('code');
            $reCode = $maxEquityCode ? $maxEquityCode . '-RE' : '3999';

            $openRetainedDebit = $priorYearNetIncome < 0 ? abs($priorYearNetIncome) : 0;
            $openRetainedCredit = $priorYearNetIncome >= 0 ? $priorYearNetIncome : 0;

            $rows->push([
                'code' => $reCode,
                'name' => 'Laba Ditahan (Auto Transfer)',
                'open_debit' => $openRetainedDebit,
                'open_credit' => $openRetainedCredit,
                'period_debit' => 0,
                'period_credit' => 0,
                'end_debit' => $openRetainedDebit,
                'end_credit' => $openRetainedCredit,
            ]);
        }

        $rows = $rows->sortBy('code')->values();

        return [
            'rows' => $rows,
            'company' => $company,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}