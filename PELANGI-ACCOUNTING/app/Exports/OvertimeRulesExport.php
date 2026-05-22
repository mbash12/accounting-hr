<?php

namespace App\Exports;

use App\Models\OvertimeRule;
use App\Services\CompanyFilterService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class OvertimeRulesExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection(): Collection
    {
        $query = OvertimeRule::with('department');

        $query = CompanyFilterService::applyCompanyFilter($query);

        return $query->get()->map(function ($rule) {
            return [
                'name'                                => $rule->name,
                'department_code'                     => $rule->department?->code,
                'is_default'                          => $rule->is_default ? 'yes' : 'no',
                'base_hourly_rate_divisor'            => $rule->base_hourly_rate_divisor,
                'workday_first_hour_multiplier'       => $rule->workday_first_hour_multiplier,
                'workday_subsequent_hour_multiplier'  => $rule->workday_subsequent_hour_multiplier,
                'holiday_multiplier'                  => $rule->holiday_multiplier,
                'active_status'                       => $rule->is_active ? 'yes' : 'no',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Rule Name',
            'Department Code',
            'Default',
            'Hourly Rate Divisor',
            'First Hour Multiplier (Workday)',
            'Subsequent Hour Multiplier (Workday)',
            'Holiday Multiplier',
            'Active Status',
        ];
    }

    public function title(): string
    {
        return 'Overtime Rules Data';
    }
}
