<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendancesExport implements FromCollection, WithHeadings, WithTitle
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $query = Attendance::query()
            ->select([
                'employee_id',
                'date',
                'check_in',
                'check_out',
                'late_minutes',
                'early_departure_minutes',
                'status',
                'notes',
                'notes_in',
                'notes_out',
                'company_id',
            ])
            ->with([
                'employee:id,employee_id,name,department_id',
                'employee.department:id,name',
            ]);

        $query = CompanyFilterService::applyCompanyFilter($query);

        $this->applyFilters($query);

        return $query->orderBy('date', 'desc')->get()->map(function ($attendance) {
            return [
                'employee_id'              => $attendance->employee?->employee_id,
                'employee_name'            => $attendance->employee?->name,
                'department'               => $attendance->employee?->department?->name,
                'date'                     => $attendance->date?->format('Y-m-d'),
                'check_in'                 => $attendance->check_in?->format('H:i:s'),
                'check_out'                => $attendance->check_out?->format('H:i:s'),
                'late_minutes'             => $attendance->late_minutes,
                'early_departure_minutes'  => $attendance->early_departure_minutes,
                'status'                   => $attendance->status,
                'notes'                    => $attendance->notes,
                'notes_in'                 => $attendance->notes_in,
                'notes_out'                => $attendance->notes_out,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Employee Name',
            'Department',
            'Date',
            'Check In',
            'Check Out',
            'Late (Minutes)',
            'Early Departure (Minutes)',
            'Status',
            'Notes',
            'Check In Notes',
            'Check Out Notes',
        ];
    }

    public function title(): string
    {
        return 'Attendance Data';
    }

    protected function applyFilters($query): void
    {
        // Date range filter
        if (!empty($this->filters['date_range']['from'])) {
            $query->whereDate('date', '>=', $this->filters['date_range']['from']);
        }
        if (!empty($this->filters['date_range']['to'])) {
            $query->whereDate('date', '<=', $this->filters['date_range']['to']);
        }

        // Employee filter
        if (!empty($this->filters['employee_id']['value'])) {
            $query->where('employee_id', $this->filters['employee_id']['value']);
        }

        // Department filter
        if (!empty($this->filters['department_id']['value'])) {
            $query->whereHas('employee', function ($q) {
                $q->where('department_id', $this->filters['department_id']['value']);
            });
        }

        // Clock source filter
        if (!empty($this->filters['clock_source']['value'])) {
            $query->whereHas('clocks', function ($q) {
                $q->where('source', $this->filters['clock_source']['value']);
            });
        }
    }
}
