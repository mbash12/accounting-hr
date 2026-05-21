<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Trial Balance</title>
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
            border: 1px solid #e5e7eb;
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
        td.amount,
        td.num {
            text-align: right;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        /* Trial balance specific */
        thead tr:first-child th {
            text-align: center;
        }

        thead tr:last-child th {
            background-color: #eff6ff;
            color: #1e3a8a;
            border-color: #d1d5db;
            text-align: right;
        }

        thead tr:last-child th.left {
            text-align: left;
        }

        tfoot td {
            font-weight: bold;
            border-top: 2px solid #1e3a8a;
            background-color: #eff6ff;
            color: #1e3a8a;
            text-align: right;
        }

        tfoot td.left {
            text-align: left;
        }

        td.code {
            width: 60px;
        }

        td.name {
            min-width: 140px;
        }
    </style>
</head>

<body>
    <div class="report-header">
        <div class="report-company-name">{{ $company->name }}</div>
        <div class="report-title">Trial Balance</div>
        <div class="report-date">
            Dari {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }}
            s/d {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
        </div>
    </div>

    @php
    $totOpenDebit = $rows->sum('open_debit');
    $totOpenCredit = $rows->sum('open_credit');
    $totPeriodDebit = $rows->sum('period_debit');
    $totPeriodCredit = $rows->sum('period_credit');
    $totEndDebit = $rows->sum('end_debit');
    $totEndCredit = $rows->sum('end_credit');
    $fmt = fn($v) => $v != 0 ? number_format($v, 2, ',', '.') : '-';
    @endphp

    <table>
        <thead>
            <tr>
                <th rowspan="2" class="left" style="width:60px;">Kode</th>
                <th rowspan="2" class="left" style="min-width:130px;">Account Name</th>
                <th colspan="2">Opening Balance</th>
                <th colspan="2">Perubahan</th>
                <th colspan="2">Closing Balance</th>
            </tr>
            <tr>
                <th>Debit</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
            <tr>
                <td class="code">{{ $row['code'] }}</td>
                <td class="name">{{ $row['name'] }}</td>
                <td class="num">{{ $fmt($row['open_debit']) }}</td>
                <td class="num">{{ $fmt($row['open_credit']) }}</td>
                <td class="num">{{ $fmt($row['period_debit']) }}</td>
                <td class="num">{{ $fmt($row['period_credit']) }}</td>
                <td class="num">{{ $fmt($row['end_debit']) }}</td>
                <td class="num">{{ $fmt($row['end_credit']) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:10px; color:#9ca3af;">
                    Tidak ada data untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="left">Total</td>
                <td>{{ number_format($totOpenDebit, 2, ',', '.') }}</td>
                <td>{{ number_format($totOpenCredit, 2, ',', '.') }}</td>
                <td>{{ number_format($totPeriodDebit, 2, ',', '.') }}</td>
                <td>{{ number_format($totPeriodCredit, 2, ',', '.') }}</td>
                <td>{{ number_format($totEndDebit, 2, ',', '.') }}</td>
                <td>{{ number_format($totEndCredit, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>