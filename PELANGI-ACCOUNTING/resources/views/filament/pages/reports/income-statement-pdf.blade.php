<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Income Statement</title>
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
        <div class="report-title">Income Statement</div>
        <div class="report-date">
            Period {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} to {{
            \Carbon\Carbon::parse($end_date)->format('d M Y') }}
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
            <!-- 1. PENDAPATAN OPERASIONAL -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: white; text-transform: uppercase;">
                    Operating Revenue</td>
            </tr>
            @php
            $operatingRevenues = $operatingRevenues ?? collect();
            $opRevNodes = ($operatingRevenues->count() === 1 && $operatingRevenues->first()->is_header) ?
            $operatingRevenues->first()->children : $operatingRevenues;
            @endphp
            @foreach($opRevNodes as $node)
            @include('filament.pages.reports.partials.account-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937; padding-left: 10px;">Total Operating Revenue</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">{{ number_format($totalOperatingRevenue ??
                    0, 2, ',', '.') }}</td>
            </tr>

            <!-- 2. HARGA POKOK PENJUALAN -->
            @if(($costOfGoodsSold ?? collect())->count() > 0)
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: white; text-transform: uppercase;">
                    Cost of Goods Sold</td>
            </tr>
            @php
            $cogsNodes = ($costOfGoodsSold->count() === 1 && $costOfGoodsSold->first()->is_header) ?
            $costOfGoodsSold->first()->children : $costOfGoodsSold;
            @endphp
            @foreach($cogsNodes as $node)
            @include('filament.pages.reports.partials.account-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937; padding-left: 10px;">Total Cost of Goods Sold</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">{{ number_format($totalCogs ?? 0, 2, ',',
                    '.') }}</td>
            </tr>
            @endif

            <!-- LABA KOTOR -->
            <tr style="background-color: #f3f4f6;">
                <td
                    style="padding-left: 10px; font-weight: bold; color: #111827; font-size: 12px; padding-top: 10px; padding-bottom: 10px;">
                    Gross Profit</td>
                <td class="amount"
                    style="font-weight: bold; color: #111827; font-size: 12px; border-top: 2px solid #111827; padding-top: 10px; padding-bottom: 10px;">
                    {{ number_format($grossProfit ?? 0, 2, ',', '.') }}</td>
            </tr>

            <!-- 3. BEBAN OPERASIONAL -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: white; text-transform: uppercase;">
                    Operating Expenses</td>
            </tr>
            @php
            $operatingExpenses = $operatingExpenses ?? collect();
            $opExpNodes = ($operatingExpenses->count() === 1 && $operatingExpenses->first()->is_header) ?
            $operatingExpenses->first()->children : $operatingExpenses;
            @endphp
            @foreach($opExpNodes as $node)
            @include('filament.pages.reports.partials.account-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937; padding-left: 10px;">Total Operating Expenses</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">{{ number_format($totalOperatingExpense ??
                    0, 2, ',', '.') }}</td>
            </tr>

            <!-- LABA OPERASIONAL -->
            <tr style="background-color: #f3f4f6;">
                <td
                    style="padding-left: 10px; font-weight: bold; color: #111827; font-size: 12px; padding-top: 10px; padding-bottom: 10px;">
                    Operating Profit</td>
                <td class="amount"
                    style="font-weight: bold; color: #111827; font-size: 12px; border-top: 2px solid #111827; padding-top: 10px; padding-bottom: 10px;">
                    {{ number_format($operatingProfit ?? 0, 2, ',', '.') }}</td>
            </tr>

            <!-- 4. PENDAPATAN & BEBAN LAIN-LAIN -->
            @php
            $otherRevenues = $otherRevenues ?? collect();
            $otherExpenses = $otherExpenses ?? collect();
            @endphp

            @if($otherRevenues->count() > 0 || $otherExpenses->count() > 0)
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: white; text-transform: uppercase;">
                    Other Revenue &amp; Expenses</td>
            </tr>
            @endif

            @if($otherRevenues->count() > 0)
            @php
            $othRevNodes = ($otherRevenues->count() === 1 && $otherRevenues->first()->is_header) ?
            $otherRevenues->first()->children : $otherRevenues;
            @endphp
            @foreach($othRevNodes as $node)
            @include('filament.pages.reports.partials.account-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937; padding-left: 10px;">Total Other Revenue</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">{{ number_format($totalOtherRevenue ?? 0,
                    2, ',', '.') }}</td>
            </tr>
            @endif

            @if($otherExpenses->count() > 0)
            @php
            $othExpNodes = ($otherExpenses->count() === 1 && $otherExpenses->first()->is_header) ?
            $otherExpenses->first()->children : $otherExpenses;
            @endphp
            @foreach($othExpNodes as $node)
            @include('filament.pages.reports.partials.account-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937; padding-left: 10px;">Total Other Expenses</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">{{ number_format($totalOtherExpense ?? 0,
                    2, ',', '.') }}</td>
            </tr>
            @endif

            <!-- NET INCOME -->
            <tr style="background-color: #f3f4f6;">
                <td
                    style="padding-left: 10px; font-weight: bold; color: #111827; font-size: 13px; padding-top: 12px; padding-bottom: 12px; border-top: 2px solid #1f2937;">
                    Net Income</td>
                <td class="amount"
                    style="font-weight: bold; color: #111827; font-size: 13px; padding-top: 12px; padding-bottom: 12px; border-top: 2px solid #1f2937;">
                    {{ number_format($netIncome ?? 0, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>