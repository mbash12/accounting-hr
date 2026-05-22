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
                Apply Filter
            </x-filament::button>
            <x-filament::button wire:click="downloadPdf" color="success" icon="heroicon-o-arrow-down-tray">
                Download PDF
            </x-filament::button>
        </div>
    </div>

    @if(isset($error))
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
        <p>{{ $error }}</p>
    </div>
    @elseif($company)
    <div class="report-page">
        <!-- Header -->
        <div class="report-header">
            <h2 class="report-company-name">{{ $company->name }}</h2>
            <h1 class="report-title">Balance Sheet (Standard)</h1>
            <p class="report-date">As of {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
        </div>

        <table class="tb-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="right" style="width: 150px;">Balance</th>
                </tr>
            </thead>
            <tbody>
                <!-- ASSETS -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: white; text-transform: uppercase;">
                        Assets</td>
                </tr>
                @php
                $assetNodes = ($assets->count() === 1 && $assets->first()->is_header) ? $assets->first()->children :
                $assets;
                @endphp
                @foreach($assetNodes as $node)
                @include('filament.pages.reports.partials.account-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 2px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #000; padding-left: 1.6rem;">Total Assets</td>
                    <td class="num" style="font-weight: bold; color: #000;">{{
                        number_format($assets->sum('calculated_balance'), 2, ',', '.') }}</td>
                </tr>

                <!-- LIABILITIES & EQUITY -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: white; text-transform: uppercase;">
                        Liabilities and Equity</td>
                </tr>

                <!-- Liabilities -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-left: 1.6rem; background-color: white; text-transform: uppercase;">
                        Liabilities</td>
                </tr>
                @php
                $liabNodes = ($liabilities->count() === 1 && $liabilities->first()->is_header) ?
                $liabilities->first()->children : $liabilities;
                @endphp
                @foreach($liabNodes as $node)
                @include('filament.pages.reports.partials.account-row', ['account' => $node, 'level' => 1])
                @endforeach

                <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                    <td style="font-weight: bold; color: #1f2937; padding-left: 2rem;">Total Liabilities</td>
                    <td class="num" style="font-weight: bold; color: #1f2937;">{{
                        number_format($liabilities->sum('calculated_balance'), 2, ',', '.') }}</td>
                </tr>

                <!-- Equity -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-left: 1.6rem; padding-top: 1rem; background-color: white; text-transform: uppercase;">
                        Equity</td>
                </tr>
                @php
                $equityNodes = ($equity->count() === 1 && $equity->first()->is_header) ? $equity->first()->children :
                $equity;
                @endphp
                @foreach($equityNodes as $node)
                @include('filament.pages.reports.partials.account-row', ['account' => $node, 'level' => 1])
                @endforeach

                <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                    <td style="font-weight: bold; color: #1f2937; padding-left: 2rem;">Total Equity</td>
                    <td class="num" style="font-weight: bold; color: #1f2937;">{{
                        number_format($equity->sum('calculated_balance'), 2, ',', '.') }}</td>
                </tr>

                <!-- Total L+E -->
                @php
                $totalLiabilities = $liabilities->sum('calculated_balance');
                $totalEquity = $equity->sum('calculated_balance');
                @endphp
                <tr style="border-top: 2px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #000; padding-left: 1.6rem;">Total Liabilities and Equity</td>
                    <td class="num" style="font-weight: bold; color: #000;">{{ number_format($totalLiabilities +
                        $totalEquity, 2, ',', '.') }}</td>
                </tr>
            </tbody>
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
        <p>Select a specific company to view the report.</p>
    </div>
    @endif
</x-filament-panels::page>