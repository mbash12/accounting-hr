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
            margin-bottom: 1.5rem;
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
            min-width: 900px;
        }

        .tb-table th,
        .tb-table td {
            padding: 0.4rem 0.6rem;
            white-space: nowrap;
        }

        .tb-table thead tr:first-child th {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            text-align: center;
            border: 1px solid #1e3a8a;
        }

        .tb-table thead tr:last-child th {
            background-color: #eff6ff;
            color: #1e3a8a;
            font-weight: bold;
            text-align: right;
            border: 1px solid #d1d5db;
        }

        .tb-table thead tr:last-child th:first-child,
        .tb-table thead tr:last-child th:nth-child(2) {
            text-align: left;
        }

        .tb-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .tb-table tbody tr:hover {
            background-color: #eff6ff;
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

        /* ── Totals row ──────────────────────────────────────────── */
        .tb-table tfoot td {
            font-weight: bold;
            border-top: 2px solid #1e3a8a;
            border-bottom: none;
            background-color: #eff6ff;
            color: #1e3a8a;
            text-align: right;
        }

        .tb-table tfoot td:first-child,
        .tb-table tfoot td:nth-child(2) {
            text-align: left;
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

    {{-- ── Filter Bar ──────────────────────────────────────────────── --}}
    <div class="report-header-controls no-print">
        <div class="report-form">
            {{ $this->form }}
        </div>
        <div class="report-actions">
            <x-filament::button wire:click="filterReport" color="primary" icon="heroicon-m-funnel">
                Apply Filter
            </x-filament::button>
            <x-filament::button wire:click="downloadPdf" color="success" icon="heroicon-o-arrow-down-tray">
                Download PDF
            </x-filament::button>
        </div>
    </div>

    {{-- ── Report Body ─────────────────────────────────────────────── --}}
    @php $reportData = $this->getReportData(); @endphp

    @if(isset($reportData['error']))
    <div class="error-box">
        <div
            style="width: 80px; height: 80px; background: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">Select Company</h3>
        <p>{{ $reportData['error'] }}</p>
    </div>
    @elseif($reportData['company'])
    @php
    $rows = $reportData['rows'];
    $company = $reportData['company'];
    $startDate = $reportData['start_date'];
    $endDate = $reportData['end_date'];

    $totOpenDebit = $rows->sum('open_debit');
    $totOpenCredit = $rows->sum('open_credit');
    $totPeriodDebit = $rows->sum('period_debit');
    $totPeriodCredit= $rows->sum('period_credit');
    $totEndDebit = $rows->sum('end_debit');
    $totEndCredit = $rows->sum('end_credit');

    $fmt = fn($v) => $v != 0 ? number_format($v, 2, ',', '.') : '-';
    @endphp

    <div class="report-page">
        {{-- Header --}}
        <div class="report-header">
            <h2 class="report-company-name">{{ $company->name }}</h2>
            <h1 class="report-title">Trial Balance</h1>
            <p class="report-date">
                Dari {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>

        {{-- Table --}}
        <table class="tb-table">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align:left; width:80px;">Kode</th>
                    <th rowspan="2" style="text-align:left; min-width:200px;">Account Name</th>
                    <th colspan="2">Opening Balance</th>
                    <th colspan="2">Perubahan</th>
                    <th colspan="2">Closing Balance</th>
                </tr>
                <tr>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                <tr>
                    <td>{{ $row['code'] }}</td>
                    <td class="acc-name">{{ $row['name'] }}</td>
                    <td class="num">{{ $fmt($row['open_debit']) }}</td>
                    <td class="num">{{ $fmt($row['open_credit']) }}</td>
                    <td class="num">{{ $fmt($row['period_debit']) }}</td>
                    <td class="num">{{ $fmt($row['period_credit']) }}</td>
                    <td class="num">{{ $fmt($row['end_debit']) }}</td>
                    <td class="num">{{ $fmt($row['end_credit']) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:#9ca3af;">
                        Tidak ada data untuk periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total</td>
                    <td>{{ number_format($totOpenDebit, 2, ',', '.') }}</td>
                    <td>{{ number_format($totOpenCredit, 2, ',', '.') }}</td>
                    <td>{{ number_format($totPeriodDebit, 2, ',', '.') }}</td>
                    <td>{{ number_format($totPeriodCredit, 2, ',', '.') }}</td>
                    <td>{{ number_format($totEndDebit, 2, ',', '.') }}</td>
                    <td>{{ number_format($totEndCredit, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @else
    <div class="error-box">
        <div
            style="width: 80px; height: 80px; background: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">Select Company</h3>
        <p>Pilih perusahaan tertentu untuk melihat laporan.</p>
    </div>
    @endif
</x-filament-panels::page>