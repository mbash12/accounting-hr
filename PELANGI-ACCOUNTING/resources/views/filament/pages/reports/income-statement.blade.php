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

    @php $reportData = $this->getViewData(); @endphp

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
    $company = $reportData['company'];
    $startDate = $reportData['start_date'];
    $endDate = $reportData['end_date'];
    $operatingRevenues = $reportData['operatingRevenues'] ?? collect();
    $costOfGoodsSold = $reportData['costOfGoodsSold'] ?? collect();
    $operatingExpenses = $reportData['operatingExpenses'] ?? collect();
    $otherRevenues = $reportData['otherRevenues'] ?? collect();
    $otherExpenses = $reportData['otherExpenses'] ?? collect();
    $totalOperatingRevenue = $reportData['totalOperatingRevenue'] ?? 0;
    $totalCogs = $reportData['totalCogs'] ?? 0;
    $grossProfit = $reportData['grossProfit'] ?? 0;
    $totalOperatingExpense = $reportData['totalOperatingExpense'] ?? 0;
    $operatingProfit = $reportData['operatingProfit'] ?? 0;
    $totalOtherRevenue = $reportData['totalOtherRevenue'] ?? 0;
    $totalOtherExpense = $reportData['totalOtherExpense'] ?? 0;
    $netIncome = $reportData['netIncome'];
    @endphp
    <div class="report-page">
        <!-- Header -->
        <div class="report-header">
            <h2 class="report-company-name">{{ $company->name }}</h2>
            <h1 class="report-title">Income Statement</h1>
            <p class="report-date">
                Periode {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{
                \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>

        <table class="tb-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="right" style="width: 150px;">Balance</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. PENDAPATAN OPERASIONAL -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: white; text-transform: uppercase;">
                        Pendapatan Operasional</td>
                </tr>
                @php $opRevNodes = ($operatingRevenues->count() === 1 && $operatingRevenues->first()->is_header) ?
                $operatingRevenues->first()->children : $operatingRevenues; @endphp
                @foreach($opRevNodes as $node)
                @include('filament.pages.reports.partials.account-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 1px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #000; padding-left: 1.6rem;">Total Pendapatan Operasional</td>
                    <td class="num" style="font-weight: bold; color: #000;">{{ number_format($totalOperatingRevenue, 2,
                        ',', '.') }}</td>
                </tr>

                <!-- 2. HARGA POKOK PENJUALAN -->
                @if($costOfGoodsSold->count() > 0)
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: white; text-transform: uppercase;">
                        Cost of Goods Sold</td>
                </tr>
                @php $cogsNodes = ($costOfGoodsSold->count() === 1 && $costOfGoodsSold->first()->is_header) ?
                $costOfGoodsSold->first()->children : $costOfGoodsSold; @endphp
                @foreach($cogsNodes as $node)
                @include('filament.pages.reports.partials.account-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 1px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #000; padding-left: 1.6rem;">Total Cost of Goods Sold</td>
                    <td class="num" style="font-weight: bold; color: #000;">{{ number_format($totalCogs, 2, ',', '.') }}
                    </td>
                </tr>
                @endif

                <!-- LABA KOTOR -->
                <tr style="background-color: #f9fafb;">
                    <td
                        style="font-weight: bold; color: #111827; padding-left: 1.6rem; font-size: 1.05em; padding-top: 1rem; padding-bottom: 1rem;">
                        Gross Profit</td>
                    <td class="num"
                        style="font-weight: bold; color: #111827; font-size: 1.05em; border-top: 2px solid #111827; padding-top: 1rem; padding-bottom: 1rem;">
                        {{ number_format($grossProfit, 2, ',', '.') }}</td>
                </tr>

                <!-- 3. BEBAN OPERASIONAL -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: white; text-transform: uppercase;">
                        Beban Operasional</td>
                </tr>
                @php $opExpNodes = ($operatingExpenses->count() === 1 && $operatingExpenses->first()->is_header) ?
                $operatingExpenses->first()->children : $operatingExpenses; @endphp
                @foreach($opExpNodes as $node)
                @include('filament.pages.reports.partials.account-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 1px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #000; padding-left: 1.6rem;">Total Beban Operasional</td>
                    <td class="num" style="font-weight: bold; color: #000;">{{ number_format($totalOperatingExpense, 2,
                        ',', '.') }}</td>
                </tr>

                <!-- LABA OPERASIONAL -->
                <tr style="background-color: #f9fafb;">
                    <td
                        style="font-weight: bold; color: #111827; padding-left: 1.6rem; font-size: 1.05em; padding-top: 1rem; padding-bottom: 1rem;">
                        Laba Operasional (Operating Profit)</td>
                    <td class="num"
                        style="font-weight: bold; color: #111827; font-size: 1.05em; border-top: 2px solid #111827; padding-top: 1rem; padding-bottom: 1rem;">
                        {{ number_format($operatingProfit, 2, ',', '.') }}</td>
                </tr>

                <!-- 4. PENDAPATAN & BEBAN LAIN-LAIN -->
                @if($otherRevenues->count() > 0 || $otherExpenses->count() > 0)
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: white; text-transform: uppercase;">
                        Pendapatan & Beban Lain-lain</td>
                </tr>
                @endif

                @if($otherRevenues->count() > 0)
                @php $othRevNodes = ($otherRevenues->count() === 1 && $otherRevenues->first()->is_header) ?
                $otherRevenues->first()->children : $otherRevenues; @endphp
                @foreach($othRevNodes as $node)
                @include('filament.pages.reports.partials.account-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 1px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #000; padding-left: 1.6rem;">Total Pendapatan Lain-lain</td>
                    <td class="num" style="font-weight: bold; color: #000;">{{ number_format($totalOtherRevenue, 2, ',',
                        '.') }}</td>
                </tr>
                @endif

                @if($otherExpenses->count() > 0)
                @php $othExpNodes = ($otherExpenses->count() === 1 && $otherExpenses->first()->is_header) ?
                $otherExpenses->first()->children : $otherExpenses; @endphp
                @foreach($othExpNodes as $node)
                @include('filament.pages.reports.partials.account-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 1px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #000; padding-left: 1.6rem;">Total Beban Lain-lain</td>
                    <td class="num" style="font-weight: bold; color: #000;">{{ number_format($totalOtherExpense, 2, ',',
                        '.') }}</td>
                </tr>
                @endif

                <!-- NET INCOME -->
                <tr style="background-color: #f3f4f6;">
                    <td
                        style="font-weight: bold; color: #111827; padding-left: 1.6rem; font-size: 1.1em; border-top: 2px solid #1f2937; padding-top: 1rem; padding-bottom: 1rem;">
                        Laba / Rugi Bersih (Net Income)</td>
                    <td class="num"
                        style="font-weight: bold; color: #111827; font-size: 1.1em; border-top: 2px solid #111827; padding-top: 1rem; padding-bottom: 1rem;">
                        {{ number_format($netIncome, 2, ',', '.') }}</td>
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
        <p>Pilih perusahaan tertentu untuk melihat laporan.</p>
    </div>
    @endif
</x-filament-panels::page>