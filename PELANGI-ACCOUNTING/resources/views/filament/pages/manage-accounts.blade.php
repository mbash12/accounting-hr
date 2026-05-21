<x-filament-panels::page>
    <div class="chart-of-accounts" x-data="{
        expandedAccounts: $persist({}).as('expanded-accounts'),
        search: @entangle('search'),

        init() {
            $watch('search', (value) => {
                if (value && typeof value === 'string' && value.trim() !== '') {
                    document.querySelectorAll('.account-tree-item').forEach(item => {
                        const accountId = item.id.replace('account-', '');
                        this.expandedAccounts[accountId] = true;
                    });
                }
            });

            window.addEventListener('expand-all-accounts', () => {
                this.expandAll();
            });

            window.addEventListener('collapse-all-accounts', () => {
                this.collapseAll();
            });
        },

        expandAll() {
            document.querySelectorAll('.account-tree-item').forEach(item => {
                const accountId = item.id.replace('account-', '');
                this.expandedAccounts[accountId] = true;
            });
        },

        collapseAll() {
            this.expandedAccounts = {};
        }
    }">
        @if(session('selected_company_id') === 'all')
            <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 400px; text-align: center;">
                <div style="max-width: 400px;">
                    <div style="width: 80px; height: 80px; background: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">{{ __('Select Company') }}</h3>
                    <p style="color: #6b7280; margin: 0 0 1.5rem 0; line-height: 1.5;">
                        {{ __('Please select a specific company from the company selector to view and manage the Chart of Accounts.') }}
                    </p>
                </div>
            </div>
        @else
            <div class="accounts-container">
                <!-- <div class="accounts-header">
                    <h2 class="accounts-title">{{ __('Chart of Accounts') }}</h2>
                    <p class="accounts-subtitle">{{ __('Manage your account hierarchy and structure') }}</p>
                </div> -->
                <div class="accounts-search">
                    <div class="search-container">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input
                            type="text"
                            class="search-input"
                            placeholder="{{ __('Search accounts by code, name, or description...') }}"
                            wire:model.live.debounce.300ms="search"
                        >
                        @if(!empty($search))
                            <button
                                class="search-clear"
                                wire:click="$set('search', '')"
                                title="{{ __('Clear search') }}"
                            >
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
                <div class="accounts-content">
                    <div class="accounts-tree">
                        @foreach($this->getAccounts() as $account)
                            @include('filament.pages.partials.account-tree-item', ['account' => $account, 'level' => 0])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        /* .chart-of-accounts {
            padding: 1rem;
        }
         */
        .accounts-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .accounts-header {
            background: linear-gradient(to right, #eff6ff, #eef2ff);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .accounts-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }
        
        .accounts-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }
        
        .accounts-content {
            padding: 1.5rem;
        }
        
        .accounts-tree {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .account-tree-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: white;
        }
        
        .account-item-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            transition: background 0.2s ease;
            border-radius: 0.5rem;
        }
        
        .account-item-content:hover {
            background: #f9fafb;
        }
        
        .account-item-main {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }
        
        .tree-lines {
            display: flex;
            align-items: center;
        }
        
        .tree-line {
            width: 1.5rem;
            height: 1rem;
            border-left: 2px solid #d1d5db;
            border-bottom: 2px solid #d1d5db;
        }
        
        .expand-btn {
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }
        
        .expand-btn:hover {
            background: #e5e7eb;
            color: #374151;
        }
        
        .expand-icon {
            width: 1rem;
            height: 1rem;
            transition: transform 0.2s;
        }
        
        .tree-dot-container {
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .tree-dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background: #9ca3af;
        }
        
        .account-code {
            font-family: 'SF Mono', Monaco, 'Cascadia Code', monospace;
            font-size: 0.875rem;
            font-weight: 600;
            color: #4b5563;
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            min-width: 6rem;
            letter-spacing: 0.025em;
        }
        
        .account-name {
            font-size: 0.875rem;
            color: #374151;
            flex: 1;
        }
        
        .account-name.header {
            font-weight: 700;
            color: #1f2937;
        }
        
        .account-description {
            font-size: 0.75rem;
            color: #6b7280;
            margin-left: 0.5rem;
        }
        
        .account-badges {
            display: flex;
            gap: 0.5rem;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-asset {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-liability {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-equity {
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .badge-revenue {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-expense {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-header {
            background: #f3e8ff;
            color: #6b21a8;
        }
        
        .badge-cash {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-inactive {
            background: #f3f4f6;
            color: #374151;
        }
        
        .account-actions {
            display: flex;
            gap: 0.25rem;
            opacity: 0;
            transition: opacity 0.2s;
            margin-left: 1rem;
        }
        
        .account-item-content:hover > .account-actions {
            opacity: 1;
        }
        
        .action-btn {
            padding: 0.375rem;
            background: none;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .action-btn svg {
            width: 1rem;
            height: 1rem;
        }
        
        .action-btn.add {
            color: #2563eb;
        }
        
        .action-btn.add:hover {
            background: #dbeafe;
        }
        
        .action-btn.edit {
            color: #6b7280;
        }
        
        .action-btn.edit:hover {
            background: #f3f4f6;
        }
        
        .action-btn.delete {
            color: #dc2626;
        }
        
        .action-btn.delete:hover {
            background: #fee2e2;
        }
        
        .account-children {
            margin-top: 0.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .tree-connector {
            position: relative;
        }
        
        .tree-connector::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5rem;
            height: 100%;
            border-left: 2px solid #d1d5db;
        }
        
        .accounts-search {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        
        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            width: 1.25rem;
            height: 1.25rem;
            color: #9ca3af;
            pointer-events: none;
        }
        
        .search-input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: #374151;
            background-color: white;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .search-input::placeholder {
            color: #9ca3af;
        }
        
        .search-clear {
            position: absolute;
            right: 0.5rem;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }
        
        .search-clear:hover {
            background: #e5e7eb;
            color: #374151;
        }
        
        .search-clear svg {
            width: 1rem;
            height: 1rem;
        }
    </style>
</x-filament-panels::page>