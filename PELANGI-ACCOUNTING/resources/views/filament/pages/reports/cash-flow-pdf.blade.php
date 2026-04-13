<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Arus Kas</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-company-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #991b1b;
            margin-bottom: 5px;
        }

        .report-date {
            font-size: 12px;
            font-weight: bold;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 5px;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            text-align: left;
            font-size: 11px;
            border: 1px solid #1e3a8a;
        }

        th.amount,
        td.amount {
            text-align: right;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>
</head>

<body>
    <div class="report-header">
        <div class="report-company-name">{{ $company->name }}</div>
        <div class="report-title">Laporan Arus Kas (Metode Tidak Langsung)</div>
        <div class="report-date">
            Period {{ \Carbon\Carbon::parse($start_date)->isoFormat('MMMM YYYY') }} to {{
            \Carbon\Carbon::parse($end_date)->isoFormat('MMMM YYYY') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount" style="width: 150px;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Operating Activities -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Arus Kas dari Aktivitas Operasi</td>
            </tr>

            <!-- P&L Tree -->
            @foreach($plTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach

            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Laba(Rugi) Bersih Operasi</td>
                <td class="amount" style="font-weight: bold; border-top: 1px solid #9ca3af; color: #1f2937;">
                    @if($plTotal < 0) - {{ number_format(abs($plTotal), 0, ',' , '.' ) }} @else {{
                        number_format($plTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Non-Cash Adjustments: Depreciation / Amortisation Add-Back -->
            @if(count($nonCashTree ?? []) > 0)
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Penyesuaian Non-Kas (Penyusutan &amp; Amortisasi)</td>
            </tr>
            @foreach($nonCashTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Jumlah Penyesuaian Non-Kas</td>
                <td class="amount" style="font-weight: bold; border-top: 1px solid #9ca3af; color: #1f2937;">
                    @if(($nonCashTotal ?? 0) < 0) - {{ number_format(abs($nonCashTotal), 0, ',' , '.' ) }} @else {{
                        number_format($nonCashTotal ?? 0, 0, ',' , '.' ) }} @endif </td>
            </tr>
            @endif

            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Laba(Rugi) Operasi sebelum perubahan Modal Kerja</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">
                    @php $adjPlTotal = $plTotal + ($nonCashTotal ?? 0); @endphp
                    @if($adjPlTotal < 0) - {{ number_format(abs($adjPlTotal), 0, ',' , '.' ) }} @else {{
                        number_format($adjPlTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Operating Assets Change -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Berkurang(Bertambah) pada Operasi Aktiva</td>
            </tr>
            @foreach($opAssetsTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Jumlah Berkurang(Bertambah) pada Operasi Aktiva</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">
                    @if($opAssetsTotal < 0) - {{ number_format(abs($opAssetsTotal), 0, ',' , '.' ) }} @else {{
                        number_format($opAssetsTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Operating Liabilities Change -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Bertambah (berkurang) pada Operasi Kewajiban</td>
            </tr>
            @foreach($opLiabTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Jumlah Bertambah (berkurang) pada Operasi Kewajiban</td>
                <td class="amount" style="font-weight: bold; color: {{ $opLiabTotal < 0 ? '#dc2626' : '#1f2937' }};">
                    @if($opLiabTotal < 0) - {{ number_format(abs($opLiabTotal), 0, ',' , '.' ) }} @else {{
                        number_format($opLiabTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Net Operating Cash Flow -->
            <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                <td style="font-weight: bold; color: #1e3a8a;">Kas bersih (dipakai)/ dihasilkan oleh Aktivitas Operasi
                </td>
                <td class="amount" style="font-weight: bold; color: #1e3a8a;">
                    @if($operatingTotal < 0) - {{ number_format(abs($operatingTotal), 0, ',' , '.' ) }} @else {{
                        number_format($operatingTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Investing Activities -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Arus Kas dari Aktivitas Investasi</td>
            </tr>
            @foreach($invTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                <td style="font-weight: bold; color: #1e3a8a;">Kas bersih yg dihasilkan / (dipakai) oleh Aktivitas
                    Investasi</td>
                <td class="amount" style="font-weight: bold; color: #1e3a8a;">
                    @if($invTotal < 0) - {{ number_format(abs($invTotal), 0, ',' , '.' ) }} @else {{
                        number_format($invTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Financing Activities -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Arus Kas dari Aktivitas Pendanaan</td>
            </tr>
            @foreach($finTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                <td style="font-weight: bold; color: #1e3a8a;">Kas bersih yg dihasilkan dari / (dipakai) oleh Aktivitas
                    Pendanaan</td>
                <td class="amount" style="font-weight: bold; color: #1e3a8a;">
                    @if($finTotal < 0) - {{ number_format(abs($finTotal), 0, ',' , '.' ) }} @else {{
                        number_format($finTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Final Totals -->
            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #000; text-transform: uppercase;">Kas bersih dihasilkan oleh /
                    (dipakai) di Periode ini</td>
                <td class="amount" style="font-weight: bold; color: #000;">
                    @if($netCashFlow < 0) - {{ number_format(abs($netCashFlow), 0, ',' , '.' ) }} @else {{
                        number_format($netCashFlow, 0, ',' , '.' ) }} @endif </td>
            </tr>
            <tr style="background-color: white;">
                <td style="font-weight: bold; color: #374151;">Kas & Setara Kas pada Awal Periode</td>
                <td class="amount" style="font-weight: bold; color: #374151;">
                    @if($beginningCash < 0) - {{ number_format(abs($beginningCash), 0, ',' , '.' ) }} @else {{
                        number_format($beginningCash, 0, ',' , '.' ) }} @endif </td>
            </tr>
            <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                <td style="font-weight: bold; color: #1e3a8a; text-transform: uppercase;">Kas & Setara Kas pada Akhir
                    Periode</td>
                <td class="amount" style="font-weight: bold; color: #1e3a8a;">
                    @if($endingCash < 0) - {{ number_format(abs($endingCash), 0, ',' , '.' ) }} @else {{
                        number_format($endingCash, 0, ',' , '.' ) }} @endif </td>
            </tr>
        </tbody>
    </table>
</body>

</html>