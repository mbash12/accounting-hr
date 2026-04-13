<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        
        <x-filament::section class="mt-6">
            <x-filament::actions>
                <x-filament::button type="submit">
                    Save Changes
                </x-filament::button>
            </x-filament::actions>
        </x-filament::section>
    </form>
</x-filament-panels::page>