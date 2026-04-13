<x-filament-panels::page>
    <style>
        .report-header-controls {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
            gap: 1.5rem;
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        .report-form {
            flex: 1;
            max-width: 600px;
        }

        .report-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .report-header-controls {
                flex-direction: column;
                align-items: stretch;
            }
            .report-form {
                max-width: none;
            }
            .report-actions {
                justify-content: flex-start;
                padding-top: 1rem;
            }
        }

        /* ── Report body ─────────────────────────────────────────── */
        .report-page {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            font-family: sans-serif;
            color: #333;
            overflow-x: auto;
        }

        .report-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .report-company-name {
            font-size: 1.1rem;
            font-weight: bold;
            text-transform: uppercase;
            color: #1f2937;
            margin: 0 0 0.25rem;
        }

        .report-title {
            font-size: 1.35rem;
            font-weight: bold;
            color: #991b1b;
            margin: 0.25rem 0;
        }

        .report-date {
            font-size: 0.875rem;
            font-weight: 600;
            color: #4b5563;
            margin: 0;
        }

        /* ── Table ───────────────────────────────────────────────── */
        .tb-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
            min-width: 600px;
        }

        .tb-table th,
        .tb-table td {
            padding: 0.6rem 0.8rem;
            white-space: nowrap;
        }

        .tb-table thead tr th {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            text-align: left;
            border: 1px solid #1e3a8a;
        }

        .tb-table thead tr th.right {
            text-align: right;
        }

        .tb-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .tb-table tbody tr:hover {
            background-color: #eff6ff !important;
        }

        .tb-table tbody td {
            border-bottom: 1px solid #e5e7eb;
        }

        .tb-table td.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .tb-table td.acc-name {
            color: #1f2937;
        }

        .error-box {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #6b7280;
        }

        @media print {
            .no-print {
                display: none;
            }

            .report-page {
                box-shadow: none;
                border: none;
                padding: 0;
            }

            body {
                background-color: white;
            }
        }
    </style>

    <div class="report-header-controls no-print">
        <div class="report-form">
            {{ $this->form }}
        </div>
        <div class="report-actions">
            <x-filament::button wire:click="filterReport" color="primary" icon="heroicon-m-funnel">
                Terapkan Filter
            </x-filament::button>
            <x-filament::button wire:click="downloadPdf" color="success" icon="heroicon-o-arrow-down-tray">
                Unduh PDF
            </x-filament::button>
        </div>
    </div>

    @if(session('selected_company_id') === 'all' || !session('selected_company_id'))
    <div class="error-box">
        <div
            style="width: 80px; height: 80px; background: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">Pilih Perusahaan</h3>
        <p>Silakan pilih perusahaan tertentu dari pemilih perusahaan untuk melihat Laporan Saldo Akun.</p>
    </div>
    @else
    @php
    $company = \App\Models\Company::find(session('selected_company_id'));
    $date = $this->data['date'] ?? now()->format('Y-m-d');
    @endphp

    <div class="report-page">
        <div class="report-header">
            <h2 class="report-company-name">{{ $company->name ?? '' }}</h2>
            <h1 class="report-title">Laporan Saldo Akun</h1>
            <p class="report-date">Per Tgl. {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
        </div>

        <div class="accounts-search no-print" style="margin-bottom: 1rem;">
            <div style="position: relative; display: flex; align-items: center; max-width: 400px;">
                <svg style="position: absolute; left: 0.75rem; width: 1.25rem; height: 1.25rem; color: #9ca3af; pointer-events: none;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text"
                    style="width: 100%; padding: 0.5rem 1rem 0.5rem 2.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;"
                    placeholder="Cari akun..." wire:model.live.debounce.300ms="search">
                @if(!empty($search))
                <button
                    style="position: absolute; right: 0.5rem; color: #6b7280; background: none; border: none; cursor: pointer;"
                    wire:click="$set('search', '')">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                @endif
            </div>
        </div>

        <table class="tb-table">
            <thead>
                <tr>
                    <th style="width: 120px;">Account number</th>
                    <th>Nama Akun</th>
                    <th style="width: 150px;">Tipe Akun</th>
                    <th class="right" style="width: 150px;">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->getAccounts() as $account)
                @include('filament.pages.reports.partials.account-balances-tree-item', ['account' => $account, 'level'
                => 0])
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-filament-panels::page>