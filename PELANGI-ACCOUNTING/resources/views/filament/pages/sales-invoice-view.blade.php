<x-filament-panels::page>
    @if(request()->query('print'))
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @page {
            size: A4 portrait;
            margin: 5mm 15mm;
        }
    </style>
    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        };
    </script>
    @endif

    <style>
        :root {
            --primary-color: #2c3e50;
            --border-color: #000;
            --font-main: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }

        .document-wrapper {
            width: 100%;
            margin: 0 auto;
            font-family: var(--font-main);
            color: #000;
            line-height: 1.2;
            box-sizing: border-box;
            background: white;
            font-size: 9pt;
        }

        /* Screen Preview */
        @media screen {
            .document-wrapper {
                margin: 20px auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 10mm;
            }
        }

        @media print {
            .document-wrapper {
                padding: 0;
                box-shadow: none;
            }
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: 700;
        }

        .uppercase {
            text-transform: uppercase;
        }

        /* Layout Grid */
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-title {
            font-size: 14pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #333;
            letter-spacing: 2px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            align-items: flex-start;
        }

        .customer-box {
            flex: 1;
            padding-right: 20px;
        }

        .meta-box {
            flex: 0 0 270px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .meta-label {
            width: 100px;
        }

        /* Main Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--border-color);
            margin-bottom: 5px;
        }

        .items-table th,
        .items-table td {
            border-left: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            padding: 4px 6px;
        }

        .items-table th {
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .items-table td {
            border-top: none;
            border-bottom: none;
        }

        .items-table th {
            background-color: #f0f0f0;
            text-align: center;
            text-transform: uppercase;
            font-size: 8pt;
            font-weight: 700;
        }

        .col-no {
            width: 30px;
            text-align: center;
        }

        .col-desc {
            text-align: left;
        }

        .col-qty {
            width: 40px;
            text-align: center;
        }

        .col-price {
            width: 100px;
            text-align: right;
        }

        .col-total {
            width: 130px;
            text-align: right;
        }

        /* Footer */
        .footer-section {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }

        .left-footer {
            flex: 1;
            padding-right: 20px;
        }

        .right-footer {
            flex: 0 0 300px;
        }

        .bank-info {
            margin-top: 10px;
            font-weight: 600;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 2px 0;
            text-align: right;
        }

        .totals-label {
            text-align: right;
            padding-right: 10px;
            width: 60%;
        }

        .signature-section {
            margin-top: 20px;
            text-align: center;
            width: 200px;
            float: right;
        }

        .signature-gap {
            height: 50px;
        }

        .company-signer {
            font-weight: 700;
        }
    </style>

    <div class="document-wrapper">
        <div id="print-area">

            <div class="invoice-header">
                <div class="invoice-title">INVOICE</div>
            </div>

            <div class="info-section">
                <div class="customer-box">
                    <div style="margin-bottom: 5px;">Kepada Yth. :</div>
                    <div class="text-bold" style="font-size: 10pt; text-transform: uppercase;">
                        {{ $record->customer->name ?? 'Pelanggan' }}
                    </div>
                    <div style="font-size: 9pt;">
                        {!! nl2br(e($record->customer->billing_address_line_1 ?? '')) !!}
                        @if($record->customer && $record->customer->billing_address_line_2)<br>{!!
                        nl2br(e($record->customer->billing_address_line_2)) !!}@endif
                        <br>
                        {!! nl2br(e($record->customer->billing_city ?? '')) !!}
                        {{ $record->customer->billing_postal_code ?? '' }}
                    </div>
                </div>

                <div class="meta-box">
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Invoice No.</td>
                            <td style="width: 10px;">:</td>
                            <td>{{ $record->invoice_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">No. PO</td>
                            <td>:</td>
                            <td>{{ $record->salesOrder->client_po_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">No. Ref Pajak</td>
                            <td>:</td>
                            <td>{{ $record->reference_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Invoice Date</td>
                            <td>:</td>
                            <td>{{ $record->date ? $record->date->format('d F Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th class="col-no">No.</th>
                        <th class="col-desc">DESCRIPTION</th>
                        <th class="col-qty">QTY</th>
                        <th class="col-price">UNIT PRICE (RP)</th>
                        <th class="col-total">TOTAL AMOUNT (RP)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowCount = 0; @endphp
                    @foreach($record->items as $index => $item)
                    @php $rowCount++; @endphp
                    <tr>
                        <td class="col-no">{{ $index + 1 }}</td>
                        <td class="col-desc">
                            {{ $item->item_name ?? $item->product->name ?? $item->description }}
                        </td>
                        <td class="col-qty">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                        <td class="col-price">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="col-total">{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach

                    {{-- Fill empty rows to maintain height if needed, usually 3-5 rows minimum for half page --}}
                    @for($i = $rowCount; $i < 5; $i++) <tr>
                        <td class="col-no">&nbsp;</td>
                        <td class="col-desc"></td>
                        <td class="col-qty"></td>
                        <td class="col-price"></td>
                        <td class="col-total"></td>
                        </tr>
                        @endfor
                </tbody>
            </table>

            <div class="footer-section">
                <div class="left-footer">
                    <div>Ditransfer Ke :</div>
                    <div class="bank-info">
                        BCA<br>
                        A/C. 369.300.4141<br>
                        A/N. PT. PELANGI SENTRAL KREASI
                    </div>

                    <div style="margin-top: 10px; font-size: 8pt; line-height: 1.4;">
                        @if($record->paymentTerm)
                            <div><strong>Termin:</strong> {{ $record->paymentTerm->name }}</div>
                        @endif
                        @if($record->due_date)
                            <div><strong>Jatuh Tempo:</strong> {{ $record->due_date->format('d F Y') }}</div>
                        @endif
                        @if($record->description)
                            <div style="margin-top: 3px;"><strong>Deskripsi:</strong> {{ $record->description }}</div>
                        @endif
                    </div>

                    <div style="margin-top: 15px; font-size: 7.5pt;">
                        <div style="margin-bottom: 2px;">Note :</div>
                        <ol
                            style="margin: 0; padding-left: 10px; list-style-type: decimal; list-style-position: outside;">
                            <li style="margin-bottom: 2px;">Invoice ini menjadi sah jika dibubuhi cap perusahaan dan
                                tanda tangan.</li>
                            <li>Semua pembayaran Transfer, Cek & Giro dianggap lunas setelah masuk ke rekening atas nama
                                PT. Pelangi Sentral Kreasi.</li>
                        </ol>
                    </div>
                </div>

                <div class="right-footer">
                    <table class="totals-table">
                        <tr>
                            <td class="totals-label">Sub Total :</td>
                            <td>{{ number_format($record->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($record->discount > 0)
                        <tr>
                            <td class="totals-label">Diskon :</td>
                            <td>-{{ number_format($record->discount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($record->tax_amount > 0)
                        <tr>
                            <td class="totals-label" style="padding-top: 10px;">PPN :</td>
                            <td style="padding-top: 10px;">{{ number_format($record->tax_amount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="totals-label text-bold">Total Invoice :</td>
                            <td class="text-bold">{{ number_format($record->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    <div class="signature-section">
                        <div>Hormat kami,</div>
                        <div style="font-size: 9pt;">PT. PELANGI SENTRAL KREASI</div>
                        <div class="signature-gap"></div>
                        <div class="company-signer">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>