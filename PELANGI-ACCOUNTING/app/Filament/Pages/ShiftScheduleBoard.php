<?php

namespace App\Filament\Pages;

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

    public array $grid = [];
    public array $holidays = [];
    public array $legend = [];
    public array $employees = [];
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
    public function refreshGrid(): void
    {
        $data = app(ShiftScheduleService::class)
            ->buildMonthGrid($this->year, $this->month, $this->companyId(), null);

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
