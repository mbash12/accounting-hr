<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-filament::input.wrapper
        :disabled="$isDisabled()"
        :valid="! $errors->has($getStatePath())"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-select'])
        "
    >
        <x-filament::input.select
            :attributes="
                \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                    ->merge([
                        'id' => $getId(),
                        'disabled' => $isDisabled(),
                        'wire:key' => $this->getId() . '.forms.' . $getStatePath() . '.input',
                    ], escape: false)
                    ->merge($getExtraInputAttributes(), escape: false)
            "
        >
            @if (! $isDisabled())
                @if (($placeholder = $getPlaceholder()) !== null)
                    <option value="">
                        {{ $placeholder }}
                    </option>
                @endif
            @endif

            @foreach ($getOptions() as $value => $label)
                <option
                    @disabled($isOptionDisabled($value, $label))
                    value="{{ $value }}"
                    @selected($isSelected($value))
                >
                    {{ $label }}
                </option>
            @endforeach
        </x-filament::input.select>
    </x-filament::input.wrapper>
</x-dynamic-component>