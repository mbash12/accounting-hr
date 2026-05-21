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

    @php
    $reportData = collect($this->getReportData());
    @endphp

    @if($reportData->has('error'))
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
        <p>{{ $reportData['error'] ?? 'Please select a specific company.' }}</p>
    </div>
    @else
    <div class="report-page">
        <div class="report-header">
            <h2 class="report-company-name">{{ $reportData['company']->name }}</h2>
            <h1 class="report-title">Cash Flow Statement (Indirect Method)</h1>
            <p class="report-date">Period {{ \Carbon\Carbon::parse($reportData['start_date'])->isoFormat('MMMM YYYY') }}
                to {{ \Carbon\Carbon::parse($reportData['end_date'])->isoFormat('MMMM YYYY') }}</p>
        </div>

        <table class="tb-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="right" style="width: 250px;">Balance</th>
                </tr>
            </thead>
            <tbody>
                <!-- Operating Activities -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: #f9fafb; text-transform: uppercase;">
                        Cash Flow from Operating Activities</td>
                </tr>
                @foreach($reportData['plTree'] as $node)
                @include('filament.pages.reports.partials.cash-flow-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                    <td style="font-weight: bold; color: #1f2937;">Laba(Rugi) Bersih Operasi</td>
                    <td class="num" style="font-weight: bold; color: #1f2937;">
                        @if($reportData['plTotal'] < 0) - {{ number_format(abs($reportData['plTotal']), 0, ',' , '.' )
                            }} @else {{ number_format($reportData['plTotal'], 0, ',' , '.' ) }} @endif </td>
                </tr>

                <!-- Non-Cash Adjustments: Depreciation Add-Back -->
                @if(count($reportData['nonCashTree']) > 0)
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1rem; background-color: #f9fafb; text-transform: uppercase;">
                        Penyesuaian Non-Kas (Penyusutan & Amortisasi)</td>
                </tr>
                @foreach($reportData['nonCashTree'] as $node)
                @include('filament.pages.reports.partials.cash-flow-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 1px solid #9ca3af; background-color: white;">
                    <td style="font-weight: bold; color: #1f2937;">Jumlah Penyesuaian Non-Kas</td>
                    <td class="num" style="font-weight: bold; color: #1f2937;">
                        @if($reportData['nonCashTotal'] < 0) - {{ number_format(abs($reportData['nonCashTotal']), 0, ','
                            , '.' ) }} @else {{ number_format($reportData['nonCashTotal'], 0, ',' , '.' ) }} @endif
                            </td>
                </tr>
                @endif

                <tr style="border-top: 2px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #1f2937;">Laba(Rugi) Operasi sebelum perubahan Modal Kerja</td>
                    <td class="num" style="font-weight: bold; color: #1f2937;">
                        @php $adjPlTotal = $reportData['plTotal'] + $reportData['nonCashTotal']; @endphp
                        @if($adjPlTotal < 0) - {{ number_format(abs($adjPlTotal), 0, ',' , '.' ) }} @else {{
                            number_format($adjPlTotal, 0, ',' , '.' ) }} @endif </td>
                </tr>

                <!-- Operating Assets Change -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: #f9fafb; text-transform: uppercase;">
                        Berkurang(Bertambah) pada Operasi Aktiva</td>
                </tr>
                @foreach($reportData['opAssetsTree'] as $node)
                @include('filament.pages.reports.partials.cash-flow-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 2px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #1f2937;">Jumlah Berkurang(Bertambah) pada Operasi Aktiva</td>
                    <td class="num" style="font-weight: bold; color: #1f2937;">
                        @if($reportData['opAssetsTotal'] < 0) - {{ number_format(abs($reportData['opAssetsTotal']),
                            0, ',' , '.' ) }} @else {{ number_format($reportData['opAssetsTotal'], 0, ',' , '.' ) }}
                            @endif </td>
                </tr>

                <!-- Operating Liabilities Change -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: #f9fafb; text-transform: uppercase;">
                        Bertambah (berkurang) pada Operasi Kewajiban</td>
                </tr>
                @foreach($reportData['opLiabTree'] as $node)
                @include('filament.pages.reports.partials.cash-flow-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 2px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #1f2937;">Jumlah Bertambah (berkurang) pada Operasi Kewajiban
                    </td>
                    <td class="num"
                        style="font-weight: bold; color: {{ $reportData['opLiabTotal'] < 0 ? '#dc2626' : '#1f2937' }};">
                        @if($reportData['opLiabTotal'] < 0) - {{ number_format(abs($reportData['opLiabTotal']), 0, ','
                            , '.' ) }} @else {{ number_format($reportData['opLiabTotal'], 0, ',' , '.' ) }} @endif </td>
                </tr>

                <!-- Net Operating Cash Flow -->
                <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                    <td style="font-weight: bold; color: #1e3a8a;">Kas bersih (dipakai)/dihasilkan oleh Aktivitas
                        Operasi</td>
                    <td class="num" style="font-weight: bold; color: #1e3a8a;">
                        @if($reportData['operatingTotal'] < 0) - {{ number_format(abs($reportData['operatingTotal']),
                            0, ',' , '.' ) }} @else {{ number_format($reportData['operatingTotal'], 0, ',' , '.' ) }}
                            @endif </td>
                </tr>

                <!-- Investing Activities -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: #f9fafb; text-transform: uppercase;">
                        Cash Flow from Investing Activities</td>
                </tr>
                @foreach($reportData['invTree'] as $node)
                @include('filament.pages.reports.partials.cash-flow-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                    <td style="font-weight: bold; color: #1e3a8a;">Kas bersih yg dihasilkan / (dipakai) oleh Aktivitas
                        Investasi</td>
                    <td class="num" style="font-weight: bold; color: #1e3a8a;">
                        @if($reportData['invTotal'] < 0) - {{ number_format(abs($reportData['invTotal']), 0, ',' , '.' )
                            }} @else {{ number_format($reportData['invTotal'], 0, ',' , '.' ) }} @endif </td>
                </tr>

                <!-- Financing Activities -->
                <tr>
                    <td colspan="2"
                        style="font-weight: bold; color: #1e3a8a; padding-top: 1.5rem; background-color: #f9fafb; text-transform: uppercase;">
                        Cash Flow from Financing Activities</td>
                </tr>
                @foreach($reportData['finTree'] as $node)
                @include('filament.pages.reports.partials.cash-flow-row', ['account' => $node, 'level' => 0])
                @endforeach
                <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                    <td style="font-weight: bold; color: #1e3a8a;">Kas bersih yg dihasilkan dari / (dipakai) oleh
                        Aktivitas Pendanaan</td>
                    <td class="num" style="font-weight: bold; color: #1e3a8a;">
                        @if($reportData['finTotal'] < 0) - {{ number_format(abs($reportData['finTotal']), 0, ',' , '.' )
                            }} @else {{ number_format($reportData['finTotal'], 0, ',' , '.' ) }} @endif </td>
                </tr>

                <tr style="height: 15px; background: white !important;">
                    <td colspan="2" style="border: none;"></td>
                </tr>

                <!-- Final Totals -->
                <tr style="border-top: 2px solid #1f2937; background-color: white;">
                    <td style="font-weight: bold; color: #000; text-transform: uppercase;">Kas bersih dihasilkan oleh /
                        (dipakai) di Periode ini</td>
                    <td class="num" style="font-weight: bold; color: #000;">
                        @if($reportData['netCashFlow'] < 0) - {{ number_format(abs($reportData['netCashFlow']), 0, ','
                            , '.' ) }} @else {{ number_format($reportData['netCashFlow'], 0, ',' , '.' ) }} @endif </td>
                </tr>
                <tr style="background-color: white;">
                    <td style="font-weight: bold; color: #374151;">Kas & Setara Kas pada Awal Periode</td>
                    <td class="num" style="font-weight: bold; color: #374151;">
                        @if($reportData['beginningCash'] < 0) - {{ number_format(abs($reportData['beginningCash']),
                            0, ',' , '.' ) }} @else {{ number_format($reportData['beginningCash'], 0, ',' , '.' ) }}
                            @endif </td>
                </tr>
                <tr style="border-top: 2px solid #1e3a8a; background-color: #eff6ff !important;">
                    <td style="font-weight: bold; color: #1e3a8a; text-transform: uppercase;">Kas & Setara Kas pada
                        Akhir Periode</td>
                    <td class="num" style="font-weight: bold; color: #1e3a8a;">
                        @if($reportData['endingCash'] < 0) - {{ number_format(abs($reportData['endingCash']), 0, ','
                            , '.' ) }} @else {{ number_format($reportData['endingCash'], 0, ',' , '.' ) }} @endif </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
</x-filament-panels::page>