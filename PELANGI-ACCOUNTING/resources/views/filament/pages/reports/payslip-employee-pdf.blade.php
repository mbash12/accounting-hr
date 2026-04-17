<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $period->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        @page {
            size: A4 portrait;
            margin: 15mm 12mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9.5px;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .page {
            width: auto;
            padding: 5mm 4mm;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* ── Header ── */
        .slip-header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
        }

        .slip-header-logo {
            display: table-cell;
            width: 60px;
            vertical-align: middle;
        }

        .slip-header-logo img {
            max-width: 55px;
            max-height: 45px;
        }

        .slip-header-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a8a;
        }

        .slip-title {
            font-size: 10.5px;
            font-weight: bold;
            color: #374151;
            margin-top: 2px;
        }

        .slip-period {
            font-size: 9px;
            color: #6b7280;
            margin-top: 1px;
        }

        .slip-number {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 8px;
            color: #6b7280;
            white-space: nowrap;
        }

        /* ── Employee Info ── */
        .employee-section {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            background-color: #f0f4ff;
            border: 1px solid #c7d2fe;
            border-radius: 4px;
            padding: 8px 10px;
        }

        .employee-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }

        .info-label {
            display: table-cell;
            width: 90px;
            color: #6b7280;
            font-size: 8.5px;
        }

        .info-sep {
            display: table-cell;
            width: 10px;
            color: #6b7280;
        }

        .info-value {
            display: table-cell;
            font-weight: bold;
            font-size: 9px;
            color: #111827;
        }

        /* ── Salary Table ── */
        .salary-section {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .salary-col-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 6px;
        }

        .salary-col-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 6px;
        }

        .salary-group-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 6px;
            margin-bottom: 0;
        }

        .salary-group-title.earn {
            background-color: #1e3a8a;
            color: white;
        }

        .salary-group-title.deduct {
            background-color: #7f1d1d;
            color: white;
        }

        .salary-items {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .salary-items tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .salary-items td {
            padding: 3px 6px;
            font-size: 8.5px;
            border-bottom: 1px solid #f3f4f6;
        }

        .salary-items td.amount {
            text-align: right;
            white-space: nowrap;
        }

        .salary-items tr.subtotal td {
            font-weight: bold;
            background-color: #e0e7ff;
            border-top: 1px solid #c7d2fe;
            font-size: 8.5px;
        }

        .salary-items tr.subtotal.deduct td {
            background-color: #fee2e2;
            border-top: 1px solid #fca5a5;
        }

        /* ── Summary Box ── */
        .summary-box {
            width: 100%;
            border: 2px solid #1e3a8a;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .summary-box table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .summary-box td {
            padding: 4px 10px;
            font-size: 9px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .summary-box td.label {
            width: 35%;
        }

        .summary-box td.value {
            width: 15%;
        }

        .summary-box tr:not(:last-child) {
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-box .label {
            color: #374151;
        }

        .summary-box .value {
            text-align: right;
            font-weight: bold;
            white-space: nowrap;
        }

        .summary-box .net-row td {
            background-color: #1e3a8a;
            color: white;
            font-size: 10.5px;
            font-weight: bold;
            padding: 6px 10px;
        }

        /* ── Footer ── */
        .slip-footer {
            display: table;
            width: 100%;
            margin-top: 12px;
        }

        .sign-col {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: bottom;
            font-size: 8.5px;
        }

        .sign-box {
            border-bottom: 1px solid #374151;
            height: 40px;
            margin-bottom: 4px;
        }

        .sign-label {
            color: #6b7280;
        }

        .note-text {
            font-size: 8px;
            color: #9ca3af;
            text-align: center;
            margin-top: 8px;
            border-top: 1px dashed #e5e7eb;
            padding-top: 4px;
        }
    </style>
</head>
<body>

@php
    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    function fmtRp($amount): string {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }
@endphp

@foreach($payslips as $payslip)
@php
    $employee  = $payslip->employee;
    $items     = $payslip->items;
    $allowances = $items->where('type', 'allowance');
    $deductions = $items->where('type', 'deduction');
    $bpjsEmp   = $payslip->bpjs_kesehatan_employee + $payslip->bpjs_ketenagakerjaan_employee;
    $bpjsPer   = $payslip->bpjs_kesehatan_employer + $payslip->bpjs_ketenagakerjaan_employer;
@endphp

<div class="page">

    {{-- Header --}}
    <div class="slip-header">
        <div class="slip-header-logo">
            @if(file_exists(public_path('logo.png')))
                <img src="{{ public_path('logo.png') }}">
            @endif
        </div>
        <div class="slip-header-info">
            <div class="company-name">{{ $company?->name }}</div>
            @if($company?->billing_address_line_1)
                <div class="slip-period" style="margin-top:2px;">{{ $company->billing_address_line_1 }}{{ $company->billing_city ? ', '.$company->billing_city : '' }}</div>
            @endif
            <div class="slip-title">SLIP GAJI KARYAWAN</div>
            <div class="slip-period">
                Periode: {{ $monthNames[$period->month] }} {{ $period->year }}
                @if($period->start_date && $period->end_date)
                    &nbsp;({{ $period->start_date->format('d/m/Y') }} – {{ $period->end_date->format('d/m/Y') }})
                @endif
            </div>
        </div>
        <div class="slip-number">
            No. Slip: <strong>{{ $payslip->number }}</strong><br>
            Tgl Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </div>
    </div>

    {{-- Employee Info --}}
    <div class="employee-section">
        <div class="employee-col">
            <div class="info-row">
                <div class="info-label">Nama Karyawan</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $employee->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">ID Karyawan</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $employee->employee_id ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Departemen</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $employee->department?->name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jabatan</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $employee->position ?? '-' }}</div>
            </div>
        </div>
        <div class="employee-col">
            <div class="info-row">
                <div class="info-label">NIK</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $employee->nik ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NPWP</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $employee->npwp ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">PTKP</div>
                <div class="info-sep">:</div>
                <div class="info-value">{{ $employee->ptkp_status ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Bank / Rekening</div>
                <div class="info-sep">:</div>
                <div class="info-value">
                    {{ $employee->bank_name ?? '' }}
                    @if($employee->bank_account_number) – {{ $employee->bank_account_number }} @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Salary Items --}}
    <div class="salary-section">
        {{-- Left: Pendapatan --}}
        <div class="salary-col-left">
            <div class="salary-group-title earn">Pendapatan</div>
            <table class="salary-items">
                <tr>
                    <td>Gaji Pokok</td>
                    <td class="amount">{{ fmtRp($payslip->basic_salary) }}</td>
                </tr>
                @foreach($allowances as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="amount">{{ fmtRp($item->amount) }}</td>
                </tr>
                @endforeach
                <tr class="subtotal">
                    <td>Total Pendapatan</td>
                    <td class="amount">{{ fmtRp($payslip->gross_salary) }}</td>
                </tr>
            </table>
        </div>

        {{-- Right: Potongan --}}
        <div class="salary-col-right">
            <div class="salary-group-title deduct">Potongan</div>
            <table class="salary-items">
                @foreach($deductions as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="amount">{{ fmtRp($item->amount) }}</td>
                </tr>
                @endforeach
                @if($payslip->pph21 > 0)
                <tr>
                    <td>PPh 21</td>
                    <td class="amount">{{ fmtRp($payslip->pph21) }}</td>
                </tr>
                @endif
                @if($bpjsEmp > 0)
                <tr>
                    <td>BPJS (Karyawan)</td>
                    <td class="amount">{{ fmtRp($bpjsEmp) }}</td>
                </tr>
                @endif
                <tr class="subtotal deduct">
                    <td>Total Potongan</td>
                    <td class="amount">{{ fmtRp($payslip->total_deduction + $payslip->pph21 + $bpjsEmp) }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary-box">
        <table>
            <tr>
                <td class="label">Gaji Pokok</td>
                <td class="value">{{ fmtRp($payslip->basic_salary) }}</td>
                <td class="label" style="padding-left:20px;">BPJS Kesehatan (Karyawan)</td>
                <td class="value">{{ fmtRp($payslip->bpjs_kesehatan_employee) }}</td>
            </tr>
            <tr>
                <td class="label">Total Tunjangan</td>
                <td class="value">{{ fmtRp($payslip->total_allowance) }}</td>
                <td class="label" style="padding-left:20px;">BPJS Ketenagakerjaan (Karyawan)</td>
                <td class="value">{{ fmtRp($payslip->bpjs_ketenagakerjaan_employee) }}</td>
            </tr>
            <tr>
                <td class="label">Gaji Bruto</td>
                <td class="value">{{ fmtRp($payslip->gross_salary) }}</td>
                <td class="label" style="padding-left:20px;">PPh 21</td>
                <td class="value">{{ fmtRp($payslip->pph21) }}</td>
            </tr>
            <tr>
                <td class="label">Total Potongan Lain</td>
                <td class="value">{{ fmtRp($payslip->total_deduction) }}</td>
                <td class="label" style="padding-left:20px;">BPJS (Ditanggung Perusahaan)</td>
                <td class="value" style="color:#6b7280;">{{ fmtRp($bpjsPer) }}</td>
            </tr>
            <tr class="net-row">
                <td colspan="2">GAJI BERSIH (TAKE HOME PAY)</td>
                <td colspan="2" style="text-align:right;">{{ fmtRp($payslip->net_salary) }}</td>
            </tr>
        </table>
    </div>

    {{-- Footer / Signature --}}
    <div class="slip-footer">
        <div class="sign-col">
            <div class="sign-box"></div>
            <div class="sign-label">Karyawan</div>
            <div style="margin-top:2px;font-weight:bold;">{{ $employee->name }}</div>
        </div>
        <div class="sign-col"></div>
        <div class="sign-col">
            <div class="sign-box"></div>
            <div class="sign-label">Disetujui oleh</div>
            <div style="margin-top:2px;font-weight:bold;">{{ $company?->name }}</div>
        </div>
    </div>

    <div class="note-text">
        Dokumen ini dicetak secara otomatis oleh sistem. Berlaku sebagai bukti sah pembayaran gaji.
    </div>

</div>
@endforeach

</body>
</html>
