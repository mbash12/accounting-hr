<x-filament-panels::page>
    <style>
        table td:last-child {
            padding: 0.75rem !important;
            text-align: center !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        table td:last-child .fi-fo-field-label-ctn {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            width: 100% !important;
        }
    </style>
    @if(session('selected_company_id') === 'all')
        <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 400px; text-align: center;">
            <div style="max-width: 400px;">
                <div style="width: 80px; height: 80px; background: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">{{ __('Pilih Perusahaan') }}</h3>
                <p style="color: #6b7280; margin: 0 0 1.5rem 0; line-height: 1.5;">
                    {{ __('Silakan pilih perusahaan tertentu dari pemilih perusahaan untuk melihat dan mengelola konfigurasi nomor referensinya.') }}
                </p>
            </div>
        </div>
    @else
        <form wire:submit="save">
            <div class="space-y-6">
                {{ $this->form }}

                <div class="flex justify-end" style="margin-top: 2rem;">
                    <x-filament::button type="submit" wire:loading.attr="disabled">
                        <x-filament::loading-indicator wire:loading wire:target="save" class="h-5 w-5" />
                        {{ __('Save') }}
                    </x-filament::button>
                </div>
            </div>
        </form>
    @endif
</x-filament-panels::page>