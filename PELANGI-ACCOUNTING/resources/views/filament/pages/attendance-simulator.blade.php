<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
    </form>

    <div class="mt-4">
        <p class="text-sm text-gray-500 italic">
            {{ __('Pilih karyawan di atas, lalu gunakan tombol di header untuk mensimulasikan Absen Masuk, Absen Keluar, atau pengajuan Izin/Cuti.') }}
        </p>
    </div>
</x-filament-panels::page>
