<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 14px; font-weight: bold; text-transform: uppercase; }
        .report-title { font-size: 16px; font-weight: bold; color: #1e3a8a; margin: 5px 0; }
        .report-meta { font-size: 11px; color: #666; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #1e3a8a; color: white; padding: 8px; border: 1px solid #ccc; text-align: left; }
        td { padding: 8px; border: 1px solid #ccc; }
        .num { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company?->name }}</div>
        <div class="report-title">LAPORAN REKAPITULASI KEHADIRAN</div>
        <div class="report-meta">
            Month: {{ $month_name }} {{ $year }}
            @if($department) | Department: {{ $department->name }} @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th class="num">Hadir</th>
                <th class="num">Lbt</th>
                <th class="num">Alpa</th>
                <th class="num">Permit</th>
                <th class="num">Leave</th>
                <th class="num">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td style="font-weight: bold;">{{ $record['employee']->name }}</td>
                    <td>{{ $record['employee']->department?->name }}</td>
                    <td class="num">{{ $record['present'] }}</td>
                    <td class="num">{{ $record['late'] }}</td>
                    <td class="num">{{ $record['absent'] }}</td>
                    <td class="num">{{ $record['permit'] }}</td>
                    <td class="num">{{ $record['leave'] }}</td>
                    <td class="num" style="font-weight: bold;">{{ $record['total_working_days'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
