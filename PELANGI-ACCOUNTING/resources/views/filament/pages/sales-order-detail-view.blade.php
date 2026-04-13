<x-filament-panels::page>
    @php
    $allRelatedOrders = $this->getAllRelatedOrders();
    $depositOrders = $this->getDepositOrders();
    $aktualOrders = $this->getAktualOrders();
    $allPurchaseOrders = $this->getAllPurchaseOrders();
    $allInvoices = $this->getAllInvoices();
    $comparisonData = $this->getSalesOrderItemsWithComparison();
    $allItemsComparison = $this->getAllSalesOrderItemsWithPoComparison();
    $summary = $this->getSummaryData();
    $isLinked = $this->isLinkedOrder();
    $stats = $this->getOrderStats();
    @endphp

    <style>
        .detail-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .detail-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-card-body {
            padding: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #4b5563;
        }

        .badge-deposit {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .badge-aktual {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .badge-standar {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #3b82f6;
        }

        .table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table th {
            text-align: left;
            padding: 12px 16px;
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .data-table th.text-right {
            text-align: right;
        }

        .data-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .data-table tr:hover td {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-success {
            color: #059669;
        }

        .text-danger {
            color: #dc2626;
        }

        .text-muted {
            color: #6b7280;
        }

        .font-mono {
            font-family: ui-monospace, monospace;
        }

        .text-sm {
            font-size: 13px;
        }

        .text-primary {
            color: #2563eb;
        }

        .italic {
            font-style: italic;
        }

        .inline-block {
            display: inline-block;
        }

        .mr-4 {
            margin-right: 16px;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .icon-sm {
            width: 20px;
            height: 20px;
            color: #2563eb;
        }

        .icon-gray {
            width: 48px;
            height: 48px;
            color: #9ca3af;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .link-primary {
            color: #2563eb;
            text-decoration: none;
        }

        .link-primary:hover {
            text-decoration: underline;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 16px;
            color: white;
        }

        .summary-card.deposit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .summary-card.aktual {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .summary-card.standar {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .summary-card.po {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .summary-card.profit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .summary-card.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .summary-card.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .summary-label {
            font-size: 11px;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: 700;
        }

        .so-link-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }

        .so-link-header {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .so-link-content {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .so-link-number {
            font-family: ui-monospace, monospace;
            font-size: 16px;
            font-weight: 600;
            color: #2563eb;
        }

        .po-item-row {
            background: #fefce8 !important;
        }

        .comparison-positive {
            background: #d1fae5 !important;
        }

        .comparison-negative {
            background: #fee2e2 !important;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .empty-state p {
            margin-top: 16px;
        }

        .section-divider {
            border-top: 2px solid #e5e7eb;
            margin: 24px 0;
        }

        .order-list-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 8px;
        }

        .order-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .order-list-content {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #64748b;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .stats-item {
            text-align: center;
            padding: 12px;
            background: #f9fafb;
            border-radius: 6px;
        }

        .stats-value {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
        }

        .stats-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 4px;
        }
    </style>

    <!-- Statistics Overview -->
    @if($isLinked)
    <div class="stats-grid">
        <div class="stats-item">
            <div class="stats-value">{{ $stats['total_orders'] }}</div>
            <div class="stats-label">Total SO</div>
        </div>
        <div class="stats-item">
            <div class="stats-value">{{ $stats['deposit_orders'] }}</div>
            <div class="stats-label">Deposit</div>
        </div>
        <div class="stats-item">
            <div class="stats-value">{{ $stats['aktual_orders'] }}</div>
            <div class="stats-label">Aktual</div>
        </div>
        <div class="stats-item">
            <div class="stats-value">{{ $stats['total_items'] }}</div>
            <div class="stats-label">Item</div>
        </div>
    </div>
    @endif

    <!-- Summary Cards -->
    @if($isLinked)
    <div class="summary-grid">
        <div class="summary-card deposit">
            <div class="summary-label">Total Deposit ({{ $summary['deposit_count'] }} SO)</div>
            <div class="summary-value">Rp {{ number_format($summary['deposit_total'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card aktual">
            <div class="summary-label">Total Aktual ({{ $summary['aktual_count'] }} SO)</div>
            <div class="summary-value">Rp {{ number_format($summary['aktual_total'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card po">
            <div class="summary-label">Total PO</div>
            <div class="summary-value">Rp {{ number_format($summary['po_total'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card profit {{ $summary['gross_profit'] >= 0 ? 'success' : 'danger' }}">
            <div class="summary-label">Estimasi Laba</div>
            <div class="summary-value">{{ number_format($summary['profit_margin'], 2) }}%</div>
        </div>
    </div>
    @else
    <div class="summary-grid">
        <div class="summary-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <div class="summary-label">Total Sales Order</div>
            <div class="summary-value">Rp {{ number_format($summary['so_total'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="summary-label">Total Purchase Order</div>
            <div class="summary-value">Rp {{ number_format($summary['po_total'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card {{ $summary['gross_profit'] >= 0 ? 'success' : 'danger' }}">
            <div class="summary-label">Estimasi Laba</div>
            <div class="summary-value">Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card {{ $summary['profit_margin'] >= 0 ? 'success' : 'danger' }}">
            <div class="summary-label">Margin Laba</div>
            <div class="summary-value">{{ number_format($summary['profit_margin'], 2) }}%</div>
        </div>
    </div>
    @endif

    <!-- Current SO Info -->
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-clipboard-document-list class="icon-sm" />
                Informasi Pesanan Penjualan (Saat Ini)
                <span
                    class="badge badge-{{ $record->order_type === 'deposit' ? 'deposit' : ($record->order_type === 'aktual' ? 'aktual' : 'standar') }}">
                    {{ ucfirst($record->order_type) }}
                </span>
            </div>
            <span class="badge badge-{{ $record->status === 'posted' ? 'success' : 'warning' }}">
                {{ $record->status === 'posted' ? 'Posted' : 'Draft' }}
            </span>
        </div>
        <div class="detail-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">No. Pesanan</span>
                    <span class="info-value font-mono">{{ $record->order_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value">{{ $record->date?->format('d M Y') ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">No. Pekerjaan</span>
                    <span class="info-value">
                        @if($record->job_number)
                        <span class="font-mono text-primary">{{ $record->job_number }}</span>
                        @elseif($record->job)
                        <span class="font-mono text-primary">{{ $record->job->job_number }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Pelanggan</span>
                    <span class="info-value">{{ $record->customer?->name ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total</span>
                    <span class="info-value font-mono">Rp {{ number_format($record->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($record->reference_no)
                <div class="info-item">
                    <span class="info-label">No. Referensi</span>
                    <span class="info-value">{{ $record->reference_no }}</span>
                </div>
                @endif
                @if($record->client_po_number)
                <div class="info-item">
                    <span class="info-label">No. PO Klien</span>
                    <span class="info-value">{{ $record->client_po_number }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Deposit Orders -->
    @if($depositOrders->count() > 0)
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-currency-dollar class="icon-sm" />
                SO Deposit Terkait
                <span class="badge badge-deposit">{{ $depositOrders->count() }} Pesanan</span>
            </div>
        </div>
        <div class="detail-card-body">
            @foreach($depositOrders as $deposit)
            <div class="order-list-item">
                <div class="order-list-header">
                    <a href="{{ \App\Filament\Resources\SalesOrders\SalesOrderResource::getUrl('view-detail', ['record' => $deposit]) }}"
                        class="so-link-number link-primary">
                        {{ $deposit->order_number }}
                    </a>
                    <span class="badge badge-{{ $deposit->status === 'posted' ? 'success' : 'warning' }}">
                        {{ $deposit->status === 'posted' ? 'Posted' : 'Draft' }}
                    </span>
                </div>
                <div class="order-list-content">
                    <span><strong>Tanggal:</strong> {{ $deposit->date?->format('d M Y') ?? '-' }}</span>
                    <span><strong>Total:</strong> Rp {{ number_format($deposit->total_amount, 0, ',', '.') }}</span>
                    <span><strong>Item:</strong> {{ $deposit->items->count() }} item</span>
                    @if($deposit->reference_no)
                    <span><strong>Ref:</strong> {{ $deposit->reference_no }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Related Aktual Orders -->
    @if($aktualOrders->count() > 0)
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-check-circle class="icon-sm" />
                SO Aktual Terkait
                <span class="badge badge-aktual">{{ $aktualOrders->count() }} Pesanan</span>
            </div>
        </div>
        <div class="detail-card-body">
            @foreach($aktualOrders as $aktual)
            <div class="order-list-item">
                <div class="order-list-header">
                    <a href="{{ \App\Filament\Resources\SalesOrders\SalesOrderResource::getUrl('view-detail', ['record' => $aktual]) }}"
                        class="so-link-number link-primary">
                        {{ $aktual->order_number }}
                    </a>
                    <span class="badge badge-{{ $aktual->status === 'posted' ? 'success' : 'warning' }}">
                        {{ $aktual->status === 'posted' ? 'Posted' : 'Draft' }}
                    </span>
                </div>
                <div class="order-list-content">
                    <span><strong>Tanggal:</strong> {{ $aktual->date?->format('d M Y') ?? '-' }}</span>
                    <span><strong>Total:</strong> Rp {{ number_format($aktual->total_amount, 0, ',', '.') }}</span>
                    <span><strong>Item:</strong> {{ $aktual->items->count() }} item</span>
                    @if($aktual->reference_no)
                    <span><strong>Ref:</strong> {{ $aktual->reference_no }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- All Related SO Items Combined -->
    @if($isLinked)
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-list-bullet class="icon-sm" />
                Semua Item Sales Order
            </div>
        </div>
        <div class="detail-card-body">
            @php
            $allSoItems = $allRelatedOrders->flatMap(function($order) {
            return $order->items->map(function($item) use ($order) {
            $item->so_order_number = $order->order_number;
            $item->so_order_type = $order->order_type;
            $item->so_id = $order->id;
            return $item;
            });
            });
            @endphp

            @if($allSoItems->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. SO</th>
                            <th>Tipe</th>
                            <th>Produk</th>
                            <th>Deskripsi</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">Harga Satuan</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allSoItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ \App\Filament\Resources\SalesOrders\SalesOrderResource::getUrl('view-detail', ['record' => $item->so_id]) }}"
                                    class="font-mono link-primary">
                                    {{ $item->so_order_number }}
                                </a>
                            </td>
                            <td>
                                <span
                                    class="badge badge-{{ $item->so_order_type === 'deposit' ? 'deposit' : ($item->so_order_type === 'aktual' ? 'aktual' : 'standar') }}">
                                    {{ ucfirst($item->so_order_type) }}
                                </span>
                            </td>
                            <td>{{ $item->item_name ?? $item->product?->name ?? '-' }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td class="text-right font-mono">
                                {{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit?->name ?? '' }}
                            </td>
                            <td class="text-right font-mono">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-right font-mono">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f3f4f6; font-weight: 600;">
                            <td colspan="7" class="text-right">TOTAL SEMUA SO</td>
                            <td class="text-right font-mono">Rp {{ number_format($allSoItems->sum('total'), 0, ',', '.')
                                }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="empty-state">
                <x-heroicon-o-inbox class="icon-gray" />
                <p>Tidak ada item dalam pesanan ini</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Purchase Orders Section -->
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-shopping-cart class="icon-sm" />
                Pesanan Pembelian
                <span class="badge badge-gray">{{ $allPurchaseOrders->count() }}</span>
            </div>
        </div>
        <div class="detail-card-body">
            @if($allPurchaseOrders->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th class="text-right">Total Item</th>
                            <th class="text-right">Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPurchaseOrders as $po)
                        <tr>
                            <td>
                                <a href="{{ \App\Filament\Resources\PurchaseOrders\PurchaseOrderResource::getUrl('view', ['record' => $po]) }}"
                                    class="font-mono link-primary">
                                    {{ $po->purchase_order_no }}
                                </a>
                            </td>
                            <td>{{ $po->date?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $po->supplier?->name ?? '-' }}</td>
                            <td class="text-right">{{ $po->items->count() }} item</td>
                            <td class="text-right font-mono">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($po->status === 'posted')
                                <span class="badge badge-success">Posted</span>
                                @else
                                <span class="badge badge-warning">Draft</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="po-item-row">
                            <td colspan="6" style="padding: 8px 16px;">
                                <div class="text-sm">
                                    <strong>Item:</strong>
                                    @foreach($po->items as $item)
                                    <span class="inline-block mr-4">
                                        • {{ $item->product?->name ?? $item->description ?? '-' }}
                                        ({{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit?->name ?? ''
                                        }}
                                        × Rp {{ number_format($item->unit_price, 0, ',', '.') }})
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <x-heroicon-o-shopping-cart class="icon-gray" />
                <p>Belum ada Pesanan Pembelian</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Sales Invoices Section -->
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-document-text class="icon-sm" />
                Invoice
                <span class="badge badge-gray">{{ $allInvoices->count() }}</span>
            </div>
        </div>
        <div class="detail-card-body">
            @if($allInvoices->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Dibayar</th>
                            <th class="text-right">Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allInvoices as $invoice)
                        <tr>
                            <td>
                                <a href="{{ \App\Filament\Resources\SalesInvoices\SalesInvoiceResource::getUrl('view', ['record' => $invoice]) }}"
                                    class="font-mono link-primary">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td>{{ $invoice->date?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $invoice->due_date?->format('d M Y') ?? '-' }}</td>
                            <td class="text-right font-mono">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="text-right font-mono">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}
                            </td>
                            <td
                                class="text-right font-mono {{ $invoice->outstanding_amount > 0 ? 'text-danger' : 'text-success' }}">
                                Rp {{ number_format($invoice->outstanding_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($invoice->is_paid)
                                <span class="badge badge-success">Lunas</span>
                                @elseif($invoice->outstanding_amount > 0)
                                <span class="badge badge-warning">Belum Lunas</span>
                                @else
                                <span class="badge badge-gray">{{ $invoice->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f3f4f6; font-weight: 600;">
                            <td colspan="3" class="text-right">TOTAL</td>
                            <td class="text-right font-mono">Rp {{ number_format($allInvoices->sum('total_amount'), 0,
                                ',', '.') }}</td>
                            <td class="text-right font-mono">Rp {{ number_format($allInvoices->sum('paid_amount'), 0,
                                ',', '.') }}</td>
                            <td class="text-right font-mono">Rp {{
                                number_format($allInvoices->sum('outstanding_amount'), 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="empty-state">
                <x-heroicon-o-document-text class="icon-gray" />
                <p>Belum ada Invoice</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Current SO Items Detail -->
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-list-bullet class="icon-sm" />
                Detail Item Sales Order (Saat Ini)
            </div>
        </div>
        <div class="detail-card-body">
            @if($record->items->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Deskripsi</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">Harga Satuan</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Tertagih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($record->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->item_name ?? $item->product?->name ?? '-' }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td class="text-right font-mono">
                                {{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit?->name ?? '' }}
                            </td>
                            <td class="text-right font-mono">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-right font-mono">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            <td class="text-right font-mono">
                                {{ number_format($item->invoiced_quantity, 0, ',', '.') }} {{ $item->unit?->name ?? ''
                                }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <x-heroicon-o-inbox class="icon-gray" />
                <p>Tidak ada item dalam pesanan ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Price Comparison Section (Aktual vs PO) -->
    @if($aktualOrders->count() > 0 && count($comparisonData) > 0)
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-scale class="icon-sm" />
                Perbandingan Harga (Aktual vs PO)
            </div>
        </div>
        <div class="detail-card-body">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. SO</th>
                            <th>Produk</th>
                            <th class="text-right">Jml Aktual</th>
                            <th class="text-right">Harga Aktual</th>
                            <th class="text-right">Jml PO</th>
                            <th class="text-right">Rata-rata Harga PO</th>
                            <th class="text-right">Margin</th>
                            <th class="text-right">Margin %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comparisonData as $data)
                        <tr class="{{ $data['margin'] >= 0 ? 'comparison-positive' : 'comparison-negative' }}">
                            <td class="font-mono">{{ $data['so_order_number'] }}</td>
                            <td>
                                <strong>{{ $data['so_item']->item_name ?? $data['so_item']->product?->name ??
                                    $data['so_item']->description ?? '-' }}</strong>
                                @if($data['so_item']->description && $data['so_item']->item_name ??
                                $data['so_item']->product?->name && $data['so_item']->description !==
                                ($data['so_item']->item_name ?? $data['so_item']->product?->name))
                                <div class="text-muted text-sm">{{ $data['so_item']->description }}</div>
                                @endif
                            </td>
                            <td class="text-right font-mono">
                                {{ number_format($data['so_item']->quantity, 0, ',', '.') }} {{
                                $data['so_item']->unit?->name ?? '' }}
                            </td>
                            <td class="text-right font-mono">
                                Rp {{ number_format($data['so_price'], 0, ',', '.') }}
                            </td>
                            <td class="text-right font-mono">
                                {{ $data['total_po_qty'] > 0 ? number_format($data['total_po_qty'], 0, ',', '.') : '-'
                                }}
                            </td>
                            <td class="text-right font-mono">
                                @if($data['avg_po_price'] > 0)
                                Rp {{ number_format($data['avg_po_price'], 0, ',', '.') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td
                                class="text-right font-mono {{ $data['margin'] >= 0 ? 'text-success' : 'text-danger' }}">
                                @if($data['avg_po_price'] > 0)
                                Rp {{ number_format($data['margin'], 0, ',', '.') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td
                                class="text-right font-mono {{ $data['margin'] >= 0 ? 'text-success' : 'text-danger' }}">
                                @if($data['avg_po_price'] > 0)
                                {{ number_format($data['margin_percent'], 2) }}%
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f3f4f6; font-weight: 600;">
                            <td colspan="3" class="text-right">TOTAL</td>
                            <td class="text-right font-mono">Rp {{ number_format($summary['aktual_total'], 0, ',', '.')
                                }}</td>
                            <td class="text-right font-mono">{{
                                number_format(collect($comparisonData)->sum('total_po_qty'), 0, ',', '.') }}</td>
                            <td class="text-right font-mono">Rp {{
                                number_format(collect($comparisonData)->sum('total_po_amount'), 0, ',', '.') }}</td>
                            <td
                                class="text-right font-mono {{ $summary['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}
                            </td>
                            <td
                                class="text-right font-mono {{ $summary['profit_margin'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($summary['profit_margin'], 2) }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Price Comparison Section (SO vs PO with Gap Analysis) -->
    @if(count($allItemsComparison) > 0)
    <div class="detail-card">
        <div class="detail-card-header">
            <div class="detail-card-title">
                <x-heroicon-o-chart-bar class="icon-sm" />
                Perbandingan Harga (SO vs PO)
            </div>
        </div>
        <div class="detail-card-body">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th class="text-right">Harga SO (Rata-rata)</th>
                            <th class="text-right">Harga PO (Rata-rata)</th>
                            <th class="text-right">Selisih</th>
                            <th class="text-right">Selisih %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allItemsComparison as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item['product']?->name ?? '-' }}</strong>
                                @if($item['so_order_numbers'])
                                <div class="text-muted text-sm">SO: {{ implode(', ', $item['so_order_numbers']) }}</div>
                                @endif
                            </td>
                            <td class="text-right font-mono" style="color: #2563eb;">
                                Rp {{ number_format($item['so_avg_price'], 0, ',', '.') }}
                            </td>
                            <td class="text-right font-mono" style="color: #7c3aed;">
                                @if($item['has_po'])
                                Rp {{ number_format($item['po_avg_price'], 0, ',', '.') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td
                                class="text-right font-mono {{ $item['gap_amount'] >= 0 ? 'text-success' : 'text-danger' }}">
                                @if($item['has_po'])
                                {{ $item['gap_amount'] >= 0 ? '+' : '' }}Rp {{ number_format($item['gap_amount'], 0,
                                ',', '.') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td
                                class="text-right font-mono {{ $item['gap_percent'] >= 0 ? 'text-success' : 'text-danger' }}">
                                @if($item['has_po'])
                                {{ $item['gap_percent'] >= 0 ? '+' : '' }}{{ number_format($item['gap_percent'], 2) }}%
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f3f4f6; font-weight: 600;">
                            <td colspan="2" class="text-right">TOTAL</td>
                            <td class="text-right font-mono" style="color: #2563eb;">
                                Rp {{ number_format(collect($allItemsComparison)->sum('so_total_amount') /
                                max(collect($allItemsComparison)->sum('so_qty'), 1), 0, ',', '.') }}
                            </td>
                            <td class="text-right font-mono" style="color: #7c3aed;">
                                @if(collect($allItemsComparison)->sum('po_qty') > 0)
                                Rp {{ number_format(collect($allItemsComparison)->sum('po_total_amount') /
                                collect($allItemsComparison)->sum('po_qty'), 0, ',', '.') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td
                                class="text-right font-mono {{ collect($allItemsComparison)->sum('gap_total_amount') >= 0 ? 'text-success' : 'text-danger' }}">
                                @if(collect($allItemsComparison)->sum('po_qty') > 0)
                                {{ collect($allItemsComparison)->sum('gap_total_amount') >= 0 ? '+' : '' }}Rp {{
                                number_format(collect($allItemsComparison)->sum('gap_total_amount') /
                                max(collect($allItemsComparison)->sum('so_qty'), 1), 0, ',', '.') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif
</x-filament-panels::page>