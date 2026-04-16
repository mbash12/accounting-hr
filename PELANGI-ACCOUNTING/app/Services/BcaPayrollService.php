<?php

namespace App\Services;

use App\Models\PayrollPeriod;
use App\Models\Payslip;
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
        
        $csvData = [];
        
        // Header
        $csvData[] = [
            'Account Number',
            'Transfer Amount',
            'Employee Name',
            'Transfer Date',
            'Employee Number',
            'Department'
        ];
        
        $transferDate = $period->end_date ? $period->end_date->format('d/m/Y') : now()->format('d/m/Y');

        foreach ($payslips as $payslip) {
            $employee = $payslip->employee;
            
            $csvData[] = [
                $employee->bank_account_number ?? '',
                round($payslip->net_salary),
                strtoupper($employee->bank_account_holder ?? $employee->name),
                $transferDate,
                $employee->employee_id ?? '',
                $employee->department?->name ?? ''
            ];
        }
        
        $output = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);
        
        return $csvContent;
    }
}
