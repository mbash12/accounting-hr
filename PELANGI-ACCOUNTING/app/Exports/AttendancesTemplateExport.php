<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendancesTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            ['EMP-001', '2025-04-01', '08:00:00', '17:00:00', 0, 0, 'present', '', 'On time', 'Normal checkout'],
            ['EMP-001', '2025-04-02', '08:15:00', '17:00:00', 15, 0, 'late', 'Bus delay', 'Bus delay', ''],
            ['EMP-002', '2025-04-01', '', '', 0, 0, 'absent', 'Absent', '', ''],
        ];
    }

    public function headings(): array
    {
        return [
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
        ];
    }

    public function title(): string
    {
        return 'Attendance Import Template';
    }
}
