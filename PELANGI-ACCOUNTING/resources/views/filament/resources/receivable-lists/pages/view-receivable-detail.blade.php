<x-filament-panels::page>
    <div class="space-y-6">
        {{-- <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">{{ __('Customer Information') }}</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Customer Name') }}</p>
                    <p class="font-medium">{{ $record->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">{{ __('Total Receivable') }}</p>
                    <p class="font-medium">{{ number_format($summary['total_receivable'] ?? 0, 0, ',', '.') }} IDR</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">{{ __('Total Paid') }}</p>
                    <p class="font-medium">{{ number_format($summary['total_paid'] ?? 0, 0, ',', '.') }} IDR</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">{{ __('Total Outstanding') }}</p>
                    <p class="font-medium text-warning-600">{{ number_format($summary['total_outstanding'] ?? 0, 0, ',', '.') }} IDR</p>
                </div>
            </div>
        </div> --}}

        <div>
            {{-- <h3 class="text-lg font-semibold mb-4">{{ __('Detail Account Receivable') }} {{ $record->id }}</h3> --}}
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>

