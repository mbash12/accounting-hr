<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Balance Sheet (Standard)</title>
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
        <div class="report-title">Balance Sheet (Standard)</div>
        <div class="report-date">Per Tgl. {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount" style="width: 150px;">Balance</th>
            </tr>
        </thead>
        <tbody>
            <!-- ASSETS -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: white; text-transform: uppercase;">
                    Aktiva</td>
            </tr>
            @php
            $assetNodes = ($assets->count() === 1 && $assets->first()->is_header) ? $assets->first()->children :
            $assets;
            @endphp
            @foreach($assetNodes as $node)
            @include('filament.pages.reports.partials.account-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach
            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #000; padding-left: 10px;">Total Assets</td>
                <td class="amount" style="font-weight: bold; color: #000;">{{
                    number_format($assets->sum('calculated_balance'), 2, ',', '.') }}</td>
            </tr>

            <!-- LIABILITIES & EQUITY -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-top: 15px; background-color: white; text-transform: uppercase;">
                    Kewajiban and Equity</td>
            </tr>

            <!-- Liabilities -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-left: 10px; background-color: white; text-transform: uppercase;">
                    Kewajiban</td>
            </tr>
            @php
            $liabNodes = ($liabilities->count() === 1 && $liabilities->first()->is_header) ?
            $liabilities->first()->children : $liabilities;
            @endphp
            @foreach($liabNodes as $node)
            @include('filament.pages.reports.partials.account-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach

            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937; padding-left: 15px;">Total Liabilities</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">{{
                    number_format($liabilities->sum('calculated_balance'), 2, ',', '.') }}</td>
            </tr>

            <!-- Equity -->
            <tr>
                <td colspan="2"
                    style="font-weight: bold; color: #1e3a8a; padding-left: 10px; padding-top: 15px; background-color: white; text-transform: uppercase;">
                    Ekuitas</td>
            </tr>
            @php
            $equityNodes = ($equity->count() === 1 && $equity->first()->is_header) ? $equity->first()->children :
            $equity;
            @endphp
            @foreach($equityNodes as $node)
            @include('filament.pages.reports.partials.account-row-pdf', ['account' => $node, 'level' => 0])
            @endforeach

            <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                <td style="font-weight: bold; color: #1f2937; padding-left: 15px;">Total Equity</td>
                <td class="amount" style="font-weight: bold; color: #1f2937;">{{
                    number_format($equity->sum('calculated_balance'), 2, ',', '.') }}</td>
            </tr>

            @php
            $totalLiabilities = $liabilities->sum('calculated_balance');
            $totalEquity = $equity->sum('calculated_balance');
            @endphp
            <tr style="border-top: 2px solid #1f2937; background-color: white;">
                <td style="font-weight: bold; color: #000; padding-left: 10px;">Total Liabilities and Equity</td>
                <td class="amount" style="font-weight: bold; color: #000;">{{ number_format($totalLiabilities +
                    $totalEquity, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>