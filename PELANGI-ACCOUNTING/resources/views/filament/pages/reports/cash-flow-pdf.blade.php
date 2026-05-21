<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cash Flow Statement</title>
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
        <div class="report-title">Cash Flow Statement (Indirect Method)</div>
        <div class="report-date">
            Period {{ \Carbon\Carbon::parse($start_date)->isoFormat('MMMM YYYY') }} to {{
            \Carbon\Carbon::parse($end_date)->isoFormat('MMMM YYYY') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount" style="width: 150px;">Balance</th>
            </tr>
        </thead>
        <tbody>
            <!-- Operating Activities -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Cash Flow from Operating Activities</td>
            </tr>

            <!-- P&L Tree -->
            @foreach($plTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach

            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Net Operating Profit (Loss)</td>
                <td class="amount" style="font-weight: bold; border-top: 1px solid #9ca3af; color: #1f2937;">
                    @if($plTotal < 0) - {{ number_format(abs($plTotal), 0, ',' , '.' ) }} @else {{
                        number_format($plTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Non-Cash Adjustments: Depreciation / Amortisation Add-Back -->
            @if(count($nonCashTree ?? []) > 0)
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Non-Cash Adjustments (Depreciation &amp; Amortization)</td>
            </tr>
            @foreach($nonCashTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Total Non-Cash Adjustments</td>
                <td class="amount" style="font-weight: bold; border-top: 1px solid #9ca3af; color: #1f2937;">
                    @if(($nonCashTotal ?? 0) < 0) - {{ number_format(abs($nonCashTotal), 0, ',' , '.' ) }} @else {{
                        number_format($nonCashTotal ?? 0, 0, ',' , '.' ) }} @endif </td>
            </tr>
            @endif

            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Operating Profit (Loss) Before Working Capital Changes</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">
                    @php $adjPlTotal = $plTotal + ($nonCashTotal ?? 0); @endphp
                    @if($adjPlTotal < 0) - {{ number_format(abs($adjPlTotal), 0, ',' , '.' ) }} @else {{
                        number_format($adjPlTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Operating Assets Change -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Decrease (Increase) in Operating Assets</td>
            </tr>
            @foreach($opAssetsTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Total Decrease (Increase) in Operating Assets</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">
                    @if($opAssetsTotal < 0) - {{ number_format(abs($opAssetsTotal), 0, ',' , '.' ) }} @else {{
                        number_format($opAssetsTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Operating Liabilities Change -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Increase (Decrease) in Operating Liabilities</td>
            </tr>
            @foreach($opLiabTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #1f2937;">Total Increase (Decrease) in Operating Liabilities</td>
                <td class="amount" style="font-weight: bold; color: {{ $opLiabTotal < 0 ? '#dc2626' : '#1f2937' }};">
                    @if($opLiabTotal < 0) - {{ number_format(abs($opLiabTotal), 0, ',' , '.' ) }} @else {{
                        number_format($opLiabTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Net Operating Cash Flow -->
            <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                <td style="font-weight: bold; color: #1e3a8a;">Net cash (used)/generated by Operating Activities</td>
                <td class="amount" style="font-weight: bold; color: #1e3a8a;">
                    @if($operatingTotal < 0) - {{ number_format(abs($operatingTotal), 0, ',' , '.' ) }} @else {{
                        number_format($operatingTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Investing Activities -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Cash Flow from Investing Activities</td>
            </tr>
            @foreach($invTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                <td style="font-weight: bold; color: #1e3a8a;">Net cash generated / (used) by Investing Activities</td>
                <td class="amount" style="font-weight: bold; color: #1e3a8a;">
                    @if($invTotal < 0) - {{ number_format(abs($invTotal), 0, ',' , '.' ) }} @else {{
                        number_format($invTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Financing Activities -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: #f9fafb; text-transform: uppercase;">
                    Cash Flow from Financing Activities</td>
            </tr>
            @foreach($finTree as $node)
            @include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                <td style="font-weight: bold; color: #1e3a8a;">Net cash generated from / (used) by Financing Activities</td>
                <td class="amount" style="font-weight: bold; color: #1e3a8a;">
                    @if($finTotal < 0) - {{ number_format(abs($finTotal), 0, ',' , '.' ) }} @else {{
                        number_format($finTotal, 0, ',' , '.' ) }} @endif </td>
            </tr>

            <!-- Final Totals -->
            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #000; text-transform: uppercase;">Net Cash Generated / (Used) in This Period</td>
                <td class="amount" style="font-weight: bold; color: #000;">
                    @if($netCashFlow < 0) - {{ number_format(abs($netCashFlow), 0, ',' , '.' ) }} @else {{
                        number_format($netCashFlow, 0, ',' , '.' ) }} @endif </td>
            </tr>
            <tr style="background-color: white;">
                <td style="font-weight: bold; color: #374151;">Cash & Cash Equivalents at Beginning of Period</td>
                <td class="amount" style="font-weight: bold; color: #374151;">
                    @if($beginningCash < 0) - {{ number_format(abs($beginningCash), 0, ',' , '.' ) }} @else {{
                        number_format($beginningCash, 0, ',' , '.' ) }} @endif </td>
            </tr>
            <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                <td style="font-weight: bold; color: #1e3a8a; text-transform: uppercase;">Cash & Cash Equivalents at End of Period</td>
                <td class="amount" style="font-weight: bold; color: #1e3a8a;">
                    @if($endingCash < 0) - {{ number_format(abs($endingCash), 0, ',' , '.' ) }} @else {{
                        number_format($endingCash, 0, ',' , '.' ) }} @endif </td>
            </tr>
        </tbody>
    </table>
</body>

</html>