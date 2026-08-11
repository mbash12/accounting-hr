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
            size: 210mm 148mm;
            margin: 0;
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
        /* Corporate Design System */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #7f8c8d;
            --accent-color: #3498db;
            --border-color: #e0e0e0;
            --bg-light: #f9f9f9;
            --font-main: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }

        .document-wrapper {
            max-width: 210mm;
            /* A4 width */
            margin: 0 auto;
            font-family: var(--font-main);
            color: #333;
            line-height: 1.4;
            box-sizing: border-box;
        }

        /* Screen Preview enhancements */
        @media screen {
            .document-wrapper {
                margin: 20px auto;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
        }

        .document-page {
            background: white;
            padding: 10mm;
            position: relative;
            min-height: 297mm;
            /* A4 height */
        }

        /* Typography Balanced */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            font-weight: 700;
            color: var(--primary-color);
        }

        p {
            margin: 0 0 4px;
            font-size: 10pt;
        }

        .text-sm {
            font-size: 9pt;
        }

        .text-xs {
            font-size: 8pt;
        }

        .text-bold {
            font-weight: 700;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .text-muted {
            color: var(--secondary-color);
        }

        /* Header Layout */
        .header-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        .company-branding {
            flex: 1;
            max-width: 400px;
        }

        .company-logo {
            max-height: 80px;
            max-width: 300px;
            margin-bottom: 12px;
            object-fit: contain;
        }

        .company-details p {
            color: var(--secondary-color);
            font-size: 9pt;
            line-height: 1.3;
            max-width: 350px;
        }

        .document-info {
            text-align: right;
            flex: 0 0 300px;
        }

        .doc-title {
            font-size: 20pt;
            color: var(--primary-color);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .doc-meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .doc-meta-table td {
            padding: 3px 0;
            font-size: 10pt;
        }

        .doc-meta-label {
            color: var(--secondary-color);
            font-weight: 600;
            text-align: left;
            width: 40%;
        }

        .doc-meta-value {
            text-align: right;
            font-weight: 600;
        }

        /* Address Section */
        .address-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 30px;
        }

        .address-box {
            flex: 1;
        }

        .address-title {
            font-size: 9pt;
            text-transform: uppercase;
            color: var(--secondary-color);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 4px;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .recipient-name {
            font-size: 11pt;
            font-weight: 700;
            margin-bottom: 4px;
        }

        /* Items Table */
        .items-table-container {
            margin-bottom: 25px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        .items-table th {
            text-align: left;
            padding: 8px 6px;
            background-color: var(--bg-light);
            border-bottom: 1px solid var(--primary-color);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9pt;
            color: var(--primary-color);
        }

        .items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        .col-idx {
            width: 5%;
            text-align: center;
        }

        .col-desc {
            width: 55%;
        }

        .col-qty {
            width: 10%;
            text-align: right;
        }

        .col-price {
            width: 15%;
            text-align: right;
        }

        .col-total {
            width: 15%;
            text-align: right;
        }

        /* Financial Summary */
        .summary-section {
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }

        .summary-table {
            width: 320px;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 4px 0;
            font-size: 10pt;
        }

        .summary-label {
            color: var(--secondary-color);
            text-align: left;
        }

        .summary-value {
            text-align: right;
            font-weight: 600;
        }

        .grand-total-row td {
            border-top: 2px solid var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding: 10px 0;
            font-weight: 700;
            font-size: 12pt;
            color: var(--primary-color);
            margin-top: 5px;
        }

        /* Footer / Notes */
        .footer-section {
            margin-top: 30px;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .notes-area {
            flex: 2;
            padding-right: 40px;
        }

        .signature-area {
            flex: 1;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 50px;
            margin-bottom: 5px;
            margin-top: 30px;
        }

        .status-stamp {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid;
            margin-top: 5px;
        }

        .stamp-approved {
            color: #27ae60;
            border-color: #27ae60;
            background: #eafaf1;
        }

        .stamp-pending {
            color: #e67e22;
            border-color: #e67e22;
            background: #fdf2e9;
        }

        .stamp-rejected {
            color: #c0392b;
            border-color: #c0392b;
            background: #f9e79f;
        }

        .stamp-closed {
            color: #333;
            border-color: #333;
            background: #e0e0e0;
        }

        .stamp-draft {
            color: #7f8c8d;
            border-color: #7f8c8d;
            background: #f2f3f4;
        }

        /* Print Media Overrides */
        @media print {
            .document-page {
                box-shadow: none;
                margin: 0;
                padding: 0;
                min-height: 0;
            }

            body {
                background: white;
            }
        }
    </style>

    <div class="document-wrapper">
        <div id="print-area" class="document-page">

            <!-- HEADER -->
            <div class="header-container">
                <div class="company-branding">
                    @if($record->company && $record->company->photo)
                    <img src="{{ Storage::url($record->company->photo) }}" alt="Company Logo" class="company-logo">
                    @else
                    <h1 style="margin-bottom: 10px; font-size: 16pt;">{{ $record->company->name ?? '' }}</h1>
                    @endif

                    <div class="company-details">
                        @if(!$record->company->photo)<p class="text-bold" style="font-size: 11pt;">{{ $record->company->name ?? '' }}</p>@endif
                        <p>
                            {!! nl2br(e($record->company->billing_address_line_1 ?? '')) !!}
                            @if($record->company->billing_address_line_2)<br>{!! nl2br(e($record->company->billing_address_line_2)) !!}@endif
                            @if($record->company->billing_city)<br>{!! nl2br(e($record->company->billing_city)) !!}, {!! nl2br(e($record->company->billing_state)) !!} {!! nl2br(e($record->company->billing_postal_code)) !!}@endif
                        </p>
                        @if($record->company->tax_id)<p>Tax ID: {{ $record->company->tax_id }}</p>@endif
                        @if($record->company->phone)<p>Phone: {{ $record->company->phone }}</p>@endif
                        @if($record->company->email)<p>Email: {{ $record->company->email }}</p>@endif
                    </div>
                </div>

                <div class="document-info">
                    <h2 class="doc-title">SALES INVOICE</h2>
                    <table class="doc-meta-table">
                        <tr>
                            <td class="doc-meta-label">Invoice No.:</td>
                            <td class="doc-meta-value">{{ $record->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="doc-meta-label">Date:</td>
                            <td class="doc-meta-value">{{ $record->date ? $record->date->format('d M Y') : '-' }}</td>
                        </tr>
                        @if($record->reference_no)
                        <tr>
                            <td class="doc-meta-label">Reference:</td>
                            <td class="doc-meta-value">{{ $record->reference_no }}</td>
                        </tr>
                        @endif
                        @if($record->paymentTerm)
                        <tr>
                            <td class="doc-meta-label">Payment Term:</td>
                            <td class="doc-meta-value">{{ $record->paymentTerm->name }}</td>
                        </tr>
                        @endif
                        @if($record->due_date)
                        <tr>
                            <td class="doc-meta-label">Due Date:</td>
                            <td class="doc-meta-value">{{ $record->due_date->format('d M Y') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- CUSTOMER -->
            <div class="address-section">
                <div class="address-box">
                    <div class="address-title">Customer Details</div>
                    @if($record->customer)
                    <p class="recipient-name">{{ $record->customer->name }}</p>
                    <p>
                        {!! nl2br(e($record->customer->billing_address_line_1 ?? '')) !!}
                        @if($record->customer->billing_address_line_2)<br>{!! nl2br(e($record->customer->billing_address_line_2)) !!}@endif
                        <br>
                        {!! nl2br(e($record->customer->billing_city ?? '')) !!}
                        @if($record->customer->billing_state), {!! nl2br(e($record->customer->billing_state)) !!}@endif
                        {!! nl2br(e($record->customer->billing_postal_code ?? '')) !!}
                    </p>
                    @if($record->customer->tax_id)<p class="text-sm text-muted">Tax ID: {{ $record->customer->tax_id }}</p>@endif
                    @else
                    <p class="text-muted">No customer selected</p>
                    @endif
                </div>
                <div class="address-box">
                    <div class="address-title">Invoice Details</div>
                    @if($record->description)
                    <p><span class="text-muted">Notes:</span> <strong>{{ $record->description }}</strong></p>
                    @endif
                </div>
            </div>

            <!-- ITEMS -->
            <div class="items-table-container">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th class="col-idx">#</th>
                            <th class="col-desc">Description</th>
                            <th class="col-qty" style="text-align: right;">QTY</th>
                            <th class="col-price" style="text-align: right;">Unit Price</th>
                            <th class="col-total" style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($record->items as $index => $item)
                        <tr>
                            <td class="col-idx">{{ $index + 1 }}</td>
                            <td class="col-desc">
                                <strong>{{ $item->product->name ?? $item->description ?? '' }}</strong>
                                @if($item->description && $item->description !== ($item->product->name ?? ''))
                                <div class="text-sm text-muted">{{ $item->description }}</div>
                                @endif
                            </td>
                            <td class="col-qty">
                                {{ number_format($item->quantity, 0, ',', '.') }}
                                <span class="text-xs text-muted">{{ $item->unit->name ?? '' }}</span>
                            </td>
                            <td class="col-price">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="col-total text-bold">{{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 20px;">No items in this invoice</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- SUMMARY -->
            <div class="summary-section">
                <table class="summary-table">
                    <tr>
                        <td class="summary-label">Subtotal</td>
                        <td class="summary-value">{{ number_format($record->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($record->discount > 0)
                    <tr>
                        <td class="summary-label">Discount</td>
                        <td class="summary-value" style="color: #c0392b;">-{{ number_format($record->discount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($record->tax_amount > 0)
                    <tr>
                        <td class="summary-label">Tax (PPN)</td>
                        <td class="summary-value">{{ number_format($record->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                                        @php
                        $additionalCharges = method_exists($record, 'otherCharges')
                            ? $record->otherCharges
                            : collect();
                    @endphp
                    @if($additionalCharges->isNotEmpty())
                        @foreach($additionalCharges as $charge)
                            @if(($charge->amount ?? 0) > 0)
                            <tr>
                                <td class="summary-label">{{ $charge->name ?: 'Additional Charge' }}</td>
                                <td class="summary-value">{{ number_format($charge->amount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        @endforeach
                    @elseif($record->other_charges > 0)
                    <tr>
                        <td class="summary-label">Other Charges</td>
                        <td class="summary-value">{{ number_format($record->other_charges, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="grand-total-row">
                        <td class="summary-label" style="color: var(--primary-color);">TOTAL</td>
                        <td class="summary-value">{{ number_format($record->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- FOOTER -->
            <div class="footer-section">
                <div class="notes-area">
                    <h4 style="font-size: 9pt; margin-bottom: 5px;">Terms & Conditions</h4>
                    <p class="text-sm text-muted">
                        This is a sales invoice. Payment is due according to the terms specified above.
                        <br>
                        Please reference the invoice number in all correspondence regarding this invoice.
                    </p>
                    @if($record->description)
                    <div style="margin-top: 10px; font-style: italic;" class="text-sm">
                        Notes: {{ $record->description }}
                    </div>
                    @endif
                </div>

                <div class="signature-area">
                    <div style="margin-bottom: 40px;">Authorized By</div>
                    <div class="signature-line"></div>
                    <div class="text-sm text-bold">{{ $record->company->name ?? '' }}</div>
                </div>
            </div>

        </div>
    </div>
    @include('filament.pages.partials.compact-document-print')
</x-filament-panels::page>
