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
    public function collection(): Collection
    {
        $query = Attendance::with('employee');

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->orderBy('date', 'desc')->get()->map(function ($attendance) {
            return [
                'employee_id'              => $attendance->employee?->employee_id,
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
}
