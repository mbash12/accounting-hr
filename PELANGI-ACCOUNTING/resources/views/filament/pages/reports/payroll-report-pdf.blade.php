<!DOCTYPE html>
<html>
<head>
    <title>Payroll Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 14px; font-weight: bold; text-transform: uppercase; }
        .report-title { font-size: 16px; font-weight: bold; color: #1e3a8a; margin: 5px 0; }
        .report-meta { font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #1e3a8a; color: white; padding: 5px; border: 1px solid #ccc; text-align: left; }
        td { padding: 5px; border: 1px solid #ccc; }
        .num { text-align: right; }
        .total-row { font-weight: bold; background-color: #eee; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company?->name }}</div>
        <div class="report-title">LAPORAN RINGKASAN PAYROLL</div>
        <div class="report-meta">
            Periode: {{ $period?->name }} 
            @if($department) | Department: {{ $department->name }} @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th class="num">Gaji Pokok</th>
                <th class="num">Tunjangan</th>
                <th class="num">Potongan</th>
                <th class="num">Gaji Bruto</th>
                <th class="num">PPh21</th>
                <th class="num">BPJS (Kar)</th>
                <th class="num">BPJS (Per)</th>
                <th class="num">Gaji Bersih</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payslips as $payslip)
                <tr>
                    <td>{{ $payslip->employee->name }}</td>
                    <td>{{ $payslip->employee->department?->name }}</td>
                    <td class="num">{{ number_format($payslip->basic_salary, 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($payslip->total_allowance, 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($payslip->total_deduction, 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($payslip->gross_salary, 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($payslip->pph21, 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($payslip->bpjs_kesehatan_employee + $payslip->bpjs_ketenagakerjaan_employee, 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($payslip->bpjs_kesehatan_employer + $payslip->bpjs_ketenagakerjaan_employer, 0, ',', '.') }}</td>
                    <td class="num" style="font-weight: bold;">{{ number_format($payslip->net_salary, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" style="text-align: center;">TOTAL</td>
                <td class="num">{{ number_format($totals['basic_salary'], 0, ',', '.') }}</td>
                <td class="num">{{ number_format($totals['allowance'], 0, ',', '.') }}</td>
                <td class="num">{{ number_format($totals['deduction'], 0, ',', '.') }}</td>
                <td class="num">{{ number_format($totals['gross'], 0, ',', '.') }}</td>
                <td class="num">{{ number_format($totals['pph21'], 0, ',', '.') }}</td>
                <td class="num">{{ number_format($totals['bpjs_employee'], 0, ',', '.') }}</td>
                <td class="num">{{ number_format($totals['bpjs_employer'], 0, ',', '.') }}</td>
                <td class="num">{{ number_format($totals['net'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
