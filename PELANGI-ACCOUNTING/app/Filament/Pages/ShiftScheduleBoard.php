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
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
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
    public ?array $data = [];
    public array $departmentIds = [];
    public array $shiftTypeIds = [];

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
        $this->form->fill([
            'month' => $this->month,
            'year' => $this->year,
            'departmentIds' => [],
            'shiftTypeIds' => [],
        ]);
        $this->refreshGrid();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('month')
                    ->label('Bulan')
                    ->options(collect(range(1, 12))->mapWithKeys(fn (int $month) => [
                        $month => CarbonImmutable::create(null, $month, 1)->format('F'),
                    ])->all())
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->applyFormFilters()),
                Select::make('year')
                    ->label('Tahun')
                    ->options(array_combine(
                        range(now()->year - 2, now()->year + 1),
                        range(now()->year - 2, now()->year + 1),
                    ))
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->applyFormFilters()),
                Select::make('departmentIds')
                    ->label('Department')
                    ->options(fn () => $this->departments)
                    ->multiple()
                    ->searchable()
                    ->placeholder('All Departments')
                    ->live()
                    ->afterStateUpdated(fn () => $this->applyFormFilters()),
                Select::make('shiftTypeIds')
                    ->label('Shift Type')
                    ->options(fn () => $this->shiftTypes)
                    ->multiple()
                    ->searchable()
                    ->placeholder('All Shift Types')
                    ->live()
                    ->afterStateUpdated(fn () => $this->applyFormFilters()),
            ])
            ->columns(4)
            ->statePath('data');
    }

    public function applyFormFilters(): void
    {
        $this->month = (int) ($this->data['month'] ?? $this->month);
        $this->year = (int) ($this->data['year'] ?? $this->year);
        $this->departmentIds = array_values(array_map('intval', $this->data['departmentIds'] ?? []));
        $this->shiftTypeIds = array_values(array_map('intval', $this->data['shiftTypeIds'] ?? []));
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
            ->buildMonthGrid($this->year, $this->month, $companyId, $this->departmentIds, $this->shiftTypeIds);

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
            $this->departmentIds = [];
            $this->shiftTypeIds = [];
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

        $this->departmentIds = array_values(array_intersect(
            array_map('intval', $this->departmentIds),
            array_map('intval', array_keys($this->departments)),
        ));
        $this->shiftTypeIds = array_values(array_intersect(
            array_map('intval', $this->shiftTypeIds),
            array_map('intval', array_keys($this->shiftTypes)),
        ));
        $this->data['month'] = $this->month;
        $this->data['year'] = $this->year;
        $this->data['departmentIds'] = $this->departmentIds;
        $this->data['shiftTypeIds'] = $this->shiftTypeIds;
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
            ->buildMonthGrid($this->year, $this->month, $companyId, $this->departmentIds, $this->shiftTypeIds);

        return Excel::download(
            new ShiftScheduleTemplateExport(
                year: $this->year,
                month: $this->month,
                departmentIds: $this->departmentIds,
                companyId: $companyId,
                prefill: true,
                shiftTypeIds: $this->shiftTypeIds,
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
