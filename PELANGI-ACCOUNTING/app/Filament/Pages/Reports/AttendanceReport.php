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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Validator;
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
        return __('HR & Payroll Reports');
    }

    protected static ?int $navigationSort = 11;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->endOfMonth()->format('Y-m-d'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->label(__('Tanggal Mulai'))
                    ->required()
                    ->live(),

                DatePicker::make('end_date')
                    ->label(__('Tanggal Akhir'))
                    ->required()
                    ->afterOrEqual('start_date')
                    ->live(),

                Select::make('department_id')
                    ->label(__('Department'))
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
                    ->placeholder(__('All Departments'))
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
        $startDate = $this->data['start_date'] ?? null;
        $endDate = $this->data['end_date'] ?? null;
        $deptId = $this->data['department_id'] ?? null;
        $companyId = session('selected_company_id');

        if (!$companyId || $companyId === 'all') {
            return [
                'records' => collect(),
                'company' => null,
                'error' => __('Please select a specific company.')
            ];
        }

        $validator = Validator::make([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ], [
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ], [], [
            'start_date' => __('Tanggal Mulai'),
            'end_date' => __('Tanggal Akhir'),
        ]);

        if ($validator->fails()) {
            return [
                'records' => collect(),
                'company' => null,
                'error' => $validator->errors()->first(),
            ];
        }

        $employeesQuery = Employee::where('company_id', $companyId)->where('is_active', true);
        if ($deptId) {
            $employeesQuery->where('department_id', $deptId);
        }
        $employees = $employeesQuery->get();

        $records = $employees->map(function ($employee) use ($startDate, $endDate) {
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate, $endDate])
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
            'start_date' => $startDate,
            'end_date' => $endDate,
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
        }, 'Attendance_Report_' . $data['start_date'] . '_' . $data['end_date'] . '.pdf');
    }
}
