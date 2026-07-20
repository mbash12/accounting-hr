@php
    $indentSize = $level * 24;
    $canDelete = ! $account->isClassificationRoot();
    use Illuminate\Support\Str;
    $uniqueId = 'account-' . $account->id;

    $visibleChildren = $account->children;
    if (!empty($this->search) && !empty($this->filteredAccountIds)) {
        $visibleChildren = $visibleChildren->filter(fn($child) => in_array($child->id, $this->filteredAccountIds));
    }
@endphp

<div class="account-tree-item" id="{{ $uniqueId }}">
    <div class="account-item-content">
        <div class="account-item-main">
            @if($level > 0)
                <div class="tree-lines">
                    <div class="tree-line" style="margin-left: {{ ($level - 1) * 24 }}px;"></div>
                </div>
            @endif
            
            @if($visibleChildren->count() > 0)
                <button
                    type="button"
                    @click="expandedAccounts['{{ $account->id }}'] = !expandedAccounts['{{ $account->id }}']"
                    class="expand-btn"
                    title="Expand/Collapse"
                    x-init="(search && typeof search === 'string' && search.trim() !== '') ? expandedAccounts['{{ $account->id }}'] = true : null"
                >
                    <svg x-show="!expandedAccounts['{{ $account->id }}']" class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <svg x-show="expandedAccounts['{{ $account->id }}']" class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            @else
                <div class="tree-dot-container">
                    <div class="tree-dot"></div>
                </div>
            @endif

            <span class="account-code">
                {{ $account->code }}
            </span>

            <span class="account-name {{ $account->is_header ? 'header' : '' }}">
                {{ $account->name }}
            </span>

        </div>

        <div class="account-actions">
            <button 
                wire:click="mountAction('addChild', { 'record': {{ $account->id }} })"
                class="action-btn add"
                title="Add Child Account"
            >
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
            <button 
                wire:click="mountAction('edit', { 'record': {{ $account->id }} })"
                class="action-btn edit"
                title="Edit Account"
            >
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
            @if($canDelete)
                <button 
                    wire:click="mountAction('delete', { 'record': {{ $account->id }} })"
                    class="action-btn delete"
                    title="Delete Account"
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    @if($visibleChildren->count() > 0)
        <div
            x-show="expandedAccounts['{{ $account->id }}'] || (search && typeof search === 'string' && search.trim() !== '')"
            x-transition
            class="account-children"
            x-init="(search && typeof search === 'string' && search.trim() !== '') ? expandedAccounts['{{ $account->id }}'] = true : null"
        >
            @if($level > 0)
                <div class="tree-connector" style="margin-left: {{ ($level - 1) * 24 }}px;"></div>
            @endif
            @foreach($visibleChildren as $child)
                @include('filament.pages.partials.account-tree-item', ['account' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
