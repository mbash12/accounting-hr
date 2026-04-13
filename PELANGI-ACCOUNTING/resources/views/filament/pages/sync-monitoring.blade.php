<x-filament-panels::page>
    <style>
        .sync-monitoring-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1rem;
        }
        @media (min-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        .stat-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1rem;
            border: 1px solid #f3f4f6;
        }
        .stat-card-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
        }
        .stat-icon {
            padding: 0.75rem;
            border-radius: 0.5rem;
        }
        .stat-icon-blue { background: #eff6ff; }
        .stat-icon-blue svg { color: #2563eb; }
        .stat-icon-green { background: #f0fdf4; }
        .stat-icon-green svg { color: #16a34a; }
        .stat-icon-red { background: #fef2f2; }
        .stat-icon-red svg { color: #dc2626; }
        .stat-icon-yellow { background: #fefce8; }
        .stat-icon-yellow svg { color: #ca8a04; }
        .stat-icon-purple { background: #f3e8ff; }
        .stat-icon-purple svg { color: #9333ea; }
        
        .main-panel {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #f3f4f6;
        }
        .panel-header {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }
        .panel-header-content {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .filters-group {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
        }
        .search-box {
            position: relative;
        }
        .search-box svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.25rem;
            height: 1.25rem;
            color: #9ca3af;
        }
        .search-input {
            padding-left: 2.5rem;
            padding-right: 1rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            width: 16rem;
        }
        .search-input:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.2);
        }
        .filter-select {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background: white;
        }
        .filter-select:focus {
            outline: none;
            border-color: #0ea5e9;
        }
        .action-buttons {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-orange {
            background: #ea580c;
            color: white;
        }
        .btn-orange:hover:not(:disabled) {
            background: #c2410c;
        }
        .btn-gray {
            background: #f3f4f6;
            color: #374151;
        }
        .btn-gray:hover:not(:disabled) {
            background: #e5e7eb;
        }
        .btn-icon {
            width: 1rem;
            height: 1rem;
        }
        .btn-icon-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .data-table {
            width: 100%;
            font-size: 0.875rem;
            text-align: left;
            border-collapse: collapse;
        }
        .data-table th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table th.w-10 { width: 2.5rem; text-align: center; }
        .data-table th.text-center { text-align: center; }
        .data-table th.text-right { text-align: right; }
        .data-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .data-table tr:hover {
            background: #f9fafb;
        }
        .data-table td.text-center { text-align: center; }
        .data-table td.text-right { text-align: right; }
        
        .checkbox {
            width: 1rem;
            height: 1rem;
            accent-color: #0ea5e9;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        .badge-purple { background: #f3e8ff; color: #6b21a8; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        
        .text-muted { color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem; }
        .text-xs { font-size: 0.75rem; }
        .font-medium { font-weight: 500; }
        .font-bold { font-weight: 700; }
        .text-gray-900 { color: #111827; }
        .text-gray-500 { color: #6b7280; }
        .text-orange-600 { color: #ea580c; }
        .text-red-600 { color: #dc2626; }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.375rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            background: transparent;
        }
        .action-btn:hover { background: #f3f4f6; }
        .action-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .action-btn-orange { color: #ea580c; }
        .action-btn-orange:hover { background: #fff7ed; }
        .action-btn-red { color: #dc2626; }
        .action-btn-red:hover { background: #fef2f2; }
        .action-btn-gray { color: #4b5563; }
        .action-btn-gray:hover { background: #f3f4f6; }
        
        .empty-state, .loading-state {
            padding: 2rem;
            text-align: center;
            color: #6b7280;
        }
        .empty-state svg, .loading-state svg {
            width: 3rem;
            height: 3rem;
            margin: 0 auto 0.75rem;
            color: #d1d5db;
        }
        .loading-state svg {
            animation: spin 1s linear infinite;
            width: 2rem;
            height: 2rem;
        }
        
        .pagination-bar {
            padding: 1rem;
            border-top: 1px solid #f3f4f6;
        }
        .pagination-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .pagination-info {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .pagination-btn {
            padding: 0.375rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background: white;
            cursor: pointer;
        }
        .pagination-btn:hover:not(:disabled) {
            background: #f9fafb;
        }
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }
        .modal-content {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            max-width: 42rem;
            width: 100%;
            margin: 1rem;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
        }
        .modal-content-lg {
            max-width: 48rem;
        }
        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }
        .modal-close {
            color: #9ca3af;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
        }
        .modal-close:hover { color: #4b5563; }
        .modal-body {
            padding: 1rem;
            overflow: auto;
        }
        .modal-footer {
            padding: 1rem;
            border-top: 1px solid #f3f4f6;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        .error-box pre {
            font-size: 0.875rem;
            color: #991b1b;
            white-space: pre-wrap;
            margin: 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .info-item {
            background: #f9fafb;
            padding: 0.75rem;
            border-radius: 0.5rem;
        }
        .info-label {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-weight: 500;
            color: #111827;
        }
        
        .event-badge-created { background: #dbeafe; color: #1e40af; }
        .event-badge-deleted { background: #fee2e2; color: #991b1b; }
    </style>

    <div x-data="syncMonitoring()" x-init="init()" class="sync-monitoring-container">
        {{-- Stats Cards --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <p class="stat-label">Total Sync Jobs</p>
                        <p class="stat-value" x-text="stats.job_stats?.total || 0"></p>
                    </div>
                    <div class="stat-icon stat-icon-blue">
                        <x-heroicon-o-document-text style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <p class="stat-label">Tersinkronisasi</p>
                        <p class="stat-value" style="color: #16a34a;" x-text="stats.job_stats?.completed || 0"></p>
                    </div>
                    <div class="stat-icon stat-icon-green">
                        <x-heroicon-o-check-circle style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <p class="stat-label">Gagal</p>
                        <p class="stat-value" style="color: #dc2626;" x-text="stats.job_stats?.failed || 0"></p>
                    </div>
                    <div class="stat-icon stat-icon-red">
                        <x-heroicon-o-x-circle style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div>
                        <p class="stat-label">Posted / Deleted</p>
                        <p class="stat-value" style="color: #9333ea;">
                            <span x-text="stats.event_stats?.created?.total || 0"></span> / <span x-text="stats.event_stats?.deleted?.total || 0"></span>
                        </p>
                    </div>
                    <div class="stat-icon stat-icon-purple">
                        <x-heroicon-o-list-bullet style="width: 1.5rem; height: 1.5rem;" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters and Actions --}}
        <div class="main-panel">
            <div class="panel-header">
                <div class="panel-header-content">
                    <div class="filters-group">
                        {{-- Search --}}
                        <div class="search-box">
                            <x-heroicon-o-magnifying-glass />
                            <input 
                                type="text" 
                                x-model="filters.search" 
                                @input.debounce.500ms="loadData()"
                                placeholder="Cari invoice, job number, atau customer..."
                                class="search-input"
                            >
                        </div>

                        {{-- Sync Status Filter --}}
                        <select 
                            x-model="filters.sync_status" 
                            @change="loadData()"
                            class="filter-select"
                        >
                            <option value="">Semua Status Sync</option>
                            <option value="completed">Tersinkronisasi</option>
                            <option value="failed">Gagal</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                        </select>

                        {{-- Event Filter --}}
                        <select
                            x-model="filters.event"
                            @change="loadData()"
                            class="filter-select"
                        >
                            <option value="">Semua Event</option>
                            <option value="created">Posted (Created)</option>
                            <option value="deleted">Deleted</option>
                        </select>
                    </div>

                    <div class="action-buttons">
                        {{-- Bulk Retry Button --}}
                        <button
                            x-show="selectedItems.length > 0"
                            x-transition
                            @click="bulkRetry()"
                            :disabled="bulkRetrying"
                            class="btn btn-orange"
                        >
                            <x-heroicon-o-arrow-path x-show="!bulkRetrying" class="btn-icon" />
                            <x-heroicon-o-arrow-path x-show="bulkRetrying" class="btn-icon btn-icon-spin" />
                            <span x-text="bulkRetrying ? 'Mensinkronkan...' : `Retry Terpilih (${selectedItems.length})`"></span>
                        </button>

                        {{-- Clear Data Button --}}
                        <button
                            @click="clearOldData()"
                            :disabled="clearingData"
                            class="btn btn-orange"
                        >
                            <x-heroicon-o-trash x-show="!clearingData" class="btn-icon" />
                            <x-heroicon-o-trash x-show="clearingData" class="btn-icon btn-icon-spin" />
                            <span x-text="clearingData ? 'Membersihkan...' : 'Clear Data'"></span>
                        </button>

                        {{-- Refresh Button --}}
                        <button
                            @click="loadData()"
                            :disabled="loading"
                            class="btn btn-gray"
                        >
                            <x-heroicon-o-arrow-path x-show="!loading" class="btn-icon" />
                            <x-heroicon-o-arrow-path x-show="loading" class="btn-icon btn-icon-spin" />
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-10">
                                <input 
                                    type="checkbox" 
                                    @change="toggleSelectAll()"
                                    :checked="allSelected"
                                    :indeterminate="selectedItems.length > 0 && !allSelected"
                                    class="checkbox"
                                >
                            </th>
                            <th>Invoice</th>
                            <th>Event</th>
                            <th>Customer</th>
                            <th>Job Number</th>
                            <th class="text-center">Status Sync</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-center">Retry</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in items" :key="item.id">
                            <tr>
                                <td class="text-center">
                                    <input 
                                        type="checkbox" 
                                        :value="item.id"
                                        x-model="selectedItems"
                                        class="checkbox"
                                    >
                                </td>
                                <td>
                                    <div class="font-medium text-gray-900" x-text="item.invoice_number"></div>
                                    <div class="text-muted" x-text="formatDate(item.invoice_date)"></div>
                                </td>
                                <td>
                                    <span
                                        class="badge"
                                        :class="item.event === 'deleted' ? 'event-badge-deleted' : 'event-badge-created'"
                                        x-text="getEventLabel(item.event)"
                                    ></span>
                                </td>
                                <td>
                                    <div class="text-gray-900" x-text="item.customer_name || '-'"></div>
                                </td>
                                <td>
                                    <div class="font-medium text-gray-900" x-text="item.job_number || '-'"></div>
                                </td>
                                <td class="text-center">
                                    <span 
                                        class="badge"
                                        :class="getSyncStatusClass(item.sync_status)"
                                        x-text="getSyncStatusLabel(item.sync_status)"
                                    ></span>
                                    <div x-show="item.error_message" class="text-muted" style="color: #dc2626;">
                                        <span x-text="truncateError(item.error_message)"></span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="font-medium text-gray-900" x-text="formatCurrency(item.total_amount)"></div>
                                </td>
                                <td class="text-center text-muted">
                                    <div x-text="formatDateTime(item.sync_created_at)"></div>
                                    <div x-show="item.execution_time" x-text="item.execution_time" style="font-size: 0.7rem; color: #9ca3af;"></div>
                                </td>
                                <td class="text-center">
                                    <span x-text="item.retry_count || '0'"></span>
                                </td>
                                <td class="text-center">
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                                        <button 
                                            @click="retrySync(item.id)"
                                            :disabled="retrying[item.id] || !item.can_retry"
                                            class="action-btn action-btn-orange"
                                            title="Retry Sync"
                                        >
                                            <x-heroicon-o-arrow-path x-show="!retrying[item.id]" style="width: 1rem; height: 1rem;" />
                                            <x-heroicon-o-arrow-path x-show="retrying[item.id]" class="btn-icon-spin" style="width: 1rem; height: 1rem;" />
                                        </button>
                                        <button 
                                            x-show="item.error_message"
                                            @click="showError(item)"
                                            class="action-btn action-btn-red"
                                            title="Lihat Error"
                                        >
                                            <x-heroicon-o-eye style="width: 1rem; height: 1rem;" />
                                        </button>
                                        <button 
                                            @click="viewDetails(item.id)"
                                            class="action-btn action-btn-gray"
                                            title="Detail"
                                        >
                                            <x-heroicon-o-information-circle style="width: 1rem; height: 1rem;" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                {{-- Empty State --}}
                <div x-show="!loading && items.length === 0" class="empty-state">
                    <x-heroicon-o-inbox />
                    <p>Tidak ada data ditemukan</p>
                </div>

                {{-- Loading State --}}
                <div x-show="loading" class="loading-state">
                    <x-heroicon-o-arrow-path />
                    <p>Memuat data...</p>
                </div>
            </div>

            {{-- Pagination --}}
            <div x-show="meta.total > 0" class="pagination-bar">
                <div class="pagination-content">
                    <div class="pagination-info">
                        Menampilkan <span x-text="items.length"></span> dari <span x-text="meta.total"></span> item
                    </div>
                    <div class="pagination-controls">
                        <button 
                            @click="prevPage()"
                            :disabled="meta.current_page === 1 || loading"
                            class="pagination-btn"
                        >
                            Sebelumnya
                        </button>
                        <span style="font-size: 0.875rem; color: #4b5563;">
                            Halaman <span x-text="meta.current_page"></span>
                        </span>
                        <button 
                            @click="nextPage()"
                            :disabled="meta.current_page * meta.per_page >= meta.total || loading"
                            class="pagination-btn"
                        >
                            Berikutnya
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Error Modal --}}
        <div 
            x-show="errorModal.show" 
            x-transition
            class="modal-overlay"
            @click.self="errorModal.show = false"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Detail Error</h3>
                    <button @click="errorModal.show = false" class="modal-close">
                        <x-heroicon-o-x-mark style="width: 1.5rem; height: 1.5rem;" />
                    </button>
                </div>
                <div class="modal-body">
                    <div class="error-box">
                        <pre x-text="errorModal.content"></pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button 
                        @click="errorModal.show = false"
                        class="btn btn-gray"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- Detail Modal --}}
        <div
            x-show="detailModal.show"
            x-transition
            class="modal-overlay"
            @click.self="detailModal.show = false"
        >
            <div class="modal-content modal-content-lg">
                <div class="modal-header">
                    <h3 class="modal-title">Detail Sinkronisasi</h3>
                    <button @click="detailModal.show = false" class="modal-close">
                        <x-heroicon-o-x-mark style="width: 1.5rem; height: 1.5rem;" />
                    </button>
                </div>
                <div class="modal-body">
                    <template x-if="detailModal.data">
                        <div>
                            {{-- Sync Job Info --}}
                            <div class="info-grid">
                                <div class="info-item">
                                    <p class="info-label">Sync Job ID</p>
                                    <p class="info-value" x-text="detailModal.data.id"></p>
                                </div>
                                <div class="info-item">
                                    <p class="info-label">Event</p>
                                    <p class="info-value">
                                        <span
                                            class="badge"
                                            :class="detailModal.data.event === 'deleted' ? 'event-badge-deleted' : 'event-badge-created'"
                                            x-text="getEventLabel(detailModal.data.event)"
                                        ></span>
                                    </p>
                                </div>
                                <div class="info-item">
                                    <p class="info-label">Status</p>
                                    <p class="info-value">
                                        <span
                                            class="badge"
                                            :class="getSyncStatusClass(detailModal.data.status)"
                                            x-text="detailModal.data.status?.toUpperCase()"
                                        ></span>
                                    </p>
                                </div>
                                <div class="info-item">
                                    <p class="info-label">Execution Time</p>
                                    <p class="info-value" x-text="detailModal.data.execution_time || '-'"></p>
                                </div>
                            </div>

                            {{-- Invoice Info --}}
                            <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.75rem;">Invoice Info</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <p class="info-label">Invoice Number</p>
                                    <p class="info-value" x-text="detailModal.data.invoice_number"></p>
                                </div>
                                <div class="info-item">
                                    <p class="info-label">Customer</p>
                                    <p class="info-value" x-text="detailModal.data.customer_name || '-'"></p>
                                </div>
                                <div class="info-item">
                                    <p class="info-label">Job Number</p>
                                    <p class="info-value" x-text="detailModal.data.job_number || '-'"></p>
                                </div>
                                <div class="info-item">
                                    <p class="info-label">Total Amount</p>
                                    <p class="info-value" x-text="formatCurrency(detailModal.data.total_amount)"></p>
                                </div>
                            </div>

                            {{-- Error Message --}}
                            <div x-show="detailModal.data.error_message" style="margin-top: 1rem;">
                                <h4 style="font-size: 0.875rem; font-weight: 600; color: #dc2626; margin-bottom: 0.5rem;">Error Message</h4>
                                <div class="error-box">
                                    <pre x-text="detailModal.data.error_message"></pre>
                                </div>
                            </div>

                            {{-- Payload --}}
                            <div x-show="detailModal.data.payload" style="margin-top: 1rem;">
                                <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Payload</h4>
                                <pre style="background: #f9fafb; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; overflow: auto;" x-text="JSON.stringify(detailModal.data.payload, null, 2)"></pre>
                            </div>

                            {{-- Result --}}
                            <div x-show="detailModal.data.result" style="margin-top: 1rem;">
                                <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Response</h4>
                                <pre style="background: #f0fdf4; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; overflow: auto;" x-text="JSON.stringify(detailModal.data.result, null, 2)"></pre>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="modal-footer">
                    <button
                        @click="detailModal.show = false"
                        class="btn btn-gray"
                    >
                        Tutup
                    </button>
                    <button
                        x-show="detailModal.data?.can_retry"
                        @click="retrySync(detailModal.data.id); detailModal.show = false"
                        class="btn btn-orange"
                    >
                        Retry Sync
                    </button>
                </div>
            </div>
        </div>

        {{-- Clear Data Modal --}}
        <div
            x-show="clearDataModal.show"
            x-transition
            class="modal-overlay"
            @click.self="clearDataModal.show = false"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Clear Old Data</h3>
                    <button @click="clearDataModal.show = false" class="modal-close">
                        <x-heroicon-o-x-mark style="width: 1.5rem; height: 1.5rem;" />
                    </button>
                </div>
                <div class="modal-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <p class="info-label">Hari Data Lama</p>
                            <input
                                type="number"
                                min="1"
                                max="365"
                                x-model="clearDataModal.days"
                                class="filter-select"
                                style="width: 100%;"
                                placeholder="Jumlah hari (default: 7)"
                            />
                        </div>
                    </div>
                    <p style="margin-top: 1rem; color: #6b7280; font-size: 0.875rem;">
                        Data sync yang sudah selesai dan lebih lama dari jumlah hari yang ditentukan akan dihapus.
                        Ini hanya akan menghapus data sync yang statusnya sudah selesai.
                    </p>
                    <div class="error-box" style="margin-top: 1rem;">
                        <p style="color: #991b1b; font-weight: 500; margin: 0;">Operasi ini tidak bisa dibatalkan. Data yang dihapus tidak dapat dipulihkan.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        @click="clearDataModal.show = false"
                        class="btn btn-gray"
                    >
                        Batal
                    </button>
                    <button
                        @click="performClearData()"
                        :disabled="clearingData"
                        class="btn btn-orange"
                    >
                        <x-heroicon-o-trash x-show="!clearingData" class="btn-icon" />
                        <x-heroicon-o-trash x-show="clearingData" class="btn-icon btn-icon-spin" />
                        <span x-text="clearingData ? 'Menghapus...' : 'Hapus Data'"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function syncMonitoring() {
            return {
                items: [],
                meta: { current_page: 1, total: 0, per_page: 20 },
                filters: {
                    sync_status: '',
                    event: '',
                    search: ''
                },
                loading: false,
                retrying: {},
                selectedItems: [],
                bulkRetrying: false,
                clearingData: false,
                stats: {},
                errorModal: { show: false, content: '' },
                detailModal: { show: false, data: null },
                clearDataModal: { show: false, days: 7 },

                get allSelected() {
                    return this.items.length > 0 && this.selectedItems.length === this.items.length;
                },

                init() {
                    this.loadData();
                    this.loadStats();
                },

                async loadData() {
                    this.loading = true;
                    try {
                        const params = new URLSearchParams({
                            page: this.meta.current_page,
                            per_page: this.meta.per_page,
                            ...this.filters
                        });
                        
                        const response = await fetch(`/internal/invoice-sync?${params}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.code === 200) {
                            this.items = data.data;
                            this.meta = data.meta;
                            this.selectedItems = [];
                        }
                    } catch (error) {
                        console.error('Failed to load data:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async loadStats() {
                    try {
                        const response = await fetch('/internal/invoice-sync/stats', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.code === 200) {
                            this.stats = data.data;
                        }
                    } catch (error) {
                        console.error('Failed to load stats:', error);
                    }
                },

                async retrySync(id) {
                    this.retrying[id] = true;
                    try {
                        const response = await fetch(`/internal/invoice-sync/${id}/retry`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.code === 200) {
                            this.$dispatch('notify', {
                                type: 'success',
                                message: data.message
                            });
                        } else {
                            this.$dispatch('notify', {
                                type: 'danger',
                                message: data.message || 'Gagal melakukan retry'
                            });
                        }
                        
                        await this.loadData();
                        await this.loadStats();
                    } catch (error) {
                        this.$dispatch('notify', {
                            type: 'danger',
                            message: 'Gagal melakukan retry: ' + error.message
                        });
                    } finally {
                        this.retrying[id] = false;
                    }
                },

                async bulkRetry() {
                    if (this.selectedItems.length === 0) return;
                    
                    if (!confirm(`Yakin ingin melakukan retry untuk ${this.selectedItems.length} item?`)) {
                        return;
                    }

                    this.bulkRetrying = true;
                    try {
                        const response = await fetch('/internal/invoice-sync/bulk-retry', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({ sync_job_ids: this.selectedItems })
                        });
                        
                        const data = await response.json();
                        
                        this.$dispatch('notify', {
                            type: data.code === 200 ? 'success' : 'warning',
                            message: data.message
                        });
                        
                        this.selectedItems = [];
                        await this.loadData();
                        await this.loadStats();
                    } catch (error) {
                        this.$dispatch('notify', {
                            type: 'danger',
                            message: 'Gagal melakukan bulk retry: ' + error.message
                        });
                    } finally {
                        this.bulkRetrying = false;
                    }
                },

                async viewDetails(id) {
                    try {
                        const response = await fetch(`/internal/invoice-sync/${id}/status`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.code === 200) {
                            this.detailModal.data = data.data;
                            this.detailModal.show = true;
                        }
                    } catch (error) {
                        console.error('Failed to load details:', error);
                    }
                },

                showError(item) {
                    this.errorModal.content = item.error_message || 'Tidak ada detail error';
                    this.errorModal.show = true;
                },

                toggleSelectAll() {
                    if (this.allSelected) {
                        this.selectedItems = [];
                    } else {
                        this.selectedItems = this.items.map(item => item.id);
                    }
                },

                prevPage() {
                    if (this.meta.current_page > 1) {
                        this.meta.current_page--;
                        this.loadData();
                    }
                },

                nextPage() {
                    if (this.meta.current_page * this.meta.per_page < this.meta.total) {
                        this.meta.current_page++;
                        this.loadData();
                    }
                },

                getSyncStatusClass(status) {
                    const classes = {
                        completed: 'badge-green',
                        failed: 'badge-red',
                        pending: 'badge-yellow',
                        processing: 'badge-blue',
                        retrying: 'badge-orange',
                    };
                    return classes[status] || 'badge-gray';
                },

                getSyncStatusLabel(status) {
                    const labels = {
                        completed: 'SUKSES',
                        failed: 'GAGAL',
                        pending: 'PENDING',
                        processing: 'PROSES',
                        retrying: 'RETRY',
                    };
                    return labels[status] || status?.toUpperCase() || 'UNKNOWN';
                },

                getEventLabel(event) {
                    const labels = {
                        created: 'POSTED',
                        deleted: 'DELETED',
                    };
                    return labels[event] || event?.toUpperCase() || 'UNKNOWN';
                },

                truncateError(error) {
                    if (!error) return '';
                    return error.length > 30 ? error.substring(0, 30) + '...' : error;
                },

                formatDate(date) {
                    if (!date) return '-';
                    // If already a date object, format it
                    if (date instanceof Date) {
                        return date.toLocaleDateString('id-ID');
                    }
                    // If it's a string (already formatted from DB), return as-is
                    return date;
                },

                formatDateTime(datetime) {
                    if (!datetime) return '-';
                    return new Date(datetime).toLocaleString('id-ID', { 
                        hour: '2-digit', 
                        minute: '2-digit',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },

                formatCurrency(amount) {
                    if (!amount) return 'Rp 0';
                    return 'Rp ' + parseFloat(amount).toLocaleString('id-ID');
                },

                async clearOldData() {
                    // Reset the days value to default when opening the modal
                    this.clearDataModal.days = 7;
                    this.clearDataModal.show = true;
                },

                async performClearData() {
                    const daysNum = parseInt(this.clearDataModal.days);
                    
                    if (isNaN(daysNum) || daysNum < 1 || daysNum > 365) {
                        this.$dispatch('notify', {
                            type: 'danger',
                            message: 'Harap masukkan angka antara 1 dan 365'
                        });
                        return;
                    }

                    this.clearingData = true;
                    this.clearDataModal.show = false; // Close the modal before performing the action
                    
                    try {
                        const response = await fetch('/internal/invoice-sync/clear-data', {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({ days: daysNum })
                        });

                        const data = await response.json();

                        this.$dispatch('notify', {
                            type: data.code === 200 ? 'success' : 'warning',
                            message: data.message
                        });

                        // Reload data and stats after clearing
                        await this.loadData();
                        await this.loadStats();
                    } catch (error) {
                        this.$dispatch('notify', {
                            type: 'danger',
                            message: 'Gagal membersihkan data: ' + error.message
                        });
                    } finally {
                        this.clearingData = false;
                    }
                }
            }
        }
    </script>
</x-filament-panels::page>
