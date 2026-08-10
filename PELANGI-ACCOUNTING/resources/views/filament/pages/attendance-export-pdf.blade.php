<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Export</title>
    <style>
        @page { margin: 24px 20px; }
        body { font-family: sans-serif; font-size: 8px; color: #333; }
        .header { text-align: center; margin-bottom: 14px; }
        .title { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .generated-at { margin-top: 4px; color: #666; }
        .page-section { width: 100%; }
        .page-break { height: 0; page-break-after: always; }
        .row { display: table; width: 100%; table-layout: fixed; }
        .cell { display: table-cell; box-sizing: border-box; padding: 4px 3px; border: 1px solid #cbd5e1; vertical-align: top; word-wrap: break-word; }
        .head .cell { background-color: #1e3a8a; color: white; font-size: 7px; font-weight: bold; }
        .id { width: 8%; }
        .name { width: 12%; }
        .department { width: 11%; }
        .date { width: 7%; }
        .time { width: 6%; }
        .number { width: 5%; text-align: center; }
        .status { width: 7%; text-transform: capitalize; }
        .note { width: 11%; }
        .center { text-align: center; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Attendance Data</div>
        <div class="generated-at">Generated at: {{ $generatedAt->format('d-m-Y H:i:s') }}</div>
    </div>

    @if($records->isEmpty())
        <div class="cell center">No attendance records found.</div>
    @else
        @foreach($records->chunk(30) as $chunk)
            <div class="page-section">
                <div class="row head">
                    <div class="cell id">Employee ID</div>
                    <div class="cell name">Employee Name</div>
                    <div class="cell department">Department</div>
                    <div class="cell date">Date</div>
                    <div class="cell time">Check In</div>
                    <div class="cell time">Check Out</div>
                    <div class="cell number">Late (Min)</div>
                    <div class="cell number">Early (Min)</div>
                    <div class="cell status">Status</div>
                    <div class="cell note">Notes</div>
                    <div class="cell note">Check In Notes</div>
                    <div class="cell note">Check Out Notes</div>
                </div>

                @foreach($chunk as $record)
                    <div class="row">
                        <div class="cell id nowrap">{{ $record['employee_id'] }}</div>
                        <div class="cell name">{{ $record['employee_name'] }}</div>
                        <div class="cell department">{{ $record['department'] }}</div>
                        <div class="cell date nowrap">{{ $record['date'] }}</div>
                        <div class="cell time nowrap">{{ $record['check_in'] }}</div>
                        <div class="cell time nowrap">{{ $record['check_out'] }}</div>
                        <div class="cell number">{{ $record['late_minutes'] }}</div>
                        <div class="cell number">{{ $record['early_departure_minutes'] }}</div>
                        <div class="cell status">{{ $record['status'] }}</div>
                        <div class="cell note">{{ $record['notes'] }}</div>
                        <div class="cell note">{{ $record['notes_in'] }}</div>
                        <div class="cell note">{{ $record['notes_out'] }}</div>
                    </div>
                @endforeach
            </div>

            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endif
</body>
</html>
