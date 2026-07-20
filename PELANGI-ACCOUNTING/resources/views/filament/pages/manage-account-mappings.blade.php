<x-filament-panels::page>
    <style>
        .account-mapping-layout {
            display: flex;
            gap: 1rem;
            min-height: 600px;
        }

        .account-mapping-sidebar {
            width: 280px;
            flex-shrink: 0;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 1rem 0;
        }

        .account-mapping-sidebar h3 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            padding: 0 1rem 0.75rem;
            margin: 0;
        }

        .account-mapping-sidebar-list {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            overflow-y: auto;
            max-height: calc(100vh - 200px);
            padding: 0 0.5rem;
        }

        .account-mapping-doc-btn {
            padding: 0.625rem 0.875rem;
            border-radius: 0.5rem;
            text-align: left;
            cursor: pointer;
            font-size: 0.875rem;
            border: none;
            transition: all 0.15s;
            position: relative;
            background-color: transparent;
            color: #374151;
            width: 100%;
        }

        .account-mapping-doc-btn:hover {
            background-color: #f3f4f6;
        }

        .account-mapping-doc-btn.is-active {
            background-color: #0ea5e9;
            color: white;
        }

        .account-mapping-doc-btn.is-active:hover {
            background-color: #0ea5e9;
        }

        .account-mapping-change-dot {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background-color: #f59e0b;
            border-radius: 50%;
        }

        .account-mapping-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            min-width: 0;
        }

        .account-mapping-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            /* Must stay visible so Filament searchable select dropdowns are not clipped */
            overflow: visible;
        }

        .account-mapping-card-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: #f9fafb;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .account-mapping-card-header h2 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }

        .account-mapping-card-body {
            padding: 1rem 1.25rem 1.25rem;
            overflow: visible;
        }

        /* Parent Filament wrappers often clip absolute dropdown panels */
        .fi-page > section,
        .fi-page-content,
        .fi-main,
        .fi-main-ctn,
        .account-mapping-layout,
        .account-mapping-main {
            overflow: visible !important;
        }

        .account-mapping-empty {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            padding: 2rem;
            text-align: center;
        }

        .account-mapping-empty h3 {
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
            margin: 0 0 0.25rem;
        }

        .account-mapping-empty p {
            font-size: 0.8125rem;
            color: #6b7280;
            margin: 0;
        }

        .account-mapping-field-changed .fi-input-wrp,
        .account-mapping-field-changed .fi-select-input {
            outline: 1px solid #f59e0b;
            border-radius: 0.5rem;
        }
    </style>

    <div class="account-mapping-layout">
        <div class="account-mapping-sidebar">
            <h3>Document Type</h3>
            <div class="account-mapping-sidebar-list">
                @foreach($this->getDocumentTypes() as $type => $label)
                    <button
                        type="button"
                        wire:click="selectDocumentType('{{ $type }}')"
                        class="account-mapping-doc-btn {{ $this->selectedDocumentType === $type ? 'is-active' : '' }}"
                    >
                        {{ $label }}
                        @if($this->hasChanges($type))
                            <span
                                class="account-mapping-change-dot"
                                style="box-shadow: 0 0 0 2px {{ $this->selectedDocumentType === $type ? '#0ea5e9' : 'white' }};"
                            ></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <div class="account-mapping-main">
            @if($this->selectedDocumentType)
                @if(session('selected_company_id') && session('selected_company_id') !== 'all')
                    <div class="account-mapping-card">
                        <div class="account-mapping-card-header">
                            <h2>{{ $this->getDocumentTypes()[$this->selectedDocumentType] }}</h2>
                        </div>
                        <div class="account-mapping-card-body fi-fixed-positioning-context" wire:key="mapping-form-{{ $this->selectedDocumentType }}">
                            {{ $this->form }}
                        </div>
                    </div>
                @else
                    <div class="account-mapping-empty">
                        <h3>Select Company</h3>
                        <p>Please select a company from the dropdown to configure account mappings.</p>
                    </div>
                @endif
            @else
                <div class="account-mapping-empty" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <h3>Select Document Type</h3>
                    <p>Select a document type from the sidebar to configure account mappings.</p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
