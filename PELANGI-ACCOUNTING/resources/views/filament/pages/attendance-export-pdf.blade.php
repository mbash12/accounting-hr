<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Export</title>
    <style>
        @page { margin: 24px 20px; }
        body { font-family: sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; margin-bottom: 14px; }
        .title { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .generated-at { margin-top: 4px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #1e3a8a; color: white; padding: 6px 4px; border: 1px solid #b7c0ce; text-align: left; }
        td { padding: 5px 4px; border: 1px solid #cbd5e1; vertical-align: top; }
        .num, .center { text-align: center; }
        .nowrap { white-space: nowrap; }
        .status { text-transform: capitalize; }
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Attendance Data</div>
        <div class="generated-at">Generated at: {{ $generatedAt->format('d-m-Y H:i:s') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th class="num">Late (Min)</th>
                <th class="num">Early (Min)</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Check In Notes</th>
                <th>Check Out Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td class="nowrap">{{ $record['employee_id'] }}</td>
                    <td>{{ $record['employee_name'] }}</td>
                    <td>{{ $record['department'] }}</td>
                    <td class="nowrap">{{ $record['date'] }}</td>
                    <td class="nowrap">{{ $record['check_in'] }}</td>
                    <td class="nowrap">{{ $record['check_out'] }}</td>
                    <td class="num">{{ $record['late_minutes'] }}</td>
                    <td class="num">{{ $record['early_departure_minutes'] }}</td>
                    <td class="status">{{ $record['status'] }}</td>
                    <td>{{ $record['notes'] }}</td>
                    <td>{{ $record['notes_in'] }}</td>
                    <td>{{ $record['notes_out'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="center">No attendance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
