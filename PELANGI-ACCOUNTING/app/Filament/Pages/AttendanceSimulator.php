<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Permit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use UnitEnum;
use BackedEnum;
use Carbon\Carbon;

class AttendanceSimulator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return __('HR & Payroll');
    }

    public static function getNavigationLabel(): string
    {
        return __('Attendance Simulator');
    }

    protected static ?int $navigationSort = 9;

    public function getTitle(): string
    {
        return __('Attendance & Permit Simulator');
    }

    protected string $view = 'filament.pages.attendance-simulator';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label(__('Select Employee for Simulation'))
                    ->options(function () {
                        $companyId = session('selected_company_id');
                        $query = Employee::where('is_active', true);
                        if ($companyId) {
                            $query->where('company_id', $companyId);
                        } elseif (auth()->check()) {
                            $ids = auth()->user()->companies()->pluck('companies.id');
                            if ($ids->isNotEmpty()) $query->whereIn('company_id', $ids);
                        }
                        return $query->pluck('name', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->live(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('checkIn')
                ->label(__('Check In'))
                ->color('success')
                ->icon('heroicon-o-arrow-right-start-on-rectangle')
                ->requiresConfirmation()
                ->hidden(fn () => empty($this->data['employee_id']))
                ->action(fn () => $this->performCheckIn()),
            
            Action::make('checkOut')
                ->label(__('Check Out'))
                ->color('warning')
                ->icon('heroicon-o-arrow-left-start-on-rectangle')
                ->requiresConfirmation()
                ->hidden(fn () => empty($this->data['employee_id']))
                ->action(fn () => $this->performCheckOut()),

            Action::make('requestPermit')
                ->label(__('Submit Permit/Leave'))
                ->color('info')
                ->icon('heroicon-o-document-plus')
                ->form([
                    Select::make('type')
                        ->label(__('Type'))
                        ->options([
                            'sick' => __('Sick'),
                            'annual_leave' => __('Annual Leave'),
                            'unpaid_leave' => __('Unpaid Leave'),
                            'other_permit' => __('Other Permit'),
                        ])->required(),
                    DatePicker::make('start_date')->label(__('Start Date'))->required(),
                    DatePicker::make('end_date')->label(__('End Date'))->required(),
                    Textarea::make('reason')->label(__('Reason')),
                    FileUpload::make('attachment_path')->label(__('Attachment'))->directory('permits'),
                ])
                ->hidden(fn () => empty($this->data['employee_id']))
                ->action(fn (array $data) => $this->performPermitRequest($data)),
        ];
    }

    private function performCheckIn()
    {
        $employeeId = $this->data['employee_id'];
        $employee = Employee::find($employeeId);
        $today = now()->toDateString();

        $existing = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->first();

        if ($existing) {
            Notification::make()->title(__('Already checked in today'))->danger()->send();
            return;
        }

        $now = now();
        $lateMinutes = 0;
        $status = 'present';

        if ($employee->department && $employee->department->work_start_time) {
            $startTime = Carbon::createFromFormat('H:i:s', $employee->department->work_start_time)
                ->setDate($now->year, $now->month, $now->day);
            
            if ($now->greaterThan($startTime)) {
                $lateMinutes = $now->diffInMinutes($startTime);
                $status = 'late';
            }
        }

        Attendance::create([
            'employee_id' => $employeeId,
            'date' => $today,
            'check_in' => $now,
            'late_minutes' => $lateMinutes,
            'status' => $status,
            'company_id' => $employee->company_id,
        ]);

        Notification::make()
            ->title(__('Check in successful'))
            ->body($lateMinutes > 0 ? __('Late :minutes minutes', ['minutes' => $lateMinutes]) : null)
            ->success()
            ->send();
    }

    private function performCheckOut()
    {
        $employeeId = $this->data['employee_id'];
        $employee = Employee::with('department')->find($employeeId);
        $today = now()->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            Notification::make()->title(__('Check in data not found for today'))->danger()->send();
            return;
        }

        if ($attendance->check_out) {
            Notification::make()->title(__('Already checked out today'))->danger()->send();
            return;
        }

        $now = now();
        $earlyMinutes = 0;

        if ($employee->department && $employee->department->work_end_time) {
            $endTime = Carbon::createFromFormat('H:i:s', $employee->department->work_end_time)
                ->setDate($now->year, $now->month, $now->day);
            
            if ($now->lessThan($endTime)) {
                $earlyMinutes = $now->diffInMinutes($endTime);
            }
        }

        $attendance->update([
            'check_out' => $now,
            'early_departure_minutes' => $earlyMinutes,
        ]);

        Notification::make()
            ->title(__('Check out successful'))
            ->body($earlyMinutes > 0 ? __('Left early by :minutes minutes', ['minutes' => $earlyMinutes]) : null)
            ->success()
            ->send();
    }

    private function performPermitRequest(array $data)
    {
        $employeeId = $this->data['employee_id'];
        
        Permit::create([
            'employee_id' => $employeeId,
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
            'attachment_path' => $data['attachment_path'] ?? null,
            'status' => 'pending',
            'company_id' => Employee::find($employeeId)->company_id,
        ]);

        Notification::make()->title(__('Permit submission sent successfully'))->success()->send();
    }
}
