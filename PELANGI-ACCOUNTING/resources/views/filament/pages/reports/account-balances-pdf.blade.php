<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Account Balance Report</title>
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
        <div class="report-title">Account Balance Report</div>
        <div class="report-date">As of {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Account Name</th>
                <th>Account Type</th>
                <th class="amount">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            @include('filament.pages.reports.partials.account-balances-tree-pdf', ['account' => $account, 'level' => 0])
            @endforeach
        </tbody>
    </table>
</body>

</html>