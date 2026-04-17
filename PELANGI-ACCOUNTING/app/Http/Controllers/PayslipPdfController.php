<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PayrollPeriod;
use App\Models\Payslip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PayslipPdfController extends Controller
{
    /**
     * Download all payslips for a payroll period as a single multi-page PDF.
     * Each employee gets one page.
     */
    public function downloadByPeriod(int $periodId): Response
    {
        $period = PayrollPeriod::findOrFail($periodId);

        $this->authorizeCompany($period->company_id);

        $payslips = Payslip::with(['employee.department', 'items'])
            ->where('payroll_period_id', $periodId)
            ->orderBy('id')
            ->get();

        $company = Company::find($period->company_id);

        $pdf = Pdf::loadView('filament.pages.reports.payslip-employee-pdf', [
            'payslips' => $payslips,
            'period'   => $period,
            'company'  => $company,
        ])->setPaper('a4', 'portrait');

        $filename = 'Slip_Gaji_' . str_replace(' ', '_', $period->name) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download a single employee's payslip PDF.
     */
    public function downloadSingle(int $payslipId): Response
    {
        $payslip = Payslip::with(['employee.department', 'items', 'payrollPeriod'])
            ->findOrFail($payslipId);

        $this->authorizeCompany($payslip->company_id);

        $period  = $payslip->payrollPeriod;
        $company = Company::find($payslip->company_id);

        $pdf = Pdf::loadView('filament.pages.reports.payslip-employee-pdf', [
            'payslips' => collect([$payslip]),
            'period'   => $period,
            'company'  => $company,
        ])->setPaper('a4', 'portrait');

        $filename = 'Slip_Gaji_' . $payslip->number . '_' . $payslip->employee->name . '.pdf';

        return $pdf->download($filename);
    }

    private function authorizeCompany(?int $companyId): void
    {
        if (!$companyId) {
            abort(403);
        }

        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        $allowedIds = $user->companies()->pluck('companies.id')->toArray();

        if (!in_array($companyId, $allowedIds)) {
            abort(403, 'Access denied to this company\'s data.');
        }
    }
}
