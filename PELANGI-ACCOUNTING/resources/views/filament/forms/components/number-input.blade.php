@php
    use Filament\Forms\Components\TextInput;
@endphp

<x-dynamic-component
    :component="TextInput::class"
    {{ $attributes }}
>
    {{ $slot }}
</x-dynamic-component>