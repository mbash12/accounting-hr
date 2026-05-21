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

        .report-page {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow-x: auto;
        }

        .report-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .tb-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
        }

        .tb-table th {
            background-color: #1e3a8a;
            color: white;
            padding: 0.5rem;
            text-align: left;
            border: 1px solid #e5e7eb;
        }

        .tb-table td {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .tb-table .num {
            text-align: right;
        }

        .tb-table .total-row {
            font-weight: bold;
            background-color: #f3f4f6;
        }

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
                <h1 class="text-2xl font-bold text-blue-900">{{ __('LAPORAN RINGKASAN PAYROLL') }}</h1>
                <p class="text-gray-600 font-semibold">
                    {{ __('Periode') }}: {{ $reportData['period']?->name }} 
                    @if($reportData['department']) | {{ __('Department') }}: {{ $reportData['department']->name }} @endif
                </p>
            </div>

            <table class="tb-table">
                <thead>
                    <tr>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th class="num">{{ __('Gaji Pokok') }}</th>
                        <th class="num">{{ __('Tunjangan') }}</th>
                        <th class="num">{{ __('Potongan') }}</th>
                        <th class="num">{{ __('Gaji Bruto') }}</th>
                        <th class="num">{{ __('PPh21') }}</th>
                        <th class="num">{{ __('BPJS (Kar)') }}</th>
                        <th class="num">{{ __('BPJS (Per)') }}</th>
                        <th class="num">{{ __('Gaji Bersih') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['payslips'] as $payslip)
                        <tr>
                            <td>{{ $payslip->employee->name }}</td>
                            <td>{{ $payslip->employee->department?->name }}</td>
                            <td class="num">{{ number_format($payslip->basic_salary, 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($payslip->total_allowance, 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($payslip->total_deduction, 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($payslip->gross_salary, 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($payslip->pph21, 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($payslip->bpjs_kesehatan_employee + $payslip->bpjs_ketenagakerjaan_employee, 0, ',', '.') }}</td>
                            <td class="num">{{ number_format($payslip->bpjs_kesehatan_employer + $payslip->bpjs_ketenagakerjaan_employer, 0, ',', '.') }}</td>
                            <td class="num font-bold">{{ number_format($payslip->net_salary, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2" class="text-center uppercase">{{ __('Total') }}</td>
                        <td class="num">{{ number_format($reportData['totals']['basic_salary'], 0, ',', '.') }}</td>
                        <td class="num">{{ number_format($reportData['totals']['allowance'], 0, ',', '.') }}</td>
                        <td class="num">{{ number_format($reportData['totals']['deduction'], 0, ',', '.') }}</td>
                        <td class="num">{{ number_format($reportData['totals']['gross'], 0, ',', '.') }}</td>
                        <td class="num">{{ number_format($reportData['totals']['pph21'], 0, ',', '.') }}</td>
                        <td class="num">{{ number_format($reportData['totals']['bpjs_employee'], 0, ',', '.') }}</td>
                        <td class="num">{{ number_format($reportData['totals']['bpjs_employer'], 0, ',', '.') }}</td>
                        <td class="num">{{ number_format($reportData['totals']['net'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</x-filament-panels::page>
