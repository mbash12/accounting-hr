<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Receipt - {{ $payment->payment_number }}</title>
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
            overflow: hidden;
            position: relative;
        }

        /* Screen Preview Styles */
        @media screen {
            body {
                background: #eee; /* Preview background */
                padding: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }
            .container {
                margin: auto;
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
        .header-title-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 0px;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            width: 45%; 
        }
        .top-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .left-column {
            width: 55%;
        }
        .right-column {
            width: 44%;
        }
        
        /* Box Styles */
        .info-box {
            border: 1px solid #000;
            border-radius: 8px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .info-box-header {
            font-weight: bold;
            padding: 2px 5px;
            border-bottom: 1px dashed #000;
            background: #f0f0f0;
        }
        .info-box-content {
            padding: 2px 5px;
            min-height: 30px;
        }

        .company-box-wrapper {
            margin-left: 100px; /* Offset to align with customer box content */
            margin-bottom: 5px;
        }
        
        .customer-wrapper {
            display: flex;
            align-items: flex-start;
        }
        .customer-label {
            width: 100px;
            padding-top: 10px;
            font-weight: bold;
        }
        .customer-content {
            flex: 1;
        }

        /* Right Grid Box */
        .grid-box {
            border: 1px solid #000;
            border-radius: 8px;
            overflow: hidden;
        }
        .grid-row {
            display: flex;
            border-bottom: 1px dashed #000;
        }
        .grid-row:last-child {
            border-bottom: none;
        }
        .grid-col {
            padding: 2px 4px;
            border-right: 1px dashed #000;
            flex: 1;
        }
        .grid-col:last-child {
            border-right: none;
        }
        .grid-label {
            font-size: 11px;
            color: #000;
            margin-bottom: 2px;
        }
        .grid-value {
            font-weight: bold;
            font-size: 13px;
        }

        /* Table Styles */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #000;
        }
        table.main-table th {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            background: #fff;
            font-weight: bold;
            border-bottom: 2px solid #000;
        }
        table.main-table td {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            padding: 4px;
            vertical-align: top;
            height: 20px;
        }

        /* Footer Styles */
        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
        }
        .say-box {
            flex: 1;
            border: 1px solid #000;
            border-radius: 8px;
            padding: 10px;
            margin-right: 20px;
            display: flex;
            align-items: flex-start;
        }
        .total-box {
            width: 42%;
            border: 1px solid #000;
            border-radius: 8px;
            overflow: hidden;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 5px;
            border-bottom: 1px solid #000;
        }
        .total-row:last-child {
            border-bottom: none;
        }
        .total-row.highlight {
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        
        .footer-signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature-block {
             display: flex;
             gap: 20px;
             width: 65%;
        }
        .signature-box {
            text-align: left;
            flex: 1;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            margin-bottom: 2px;
        }
        .memo-box {
            border: 1px solid #000;
            border-radius: 8px;
            padding: 15px;
            width: 30%;
            min-height: 50px;
            position: relative;
        }
        .memo-label {
             position: absolute;
             top: -10px;
             left: 10px;
             background: #fff;
             padding: 0 5px;
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
        .page-break {
            /* page-break-after is handled in container style for print now, but useful for safety */
        }

        /* Print Overrides - Must be at the end */
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
        <button onclick="window.print()" class="btn">Print</button>
    </div>

    @php
        $chunks = $payment->items->chunk(3);
        if ($chunks->isEmpty()) {
            $chunks->push(collect());
        }
    @endphp

    @foreach($chunks as $index => $chunk)
    <div class="container {{ !$loop->last ? 'page-break' : '' }}">
        
        <div class="header-title-section">
            <div class="header-title">Cust. Receipt</div>
        </div>

        <div class="top-section">
            <div class="left-column">
                <!-- Company Info -->
                <div class="company-box-wrapper">
                    <div class="info-box">
                        <div class="info-box-header">{{ $payment->company->name ?? 'Company Name' }}</div>
                        <div class="info-box-content">{!! nl2br(e($payment->company->address ?? '')) !!}</div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="customer-wrapper">
                    <div class="customer-label">Received From :</div>
                    <div class="customer-content">
                        <div class="info-box">
                            <div class="info-box-header">{{ $payment->customer->name ?? '' }}</div>
                            <div class="info-box-content">
                                {!! nl2br(e($payment->customer->address ?? '')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-column">
                <div class="grid-box">
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="grid-label">Payment Date</div>
                            <div class="grid-value">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="grid-col">
                            <div class="grid-label">Form No.</div>
                            <div class="grid-value">{{ $payment->payment_number }}</div>
                        </div>
                    </div>
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="grid-label">Cheque Date</div>
                            <div class="grid-value">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="grid-col">
                            <div class="grid-label">Cheque No.</div>
                            <div class="grid-value">{{ $payment->reference_no ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="grid-label">Bank</div>
                            <div class="grid-value">{{ $payment->account ? ($payment->account->code . ' - ' . $payment->account->name) : '' }}</div>
                        </div>
                        <div class="grid-col">
                            <div class="grid-label">Cheque Amount</div>
                            <div class="grid-value">{{ number_format($payment->total_payment, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="grid-row">
                        <div class="grid-col">
                            <div class="grid-label">Currency</div>
                            <div class="grid-value">{{ $payment->currency->code ?? 'IDR' }}</div>
                        </div>
                        <div class="grid-col">
                            <div class="grid-label">Rate</div>
                            <div class="grid-value">1</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 25%">Invoice No.</th>
                    <th style="width: 15%">Date</th>
                    <th style="width: 20%">Amount</th>
                    <th style="width: 15%">Owing</th>
                    <th style="width: 20%">Payment Amount</th>
                    <th style="width: 5%">Total Disc.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chunk as $item)
                <tr>
                    <td>{{ $item->salesInvoice->invoice_number ?? '-' }}</td>
                    <td>{{ $item->salesInvoice && $item->salesInvoice->date ? $item->salesInvoice->date->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: right">{{ number_format($item->salesInvoice->total_amount ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format(($item->salesInvoice->outstanding_amount ?? 0) + ($item->set_payment ?? 0), 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($item->set_payment ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($item->discount_amount ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <!-- Fill empty rows if needed -->
                @for($i = 0; $i < max(0, 3 - count($chunk)); $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <div class="total-section">
            <div class="say-box">
                <span style="white-space: nowrap; margin-right: 10px; font-weight: bold;">Say :</span>
                <span style="font-style: italic;">
                        @php
                        echo ucwords(App\Helpers\TerbilangHelper::convert($payment->total_payment)) . " Rupiah";
                    @endphp
                </span>
            </div>
            <div class="total-box">
                <div class="total-row">
                    <div>Total Owing:</div>
                    <div style="font-weight: bold;">{{ number_format($payment->items->sum(function($item) { return ($item->salesInvoice->outstanding_amount ?? 0) + ($item->set_payment ?? 0); }), 0, ',', '.') }}</div>
                </div>
                <div class="total-row">
                    <div>Total Discount:</div>
                    <div style="font-weight: bold;">{{ number_format($payment->items->sum('discount_amount'), 0, ',', '.') }}</div>
                </div>
                <div class="total-row highlight">
                    <div>Total Payment:</div>
                    <div>{{ number_format($payment->total_payment, 0, ',', '.') }}</div>
                </div>
                <div class="total-row">
                    <div>Overpay:</div>
                    <div style="font-weight: bold;">0</div>
                </div>
            </div>
        </div>

        <div class="footer-signatures">
            <div class="signature-block">
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
                    <div>Paid By</div>
                    <div class="signature-line"></div>
                    <div>Date:</div>
                </div>
                <div class="signature-box">
                    <div>Approved By</div>
                    <div class="signature-line"></div>
                    <div>Date:</div>
                </div>
            </div>

            <div class="memo-box">
                <div class="memo-label">Memo</div>
                {{ $payment->transaction_notes ?? '-' }}
            </div>
        </div>

    </div>
    @endforeach
</body>
</html>
