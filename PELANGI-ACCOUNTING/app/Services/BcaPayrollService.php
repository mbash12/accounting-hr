<?php

namespace App\Services;

use App\Models\PayrollPeriod;
use App\Models\Payslip;
use App\Models\THRCalculation;
use Illuminate\Support\Collection;

class BcaPayrollService
{
    /**
     * Generate BCA Payroll CSV Content based on requested format:
     * Account Number,Transfer Amount,Employee Name,Transfer Date,Employee Number,Department
     */
    public function generateCsv(PayrollPeriod $period): string
    {
        $payslips = $period->payslips()->with(['employee', 'employee.department'])->get();
        
        $transferDate = $period->end_date ? $period->end_date->format('d/m/Y') : now()->format('d/m/Y');

        $rows = $payslips->map(function ($payslip) use ($transferDate) {
            $employee = $payslip->employee;

            return [
                $employee->bank_account_number ?? '',
                round($payslip->net_salary),
                strtoupper($employee->bank_account_holder ?? $employee->name),
                $transferDate,
                $employee->employee_id ?? '',
                $employee->department?->name ?? '',
            ];
        });

        return $this->buildCsv($rows);
    }

    /**
     * Generate BCA THR CSV — same format, Transfer Amount = amount - pph21 (net THR).
     */
    public function generateCsvForTHR(THRCalculation $thr): string
    {
        $items = $thr->items()->with(['employee', 'employee.department'])->get();

        $transferDate = $thr->payout_date ? $thr->payout_date->format('d/m/Y') : now()->format('d/m/Y');

        $rows = $items->map(function ($item) use ($transferDate) {
            $employee = $item->employee;
            $netAmount = round($item->amount - $item->pph21);

            return [
                $employee->bank_account_number ?? '',
                $netAmount,
                strtoupper($employee->bank_account_holder ?? $employee->name),
                $transferDate,
                $employee->employee_id ?? '',
                $employee->department?->name ?? '',
            ];
        });

        return $this->buildCsv($rows);
    }

    private function buildCsv(Collection $rows): string
    {
        $header = ['Account Number', 'Transfer Amount', 'Employee Name', 'Transfer Date', 'Employee Number', 'Department'];

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $header);
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
