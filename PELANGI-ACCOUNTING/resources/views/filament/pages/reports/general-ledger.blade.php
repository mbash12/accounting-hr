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
            max-width: 800px;
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

        .report-account-info {
            font-size: 1rem;
            font-weight: bold;
            color: #1f2937;
            text-align: left;
            margin-bottom: 1rem;
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

        .tb-table thead th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: 600;
            text-align: left;
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
        }

        .tb-table thead th:nth-child(5),
        .tb-table thead th:nth-child(6),
        .tb-table thead th:nth-child(7),
        .tb-table thead th:nth-child(8) {
             text-align: right;
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

        .tb-table td.center {
            text-align: center;
        }

        .tb-table td.desc-col {
            white-space: normal;
            min-width: 250px;
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
                Refresh
            </x-filament::button>
            <x-filament::button wire:click="downloadPdf" color="success" icon="heroicon-o-printer">
                Print
            </x-filament::button>
        </div>
    </div>

    {{-- ── Report Body ─────────────────────────────────────────────── --}}
    @php $reportData = $this->getReportData(); @endphp

    @if(isset($reportData['error']))
    <div class="error-box">
        <div style="width: 80px; height: 80px; background: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">Information</h3>
        <p>{{ $reportData['error'] }}</p>
    </div>
    @elseif($reportData['company'])
    @php
    $accountsData = $reportData['accounts_data'];
    $company = $reportData['company'];
    $startDate = $reportData['start_date'];
    $endDate = $reportData['end_date'];

    $fmt = fn($v) => $v != 0 ? number_format($v, 2, ',', '.') : '-';
    $priorDateStr = \Carbon\Carbon::parse($startDate)->subDay()->format('d M Y');
    $fmtBalance = fn($v) => number_format($v, 2, ',', '.');
    @endphp

    <div class="report-page">
        {{-- Header --}}
        <div class="report-header">
            <h2 class="report-company-name">{{ $company->name }}</h2>
            <h1 class="report-title">General Ledger</h1>
            <p class="report-date">
                From {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </p>
        </div>

        @forelse($accountsData as $accData)
        @php
            $account = $accData['account'];
            $rows = $accData['rows'];
            $openingBalance = $accData['opening_balance'];
        @endphp

        <div class="report-account-info" style="margin-top: 2rem; border-top: 2px dashed #e5e7eb; padding-top: 1.5rem;">
            Account: {{ $account->code }} - {{ $account->name }}
        </div>

        {{-- Table --}}
        <table class="tb-table" style="margin-bottom: 1rem;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Source No.</th>
                    <th>Check No.</th>
                    <th>Description</th>
                    <th>Receipt (Dr)</th>
                    <th>Payment (Cr)</th>
                    <th>Balance</th>
                    <th>Reconciled</th>
                </tr>
            </thead>
            <tbody>
                <!-- Opening Balance Row -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="desc-col">As of {{ $priorDateStr }}</td>
                    <td class="num"></td>
                    <td class="num"></td>
                    <td class="num">{{ $fmtBalance($openingBalance) }}</td>
                    <td class="center">-</td>
                </tr>

                @forelse($rows as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['source_no'] }}</td>
                    <td>{{ $row['check_no'] }}</td>
                    <td class="desc-col">{{ $row['description'] }}</td>
                    <td class="num">{{ $fmt($row['debit']) }}</td>
                    <td class="num">{{ $fmt($row['credit']) }}</td>
                    <td class="num">{{ $fmtBalance($row['balance']) }}</td>
                    <td class="center">{{ $row['reconciled'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:#9ca3af;">
                        No transactions for this period.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @empty
        <div style="text-align:center; padding:2rem; color:#9ca3af;">
            No account data selected.
        </div>
        @endforelse
    </div>
    @endif
</x-filament-panels::page>
