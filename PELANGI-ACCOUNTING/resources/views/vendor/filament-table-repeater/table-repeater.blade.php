@php
use Filament\Support\Enums\Alignment;
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>

    @php
        $items = $getItems();

        $addAction = $getAction($getAddActionName());
        $addActionAlignment = $getAddActionAlignment();
        $cloneAction = $getAction($getCloneActionName());
        $deleteAction = $getAction($getDeleteActionName());
        $moveDownAction = $getAction($getMoveDownActionName());
        $moveUpAction = $getAction($getMoveUpActionName());
        $reorderAction = $getAction($getReorderActionName());

        $isAddable = $isAddable();
        $isCloneable = $isCloneable();
        $isCollapsible = $isCollapsible();//
        $isDeletable = $isDeletable();
        $isReorderable = $isReorderable();
        $isReorderableWithButtons = $isReorderableWithButtons();
        $isReorderableWithDragAndDrop = $isReorderableWithDragAndDrop();

        $statePath = $getStatePath();

        $columnLabels = $getColumnLabels();
        $colStyles = $getColStyles();
        //---

        $addBetweenAction = $getAction($getAddBetweenActionName());//
        $extraItemActions = $getExtraItemActions();

    @endphp

    <div
        {{-- x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }"  --}}
        x-data="{ 
            isCollapsed: @js($isCollapsed()),
            showOverflow: false,
            toggleOverflow() {
                this.showOverflow = !this.showOverflow;
            }
        }"
        x-on:repeater-collapse.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = true)"
        x-on:repeater-expand.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = false)"

        {{
            $attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class(['it-table-repeater'])
        }}
    >

        <div
            class="it-table-repeater-header"
        >

            <div></div>
        
            @if ($isCollapsible)
                <div>
                    <button
                        x-on:click="isCollapsed = !isCollapsed"
                        type="button"
                        class="it-table-repeater-btn-collapse"
                    >
                    
                        <x-heroicon-s-chevron-up class="w-4 h-4" x-show="! isCollapsed"/>

                        <span class="sr-only" x-show="! isCollapsed">
                            {{ __('filament-forms::components.repeater.actions.collapse.label') }}
                        </span>

                        <x-heroicon-s-chevron-down class="w-4 h-4" x-show="isCollapsed" x-cloak/>

                        <span class="sr-only" x-show="isCollapsed" x-cloak>
                            {{ __('filament-forms::components.repeater.actions.expand.label') }}
                        </span>
                    </button>
                </div>
            @endif
        </div>

        <div class="px-4{{ $isAddable? '' : ' py-2' }}">
            <table x-show="! isCollapsed">
                <thead>
                    <tr>

                        @php
                            $components = !empty($items) && isset($items[0]) ? $items[0]->getComponents(withHidden: true) : [];
                            $primaryComponents = array_slice($components, 0, 4);
                            $hasOverflow = count($components) > 4;
                        @endphp

                        <th class="w-8"></th>

                        @php
                            $displayLabels = array_slice($columnLabels, 0, 4);
                            $columnWidths = ['30%', '20%', '20%', '20%'];
                        @endphp

                        @foreach($displayLabels as $index => $columnLabel)
                            @if($columnLabel['display'])
                            <th
                                @if($colStyles && isset($colStyles[$columnLabel['component']]))
                                    style="{{ $colStyles[$columnLabel['component']] }}; width: {{ $columnWidths[$index] ?? 'auto' }}"
                                @else
                                    style="width: {{ $columnWidths[$index] ?? 'auto' }}"
                                @endif
                            >
                                <span>
                                    {{ $columnLabel['name'] }}
                                </span>
                            </th>
                            @else
                            <th class="hidden" style="width: {{ $columnWidths[$index] ?? 'auto' }}"></th>
                            @endif
                        @endforeach

                        @if (count($extraItemActions)||$isReorderableWithDragAndDrop || $isReorderableWithButtons || $isCloneable || $isDeletable)
                        	<th></th>
						@endif
                    </tr>
                </thead>

                <tbody
                    x-sortable
                >

                    @foreach ($items as $itemKey => $item)
                        @php
                            $components = $item->getComponents(withHidden: true);
                            $primaryComponents = array_slice($components, 0, 4);
                            $overflowComponents = array_slice($components, 4);
                        @endphp

                        <tr
                            x-on:repeater-collapse.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = true)"
                            x-on:repeater-expand.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = false)"
                            wire:key="{{ $item->getLivewireKey() }}.item.primary"
                            x-sortable-item="{{ $itemKey }}"
                        >

                            <td class="it-table-repeater-overflow-toggle">
                                @if (count($overflowComponents) > 0)
                                    <button
                                        x-on:click="toggleOverflow"
                                        type="button"
                                        class="text-gray-600 hover:text-gray-800 p-1"
                                    >
                                        <x-heroicon-s-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="showOverflow ? 'rotate-180' : ''"/>
                                    </button>
                                @endif
                            </td>

                            @foreach ($primaryComponents as $index => $component)
                            <td style="width: {{ $columnWidths[$index] ?? 'auto' }}">
                               {{ $component }}
                            </td>
                            @endforeach

                            @if (count($extraItemActions)||$isReorderableWithDragAndDrop || $isReorderableWithButtons || $isCloneable || $isDeletable )
								<td class="it-table-repeater-actions" rowspan="2">

                                    @foreach ($extraItemActions as $extraItemAction)
                                        <div x-on:click.stop>
                                            {{ $extraItemAction(['item' => $itemKey]) }}
                                        </div>
                                    @endforeach
                                    @if ($isReorderableWithDragAndDrop || $isReorderableWithButtons)
                                        @if ($isReorderableWithDragAndDrop)
                                            <div
                                                x-sortable-handle
                                                x-on:click.stop
                                            >
                                                {{ $reorderAction }}
                                            </div>
                                        @endif

                                        @if ($isReorderableWithButtons)
                                            <div
                                                class="flex items-center justify-center"
                                            >
                                                {{ $moveUpAction(['item' => $itemKey])->disabled($loop->first) }}
                                            </div>

                                            <div
                                                class="flex items-center justify-center"
                                            >
                                                {{ $moveDownAction(['item' => $itemKey])->disabled($loop->last) }}
                                            </div>
                                        @endif

                                    @endif

                                    @if ($isCloneable || $isDeletable )
                                        @if ($cloneAction->isVisible())
                                            <div>
                                                {{ $cloneAction(['item' => $itemKey]) }}
                                            </div>
                                        @endif

                                        @if ($isDeletable)
                                            <div>
                                                {{ $deleteAction(['item' => $itemKey]) }}
                                            </div>
                                        @endif

                                    @endif

                                </td>
							@endif
                        </tr>

                        @if (count($overflowComponents) > 0)
                            <tr
                                wire:key="{{ $item->getLivewireKey() }}.item.overflow"
                                x-show="showOverflow"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                            >
                                <td></td>
                                @foreach ($overflowComponents as $index => $component)
                                    @php
                                        $overflowLabelIndex = 4 + $index;
                                        $overflowLabel = isset($columnLabels[$overflowLabelIndex]) ? $columnLabels[$overflowLabelIndex] : null;
                                    @endphp
                                <td class="align-top">
                                    @if($overflowLabel && $overflowLabel['display'])
                                        <div class="text-xs font-medium text-gray-600 mb-1">
                                            {{ $overflowLabel['name'] }}
                                        </div>
                                    @endif
                                   {{ $component }}
                                </td>
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>

            <div class="it-table-repeater-collapsed" x-show="isCollapsed" x-cloak>
                {{ __('filament-table-repeater::components.table-repeater.collapsed') }}
            </div>
        </div>

        @if ($isAddable && $addAction->isVisible())
            <div
                @class([
                    'it-table-repeater-add',
                    match ($addActionAlignment) {
                        Alignment::Start, Alignment::Left => 'justify-start',
                        Alignment::Center, null => 'justify-center',
                        Alignment::End, Alignment::Right => 'justify-end',
                        default => $alignment,
                    },
                ])
            >
                {{ $addAction }}
            </div>
        @endif

    </div>

</x-dynamic-component>
