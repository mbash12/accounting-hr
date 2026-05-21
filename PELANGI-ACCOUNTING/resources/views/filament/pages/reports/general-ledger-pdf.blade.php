<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>General Ledger Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .header {
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14pt;
        }
        .header p {
            margin: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9pt;
        }
        th, td {
            padding: 4px;
            white-space: nowrap;
        }
        
        thead th {
            background-color: #f0f0f0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        
        tbody td {
            border-bottom: 1px dashed #ccc;
        }

        .desc-col {
            white-space: normal;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <h1>{{ $company->name }}</h1>
        <h2>General Ledger Report</h2>
        <p>
            Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} 
            s/d {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
        </p>
    </div>

    @php
        $fmt = fn($v) => $v != 0 ? number_format($v, 2, ',', '.') : '-';
        $fmtBalance = fn($v) => number_format($v, 2, ',', '.');
        $priorDateStr = \Carbon\Carbon::parse($start_date)->subDay()->format('d M Y');
    @endphp

    @foreach($accounts_data as $index => $accData)
    @php
        $account = $accData['account'];
        $rows = $accData['rows'];
        $openingBalance = $accData['opening_balance'];
    @endphp

    <div style="margin-top: {{ $index > 0 ? '30px' : '0' }}; margin-bottom: 10px; font-weight: bold; border-top: {{ $index > 0 ? '1px solid #000' : 'none' }}; padding-top: {{ $index > 0 ? '10px' : '0' }};">
        Account: {{ $account->code }} - {{ $account->name }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Tanggal</th>
                <th class="text-left">No. Sumber</th>
                <th class="text-left">No. Cek</th>
                <th class="text-left">Keterangan</th>
                <th class="text-right">Pemasukan (Dr)</th>
                <th class="text-right">Pengeluaran (Cr)</th>
                <th class="text-right">Balance</th>
                <th class="text-center">Terekonsiliasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="desc-col">As of {{ $priorDateStr }}</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right">{{ $fmtBalance($openingBalance) }}</td>
                <td class="text-center">-</td>
            </tr>

            @forelse($rows as $row)
            <tr>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['source_no'] }}</td>
                <td>{{ $row['check_no'] }}</td>
                <td class="desc-col">{{ $row['description'] }}</td>
                <td class="text-right">{{ $fmt($row['debit']) }}</td>
                <td class="text-right">{{ $fmt($row['credit']) }}</td>
                <td class="text-right">{{ $fmtBalance($row['balance']) }}</td>
                <td class="text-center">{{ $row['reconciled'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">
                    Tidak ada transaksi untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endforeach
</body>
</html>
