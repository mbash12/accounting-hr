<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Voucher - {{ $journalEntry->entry_number }}</title>
    <style>
        /* Base Styles */
        body {
            font-family: Arial, sans-serif;
            color: #000;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 210mm;
            height: 148mm;
            background: #fff;
            box-sizing: border-box;
            position: relative;
        }

        /* Screen Preview Styles */
        @media screen {
            body {
                background: #eee;
                padding: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }
            .container {
                margin: 20px auto;
                padding: 10mm;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .no-print {
                position: absolute;
                top: 20px;
                right: 20px;
                z-index: 100;
            }
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            margin-top: 10px;
        }
        
        .company-info {
            width: 45%;
            font-size: 11px;
            margin-left: 20px;
        }
        .company-info strong {
            font-size: 13px;
        }
        
        .voucher-info {
            width: 38%;
            border: 1px solid #000;
            border-radius: 6px;
            overflow: hidden;
            margin-right: 0px;
        }
        .voucher-title {
            font-size: 26px;
            font-weight: normal;
            text-align: left;
            padding: 5px 10px;
            border-bottom: 1px solid #000;
            letter-spacing: -0.5px;
        }
        .voucher-details {
            padding: 3px 10px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            font-size: 11px;
        }
        .voucher-row {
            display: flex;
        }
        .voucher-label {
            width: 80px;
        }

        /* Table Styles */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: 1px solid #000;
        }
        table.main-table th {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: center;
            background: #fff;
            font-weight: normal;
        }
        table.main-table td {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            padding: 2px 4px;
            vertical-align: top;
            height: 15px;
        }

        /* The last td needs no bottom border until the very end, which is handled by the table border */
        
        .summary-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 5px;
        }
        
        .left-summary {
            width: 70%;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .say-box-wrapper {
            display: flex;
            align-items: flex-start;
            margin-left: 5px;
        }
        
        .say-label {
            margin-right: 5px;
            padding-top: 3px;
            font-size: 11px;
        }

        .say-box {
            flex: 1;
            border: 1px solid #000;
            border-radius: 5px;
            padding: 3px 6px;
            min-height: 18px;
            font-size: 11px;
        }
        
        .desc-box {
            border: 1px solid #000;
            border-radius: 5px;
            padding: 8px 6px;
            position: relative;
            min-height: 30px;
            font-size: 11px;
            margin-left: 5px;
        }
        .desc-label {
            position: absolute;
            top: -7px;
            left: 5px;
            background: #fff;
            padding: 0 4px;
            font-size: 11px;
        }
        
        .right-summary {
            width: 28%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        
        .totals-box {
            border: 1px solid #000;
            border-radius: 5px;
            padding: 5px 8px;
            margin-bottom: 25px; /* Adjust to align with bottom of description box depending on height */
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 4px;
        }
        
        .footer-signatures {
            display: flex;
            justify-content: flex-start;
            gap: 40px;
            margin-left: 10px;
        }
        .signature-box {
            width: 80px;
            font-size: 11px;
        }
        .signature-line {
            margin-top: 35px;
            border-top: 1px solid #000;
            margin-bottom: 2px;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #6b7280;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 20px;
            cursor: pointer;
            border: none;
        }

        /* Print Overrides */
        @media print {
            body { 
                background: none; 
                display: block; 
                margin: 0; 
                padding: 0;
            }
            .no-print { display: none; }
            .container {
                width: 100%;
                height: 148mm; 
                margin: 0;
                padding: 5mm; 
                box-shadow: none;
                border: none;
                page-break-after: always;
            }
            .container:last-child {
                page-break-after: auto;
            }
            @page { 
                size: A4 portrait; 
                margin: 0; 
            }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
        window.addEventListener('afterprint', function() {
            window.close();
        });
    </script>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">Cetak</button>
    </div>

    @php
        $totalDebit = 0;
        $totalCredit = 0;
        $items = $journalEntry->items;
        
        // Use a fixed minimum row count to fill the area nicely similar to the screenshot
        $minRows = 10;
        $chunks = $items->chunk($minRows);
        if ($chunks->isEmpty()) {
            $chunks->push(collect());
        }
        
        foreach($items as $item) {
            $totalDebit += $item->debit;
            $totalCredit += $item->credit;
        }
    @endphp

    @foreach($chunks as $index => $chunk)
    <div class="container {{ !$loop->last ? 'page-break' : '' }}">
        
        <div class="header">
            <div class="company-info">
                <strong>{{ $journalEntry->company->name ?? 'PT. PELANGI SENTRAL KREASI' }}</strong><br>
                {!! nl2br(e($journalEntry->company->address ?? 'JL. KH. MOH. MANSYUR BLOK 15A/12'."\n".'JAKARTA PUSAT')) !!}
            </div>
            <div class="voucher-info">
                <div class="voucher-title">Journal Voucher</div>
                <div class="voucher-details">
                    <div class="voucher-row">
                        <span class="voucher-label">Voucher No.</span>
                        <span>: {{ $journalEntry->entry_number }}</span>
                    </div>
                    <div class="voucher-row">
                        <span class="voucher-label">Date</span>
                        <span>: {{ $journalEntry->date ? $journalEntry->date->format('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <table class="main-table" style="height: 190px;">
            <thead>
                <tr>
                    <th style="width: 16%">Account No.</th>
                    <th style="width: 32%">Account Name</th>
                    <th style="width: 16%">Debit</th>
                    <th style="width: 16%">Credit</th>
                    <th style="width: 20%">Memo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chunk as $item)
                <tr>
                    <td>{{ $item->account->code ?? $item->account->account_code ?? '-' }}</td>
                    <td>{{ $item->account->name ?? '-' }}</td>
                    <td style="text-align: right">{{ $item->debit != 0 ? number_format($item->debit, 2, '.', '.') : '0' }}</td>
                    <td style="text-align: right">{{ $item->credit != 0 ? number_format($item->credit, 2, '.', '.') : '0' }}</td>
                    <td>{{ $item->notes ?? '' }}</td>
                </tr>
                @endforeach
                
                @for($i = 0; $i < max(0, $minRows - count($chunk)); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
                
                <!-- Extra row to stretch the remaining space -->
                <tr style="height: auto;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        @if($loop->last)
        <div class="summary-section">
            <div class="left-summary">
                <div class="say-box-wrapper">
                    <span class="say-label">Say :</span>
                    <div class="say-box">
                        @php
                            $terbilangHelperExists = class_exists(\App\Helpers\TerbilangHelper::class);
                            if ($terbilangHelperExists && method_exists(\App\Helpers\TerbilangHelper::class, 'convert')) {
                                $totalStr = number_format((float)$totalDebit, 2, '.', '');
                                $parts = explode('.', $totalStr);
                                $rupiah = (int)$parts[0];
                                $sen = isset($parts[1]) ? (int)$parts[1] : 0;

                                $say = \App\Helpers\TerbilangHelper::convert($rupiah);
                                
                                if ($sen > 0) {
                                    $say .= ' koma ' . \App\Helpers\TerbilangHelper::convert($sen);
                                }
                                
                                echo ucfirst(strtolower(trim($say)));
                            } else {
                                echo "-";
                            }
                        @endphp
                    </div>
                </div>
                
                <div class="desc-box">
                    <span class="desc-label">Description</span>
                    {{ $journalEntry->description ?? '-' }}
                </div>
            </div>
            
            <div class="right-summary">
                <div class="totals-box">
                    <div class="totals-row">
                        <span>Debits :</span>
                        <span>{{ number_format($totalDebit, 2, '.', '.') }}</span>
                    </div>
                    <div class="totals-row" style="margin-bottom: 0;">
                        <span>Credits :</span>
                        <span>{{ number_format($totalCredit, 2, '.', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-signatures">
            <div class="signature-box">
                <div>Prepared By</div>
                <div class="signature-line"></div>
                <div>Date:</div>
            </div>
            <div class="signature-box">
                <div>Reviewed By</div>
                <div class="signature-line"></div>
                <div>Date:</div>
            </div>
            <div class="signature-box">
                <div>Approved By</div>
                <div class="signature-line"></div>
                <div>Date:</div>
            </div>
        </div>
        @endif
        
    </div>
    @endforeach
</body>
</html>
