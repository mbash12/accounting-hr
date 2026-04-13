<?php

namespace App\Filament\Pages\Reports;

use App\Models\Company;
use App\Models\Department;
use App\Models\PayrollPeriod;
use App\Models\Payslip;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use UnitEnum;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class PayrollReport extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.payroll-report';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan HR & Payroll';

    protected static ?string $navigationLabel = 'Laporan Payroll';

    public function getTitle(): string
    {
        return __('Laporan Ringkasan Payroll');
    }

    public function getHeading(): string
    {
        return __('Laporan Ringkasan Payroll');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Laporan HR & Payroll');
    }

    protected static ?int $navigationSort = 10;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'payroll_period_id' => PayrollPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->first()?->id,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('payroll_period_id')
                    ->label(__('Periode Payroll'))
                    ->options(fn() => \App\Services\CompanyFilterService::applyCompanyFilter(PayrollPeriod::query())
                        ->orderBy('year', 'desc')
                        ->orderBy('month', 'desc')
                        ->get()
                        ->pluck('name', 'id')
                    )
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn() => $this->validate()),
                
                Select::make('department_id')
                    ->label(__('Departemen'))
                    ->options(fn() => \App\Services\CompanyFilterService::applyCompanyFilter(Department::query())->pluck('name', 'id'))
                    ->placeholder(__('Semua Departemen'))
                    ->live()
                    ->afterStateUpdated(fn() => $this->validate()),
            ])
            ->columns(2)
            ->statePath('data');
    }

    protected function getViewData(): array
    {
        return $this->getRawData();
    }

    protected function getRawData(): array
    {
        $periodId = $this->data['payroll_period_id'] ?? null;
        $deptId = $this->data['department_id'] ?? null;
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            return [
                'payslips' => collect(),
                'totals' => [],
                'company' => null,
                'period' => null,
                'error' => __('Silakan pilih perusahaan tertentu dari pemilih global.')
            ];
        }

        if (!$periodId) {
            return [
                'payslips' => collect(),
                'totals' => [],
                'company' => Company::find($companyId),
                'period' => null,
                'error' => __('Pilih periode payroll terlebih dahulu.')
            ];
        }

        $query = Payslip::with(['employee', 'employee.department'])
            ->where('payroll_period_id', $periodId)
            ->where('company_id', $companyId);

        if ($deptId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        $payslips = $query->get();

        $totals = [
            'basic_salary' => $payslips->sum('basic_salary'),
            'allowance' => $payslips->sum('total_allowance'),
            'deduction' => $payslips->sum('total_deduction'),
            'gross' => $payslips->sum('gross_salary'),
            'pph21' => $payslips->sum('pph21'),
            'bpjs_employee' => $payslips->sum(fn($p) => $p->bpjs_kesehatan_employee + $p->bpjs_ketenagakerjaan_employee),
            'bpjs_employer' => $payslips->sum(fn($p) => $p->bpjs_kesehatan_employer + $p->bpjs_ketenagakerjaan_employer),
            'net' => $payslips->sum('net_salary'),
        ];

        return [
            'payslips' => $payslips,
            'totals' => $totals,
            'company' => Company::find($companyId),
            'period' => PayrollPeriod::find($periodId),
            'department' => $deptId ? Department::find($deptId) : null,
        ];
    }

    public function downloadPdf()
    {
        $data = $this->getRawData();
        if (isset($data['error'])) return;

        $pdf = Pdf::loadView('filament.pages.reports.payroll-report-pdf', $data)
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Payroll_' . ($data['period']?->name ?? 'report') . '.pdf');
    }
}
