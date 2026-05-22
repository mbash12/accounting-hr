<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
    </form>

    <div class="mt-4">
        <p class="text-sm text-gray-500 italic">
            {{ __('Select an employee above, then use the buttons in the header to simulate Check In, Check Out, or Permit/Leave submission.') }}
        </p>
    </div>
</x-filament-panels::page>
