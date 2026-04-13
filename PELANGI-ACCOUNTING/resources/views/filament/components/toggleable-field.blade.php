@php
    $mainField = $getMainFieldComponent();
    $secondaryField = $getSecondaryFieldComponent();
    $defaultShowSecondary = $getDefaultShowSecondary();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{
        showSecondary: @js($defaultShowSecondary),
        toggle() {
            this.showSecondary = !this.showSecondary;
        }
    }" class="space-y-3">
        
        <!-- Main Field with Toggle Button -->
        <div class="flex items-start space-x-2">
            <div class="flex-1" style="flex:1">
                @if($mainField)
                    {{ $mainField }}
                @endif
            </div>
            
            <!-- Toggle Button -->
            @if($secondaryField)
                <button
                    @click="toggle()"
                    type="button"
                    title="Toggle secondary field"
                    style="
                        margin-top: 24px;
                        padding: 8px;
                        cursor: pointer;
                        min-width: 44px;
                        min-height: 44px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        transition: all 0.2s ease;
                        font-size: 16px;
                        font-weight: bold;
                    "
                >
                    <span x-show="!showSecondary" style="display: block;">↓</span>
                    <span x-show="showSecondary" style="display: block;">→</span>
                </button>
            @endif
        </div>

        <!-- Secondary Field (Hidden by default) -->
        @if($secondaryField)
            <div x-show="showSecondary" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 transform -translate-y-2" 
                 x-transition:enter-end="opacity-100 transform translate-y-0" 
                 x-transition:leave="transition ease-in duration-150" 
                 x-transition:leave-start="opacity-100 transform translate-y-0" 
                 x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2" style="margin-top:8px;">
                 
                {{ $secondaryField }}
            </div>
        @endif
    </div>
</x-dynamic-component>