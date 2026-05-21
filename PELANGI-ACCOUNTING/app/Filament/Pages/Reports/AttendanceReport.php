<?php

namespace App\Filament\Pages\Reports;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Permit;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use UnitEnum;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class AttendanceReport extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.reports.attendance-report';

    protected static string|UnitEnum|null $navigationGroup = 'HR & Payroll Reports';

    protected static ?string $navigationLabel = 'Attendance Report';

    public function getTitle(): string
    {
        return __('Attendance Summary Report');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Laporan HR & Payroll');
    }

    protected static ?int $navigationSort = 11;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'month' => now()->month,
            'year' => now()->year,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('month')
                    ->label(__('Bulan'))
                    ->options([
                        1 => __('Januari'), 2 => __('Februari'), 3 => __('Maret'), 4 => __('April'),
                        5 => __('Mei'), 6 => __('Juni'), 7 => __('Juli'), 8 => __('Agustus'),
                        9 => __('September'), 10 => __('Oktober'), 11 => __('November'), 12 => __('Desember'),
                    ])
                    ->required()
                    ->live(),
                
                Select::make('year')
                    ->label(__('Tahun'))
                    ->options(array_combine(range(now()->year - 2, now()->year + 1), range(now()->year - 2, now()->year + 1)))
                    ->required()
                    ->live(),

                Select::make('department_id')
                    ->label(__('Departemen'))
                    ->options(function () {
                        $companyId = session('selected_company_id');
                        $query = Department::query();
                        if ($companyId) {
                            $query->where('company_id', $companyId);
                        } elseif (auth()->check()) {
                            $ids = auth()->user()->companies()->pluck('companies.id');
                            if ($ids->isNotEmpty()) $query->whereIn('company_id', $ids);
                        }
                        return $query->pluck('name', 'id');
                    })
                    ->placeholder(__('Semua Departemen'))
                    ->live(),
            ])
            ->columns(3)
            ->statePath('data');
    }

    protected function getViewData(): array
    {
        return $this->getRawData();
    }

    protected function getRawData(): array
    {
        $month = $this->data['month'] ?? now()->month;
        $year = $this->data['year'] ?? now()->year;
        $deptId = $this->data['department_id'] ?? null;
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            return [
                'records' => collect(),
                'company' => null,
                'error' => __('Silakan pilih perusahaan tertentu.')
            ];
        }

        $employeesQuery = Employee::where('company_id', $companyId)->where('is_active', true);
        if ($deptId) {
            $employeesQuery->where('department_id', $deptId);
        }
        $employees = $employeesQuery->get();

        $records = $employees->map(function ($employee) use ($month, $year) {
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get();

            return [
                'employee' => $employee,
                'present' => $attendances->where('status', 'present')->count(),
                'late' => $attendances->where('status', 'late')->count(),
                'absent' => $attendances->where('status', 'absent')->count(),
                'permit' => $attendances->where('status', 'permit')->count(),
                'leave' => $attendances->where('status', 'leave')->count(),
                'total_working_days' => $attendances->count(),
            ];
        });

        return [
            'records' => $records,
            'company' => Company::find($companyId),
            'month_name' => date("F", mktime(0, 0, 0, $month, 10)),
            'year' => $year,
            'department' => $deptId ? Department::find($deptId) : null,
        ];
    }

    public function downloadPdf()
    {
        $data = $this->getRawData();
        if (isset($data['error'])) return;

        $pdf = Pdf::loadView('filament.pages.reports.attendance-report-pdf', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Kehadiran_' . $data['month_name'] . '_' . $data['year'] . '.pdf');
    }
}
