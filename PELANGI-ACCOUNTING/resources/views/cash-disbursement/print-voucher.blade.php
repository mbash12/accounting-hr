<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Other Payment - {{ $cashDisbursement->disbursement_number }}</title>
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
            margin-bottom: 8px;
            margin-top: 5px;
        }
        
        .left-header-section {
            width: 58%;
            display: flex;
            flex-direction: column;
            gap: 15px; /* Space between company info and payer info */
        }
        
        .company-info {
            width: 80%;
            font-size: 11px;
            margin-left: 20px;
            border: 1px solid #000;
            border-radius: 6px;
            padding: 5px 8px;
        }
        .company-info strong {
            font-size: 12px;
            display: block;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
            margin-bottom: 3px;
        }
        
        .payer-info-box {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .payer-info-row {
            display: flex;
        }
        .payer-label {
            width: 65px;
            font-size: 11px;
            padding-top: 2px;
        }
        .payer-value {
            flex: 1;
            border: 1px solid #000;
            border-radius: 6px;
            padding: 4px 6px;
            min-height: 18px;
        }
        .payer-value.no-border {
            border: none;
            padding-left: 0;
        }
        
        .voucher-info-wrapper {
            width: 40%;
            margin-right: 0px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .voucher-title {
            font-size: 26px;
            font-weight: normal;
            text-align: right;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }
        
        .voucher-details-box {
            border: 1px solid #000;
            border-radius: 6px;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .voucher-row {
            display: flex;
            border-bottom: 1px dashed #000;
        }
        .voucher-row:last-child {
            border-bottom: none;
        }
        
        .voucher-col {
            padding: 2px 6px;
            display: flex;
            flex-direction: column;
            width: 50%;
        }
        
        .voucher-col-right {
            border-left: 1px dashed #000;
        }
        
        .voucher-label-sm {
            font-size: 10px;
        }
        .voucher-value-sm {
            font-size: 11px;
            text-align: center;
            margin-top: 1px;
        }
        .voucher-value-sm.right-align {
            text-align: right;
        }

        /* Table Styles */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
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
        
        .summary-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
        }
        
        .left-summary {
            width: 63%;
            display: flex;
            flex-direction: column;
        }
        
        .say-box-wrapper {
            display: flex;
            align-items: flex-start;
            margin-left: 0px;
            margin-bottom: 5px;
        }
        
        .say-label {
            margin-right: 5px;
            margin-top: 3px;
        }
        
        .say-box {
            flex: 1;
            border: 1px solid #000;
            border-radius: 5px;
            padding: 3px 6px;
            min-height: 20px; /* Thinner say box matching screenshot */
            font-size: 11px;
        }
        
        .right-summary {
            width: 35%;
            display: flex;
            justify-content: flex-end;
        }
        
        .totals-box {
            border: 1px solid #000;
            border-radius: 5px;
            padding: 3px 8px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            font-size: 12px;
        }
        
        .desc-box {
            border: 1px solid #000;
            border-radius: 5px;
            padding: 8px 6px;
            position: relative;
            min-height: 40px;
            font-size: 11px;
            width: 63%; /* Match left summary width roughly */
            margin-bottom: 5px;
        }
        .desc-label {
            position: absolute;
            top: -7px;
            left: 5px;
            background: #fff;
            padding: 0 4px;
            font-size: 10px;
        }
        
        .footer-signatures {
            display: flex;
            justify-content: flex-start;
            gap: 25px;
            margin-left: 5px;
            margin-top: -5px;
        }
        .signature-box {
            width: 65px;
            font-size: 11px;
            text-align: left;
        }
        .signature-line {
            margin-top: 25px;
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
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">Cetak</button>
    </div>

    @php
        $totalAmount = 0;
        $items = $cashDisbursement->items;
        
        $minRows = 10;
        $chunks = $items->chunk($minRows);
        if ($chunks->isEmpty()) {
            $chunks->push(collect());
        }
        
        foreach($items as $item) {
            $totalAmount += $item->amount;
        }
    @endphp

    @foreach($chunks as $index => $chunk)
    <div class="container {{ !$loop->last ? 'page-break' : '' }}">
        
        <div class="header">
            <div class="left-header-section">
                <div class="company-info">
                    <strong>{{ $cashDisbursement->company->name ?? 'PT. PELANGI SENTRAL KREASI' }}</strong>
                    {!! nl2br(e($cashDisbursement->company->address ?? 'JL. KH. MOH. MANSYUR BLOK 15A/12'."\n".'JAKARTA PUSAT')) !!}
                </div>
                
                <div class="payer-info-box">
                    <div class="payer-info-row">
                        <span class="payer-label">Paid From :</span>
                        <div class="payer-value">
                            {{ $cashDisbursement->fromAccount->account_number ?? $cashDisbursement->fromAccount->code ?? '' }}-{{ $cashDisbursement->fromAccount->name ?? '-' }} ({{ $cashDisbursement->company->currency ?? 'Rp.' }}) {{ $cashDisbursement->fromAccount->bank_account_number ?? '' }}
                        </div>
                    </div>
                    <div class="payer-info-row" style="margin-top: 2px;">
                        <span class="payer-label">Payee :</span>
                        <div class="payer-value no-border" style="min-height: auto;">
                            {{ $cashDisbursement->recipient->name ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="voucher-info-wrapper">
                <div class="voucher-title">Other Payment</div>
                <div class="voucher-details-box">
                    <div class="voucher-row">
                        <div class="voucher-col">
                            <span class="voucher-label-sm">Date</span>
                            <span class="voucher-value-sm">{{ $cashDisbursement->date ? $cashDisbursement->date->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="voucher-col voucher-col-right">
                            <span class="voucher-label-sm">Voucher No.</span>
                            <span class="voucher-value-sm">{{ $cashDisbursement->disbursement_number }}</span>
                        </div>
                    </div>
                    <div class="voucher-row">
                        <div class="voucher-col">
                            <span class="voucher-label-sm">Rate</span>
                            <span class="voucher-value-sm">1</span>
                        </div>
                        <div class="voucher-col voucher-col-right">
                            <span class="voucher-label-sm">Cheque No.</span>
                            <span class="voucher-value-sm">{{ $cashDisbursement->reference_no ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="voucher-row">
                        <div class="voucher-col">
                            <span class="voucher-label-sm">Currency</span>
                            <span class="voucher-value-sm">IDR</span>
                        </div>
                        <div class="voucher-col voucher-col-right">
                            <span class="voucher-label-sm">Amount</span>
                            <span class="voucher-value-sm right-align">{{ number_format($totalAmount, 2, '.', '') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <table class="main-table" style="height: 180px;">
            <thead>
                <tr>
                    <th style="width: 15%">Account No.</th>
                    <th style="width: 35%">Account Name</th>
                    <th style="width: 15%">Amount</th>
                    <th style="width: 35%">Memo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chunk as $item)
                <tr>
                    <td>{{ $item->account->code ?? $item->account->account_code ?? '-' }}</td>
                    <td>{{ $item->account->name ?? '-' }}</td>
                    <td style="text-align: right">{{ $item->amount != 0 ? number_format($item->amount, 2, '.', '') : '0' }}</td>
                    <td>{{ $item->description ?? '' }}</td>
                </tr>
                @endforeach
                
                @for($i = 0; $i < max(0, $minRows - count($chunk)); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
                
                <!-- Extra row -->
                <tr style="height: auto;">
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
                                $totalStr = number_format((float)$totalAmount, 2, '.', '');
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
            </div>
            
            <div class="right-summary">
                <div class="totals-box">
                    <span>Total Payment :</span>
                    <span>{{ number_format($totalAmount, 2, '.', '') }}</span>
                </div>
            </div>
        </div>
        
        <div class="desc-box">
            <span class="desc-label">-Memo</span>
            {{ $cashDisbursement->description ?? '-' }}
        </div>
        
        <div class="footer-signatures">
            <div class="signature-box">
                <div>Prepared By</div>
                <div class="signature-line"></div>
                <div>Date:</div>
            </div>
            <div class="signature-box">
                <div>Approved By</div>
                <div class="signature-line"></div>
                <div>Date:</div>
            </div>
            <div class="signature-box">
                <div>Paid By</div>
                <div class="signature-line"></div>
                <div>Date:</div>
            </div>
        </div>
        @endif
        
    </div>
    @endforeach
</body>
</html>
