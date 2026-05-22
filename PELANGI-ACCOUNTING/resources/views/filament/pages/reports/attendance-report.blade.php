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
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .report-form { flex: 1; max-width: 800px; }
        .report-actions { display: flex; align-items: center; gap: 0.5rem; }

        .report-page {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .report-header { text-align: center; margin-bottom: 2rem; }

        .tb-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .tb-table th { background-color: #1e3a8a; color: white; padding: 0.75rem; text-align: left; border: 1px solid #e5e7eb; }
        .tb-table td { padding: 0.75rem; border: 1px solid #e5e7eb; }
        .tb-table .num { text-align: center; }

        @media print {
            .no-print { display: none; }
            .report-page { box-shadow: none; border: none; padding: 0; }
        }
    </style>

    <div class="report-header-controls no-print">
        <div class="report-form">
            {{ $this->form }}
        </div>
        <div class="report-actions">
            <x-filament::button wire:click="downloadPdf" color="success" icon="heroicon-o-arrow-down-tray">
                {{ __('Download PDF') }}
            </x-filament::button>
        </div>
    </div>

    @php $reportData = $this->getViewData(); @endphp

    @if(isset($reportData['error']))
        <div class="p-6 bg-white rounded-lg shadow text-center text-gray-500">
            {{ $reportData['error'] }}
        </div>
    @else
        <div class="report-page">
            <div class="report-header">
                <h2 class="text-xl font-bold uppercase">{{ $reportData['company']?->name }}</h2>
                <h1 class="text-2xl font-bold text-blue-900">{{ __('LAPORAN REKAPITULASI KEHADIRAN') }}</h1>
                <p class="text-gray-600 font-semibold uppercase">
                    {{ __('Bulan') }}: {{ __($reportData['month_name']) }} {{ $reportData['year'] }}
                    @if($reportData['department']) | {{ __('Department') }}: {{ $reportData['department']->name }} @endif
                </p>
            </div>

            <table class="tb-table">
                <thead>
                    <tr>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th class="num">{{ __('Hadir') }}</th>
                        <th class="num">{{ __('Terlambat') }}</th>
                        <th class="num">{{ __('Alpa') }}</th>
                        <th class="num">{{ __('Permit') }}</th>
                        <th class="num">{{ __('Leave') }}</th>
                        <th class="num font-bold">{{ __('Total Working Days') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['records'] as $record)
                        <tr>
                            <td class="font-medium">{{ $record['employee']->name }}</td>
                            <td>{{ $record['employee']->department?->name }}</td>
                            <td class="num">{{ $record['present'] }}</td>
                            <td class="num text-orange-600">{{ $record['late'] }}</td>
                            <td class="num text-red-600">{{ $record['absent'] }}</td>
                            <td class="num text-blue-600">{{ $record['permit'] }}</td>
                            <td class="num text-purple-600">{{ $record['leave'] }}</td>
                            <td class="num font-bold">{{ $record['total_working_days'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-filament-panels::page>
