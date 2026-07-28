<?php

namespace App\Filament\Pages;

use App\Exports\ShiftScheduleTemplateExport;
use App\Models\Department;
use App\Models\ShiftType;
use App\Services\ShiftScheduleService;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use BackedEnum;
use Maatwebsite\Excel\Facades\Excel;

class ShiftScheduleBoard extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected string $view = 'filament.pages.shift-schedule-board';

    protected static BackedEnum|string|null $navigationIcon = null;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationSort(): ?int
    {
        return 1; // put it near the top of the HR & Payroll group
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->can('View:ShiftScheduleBoard');
    }

    #[Url]
    public int $year = 0;

    #[Url]
    public int $month = 0;
    public ?int $departmentId = null;
    public ?int $shiftTypeId = null;

    public array $grid = [];
    public array $holidays = [];
    public array $legend = [];
    public array $employees = [];
    public array $departments = [];
    public array $shiftTypes = [];
    public int $days_in_month = 0;
    public int $first_dow = 1;
    public string $month_name = '';

    public function mount(): void
    {
        $this->year  = $this->year  ?: now()->year;
        $this->month = $this->month ?: now()->month;
        $this->refreshGrid();
    }

    #[On('shift-schedule-uploaded')]
    #[On('company-changed')]
    public function refreshGrid(): void
    {
        $companyId = $this->companyId();
        $this->loadFilterOptions($companyId);

        if (!$companyId) {
            $this->resetBoardData();
            return;
        }

        $data = app(ShiftScheduleService::class)
            ->buildMonthGrid($this->year, $this->month, $companyId, $this->departmentId, $this->shiftTypeId);

        $this->year         = $data['year'];
        $this->month        = $data['month'];
        $this->month_name   = $data['month_name'];
        $this->days_in_month= $data['days_in_month'];
        $this->first_dow    = $data['first_dow'];
        $this->grid         = $data['grid'];
        $this->holidays     = $data['holidays'];
        $this->legend       = $data['legend']->map(fn ($t) => [
            'code'       => $t->code,
            'name'       => $t->name,
            'color'      => $t->color,
            'text_color' => $t->text_color,
            'is_off'     => (bool) $t->is_off,
            'start_time' => $t->start_time,
            'end_time'   => $t->end_time,
        ])->all();
        $this->employees    = collect($data['employees'])->map(fn ($e) => [
            'id'          => $e->id,
            'employee_id' => $e->employee_id,
            'name'        => $e->name,
            'department'  => $e->department?->name,
            'position'    => $e->position,
        ])->all();
    }

    protected function loadFilterOptions(?int $companyId): void
    {
        if (!$companyId) {
            $this->departments = [];
            $this->shiftTypes = [];
            $this->departmentId = null;
            $this->shiftTypeId = null;
            return;
        }

        $this->departments = Department::query()
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $this->shiftTypes = ShiftType::query()
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get(['id', 'code', 'name'])
            ->mapWithKeys(fn (ShiftType $type) => [$type->id => $type->code . ' - ' . $type->name])
            ->toArray();

        if ($this->departmentId !== null && !array_key_exists($this->departmentId, $this->departments)) {
            $this->departmentId = null;
        }
        if ($this->shiftTypeId !== null && !array_key_exists($this->shiftTypeId, $this->shiftTypes)) {
            $this->shiftTypeId = null;
        }
    }

    protected function resetBoardData(): void
    {
        $this->grid = [];
        $this->holidays = [];
        $this->legend = [];
        $this->employees = [];
        $this->days_in_month = CarbonImmutable::create($this->year, $this->month, 1)->daysInMonth;
        $this->first_dow = CarbonImmutable::create($this->year, $this->month, 1)->dayOfWeekIso;
        $this->month_name = CarbonImmutable::create($this->year, $this->month, 1)->format('F');
    }

    protected function companyId(): ?int
    {
        $selected = session('selected_company_id');
        if ($selected === 'all' || !$selected) {
            return null;
        }
        return (int) $selected;
    }

    public function previousMonth(): void
    {
        $d = CarbonImmutable::create($this->year, $this->month, 1)->subMonth();
        $this->year  = (int) $d->year;
        $this->month = (int) $d->month;
        $this->refreshGrid();
    }

    public function nextMonth(): void
    {
        $d = CarbonImmutable::create($this->year, $this->month, 1)->addMonth();
        $this->year  = (int) $d->year;
        $this->month = (int) $d->month;
        $this->refreshGrid();
    }

    public function exportAction(): Action
    {
        return Action::make('export')
            ->label('Export')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(fn ($livewire) => $livewire->exportSchedule());
    }

    public function exportSchedule()
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return;
        }

        $gridData = app(ShiftScheduleService::class)
            ->buildMonthGrid($this->year, $this->month, $companyId, $this->departmentId, $this->shiftTypeId);

        return Excel::download(
            new ShiftScheduleTemplateExport(
                year: $this->year,
                month: $this->month,
                departmentId: $this->departmentId,
                companyId: $companyId,
                prefill: true,
                shiftTypeId: $this->shiftTypeId,
                employeeIds: collect($gridData['employees'])->pluck('id')->all(),
            ),
            'shift-schedule-' . CarbonImmutable::create($this->year, $this->month, 1)->format('Y-m') . '.xlsx'
        );
    }

    public function uploadAction(): Action
    {
        return \App\Filament\Actions\ImportShiftScheduleAction::make()
            ->authorize(fn () => auth()->check() && (auth()->user()->can('Update:ShiftSchedule') || auth()->user()->can('Update:ShiftType')));
    }

    public function clearScheduleAction(): Action
    {
        return \App\Filament\Actions\ClearShiftScheduleAction::make();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
