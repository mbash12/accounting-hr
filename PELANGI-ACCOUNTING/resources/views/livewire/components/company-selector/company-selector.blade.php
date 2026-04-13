<style>
    .company-selector {
        margin: 16px 12px 16px 12px;
    }

    .company-dropdown {
        position: relative;
        width: 100%;
    }

    .company-dropdown-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        background: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 10px 12px;
        cursor: pointer;
        transition: all 0.15s ease;
        min-height: 42px;
    }

    .company-dropdown-trigger:hover {
        border-color: #9ca3af;
        background-color: #f9fafb;
    }

    .company-dropdown-trigger.active {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .company-dropdown-content {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 50;
        max-height: 240px;
        overflow-y: auto;
        display: none;
    }

    .company-dropdown-content.show {
        display: block;
    }

    .company-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        border-bottom: 1px solid #f3f4f6;
    }

    .company-item:last-child {
        border-bottom: none;
    }

    .company-item:hover {
        background-color: #f9fafb;
    }

    .company-item.selected {
        background-color: #eff6ff;
        color: #1e40af;
    }

    .company-item-icon {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        color: #6b7280;
        flex-shrink: 0;
    }

    .company-item.selected .company-item-icon {
        color: #3b82f6;
    }

    .company-item-text {
        flex: 1;
        font-size: 14px;
        font-weight: 400;
    }

    .company-item.selected .company-item-text {
        font-weight: 500;
    }

    .company-item-check {
        margin-left: auto;
        color: #3b82f6;
        opacity: 0;
        transition: opacity 0.15s ease;
    }

    .company-item.selected .company-item-check {
        opacity: 1;
    }

    .trigger-content {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .trigger-icon {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        color: #6b7280;
    }

    .trigger-text {
        flex: 1;
        font-size: 14px;
        color: #374151;
    }

    .trigger-arrow {
        width: 16px;
        height: 16px;
        color: #6b7280;
        transition: transform 0.15s ease;
    }

    .company-dropdown-trigger.active .trigger-arrow {
        transform: rotate(180deg);
    }

    /* Transition animations */
    .company-dropdown-content {
        transform-origin: top left;
        transition: opacity 0.15s ease, transform 0.15s ease;
        opacity: 0;
        transform: scale(0.95);
        visibility: hidden;
    }

    .company-dropdown-content.show {
        opacity: 1;
        transform: scale(1);
        visibility: visible;
    }

    /* Scrollbar styling */
    .company-dropdown-content::-webkit-scrollbar {
        width: 6px;
    }

    .company-dropdown-content::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .company-dropdown-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .company-dropdown-content::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
    document.addEventListener('livewire:init', () => {
        window.companySelectorData = {
            open: false,
            selectedValue: '{{ $selectedCompany }}',
            companies: @json($companies->pluck('name', 'id')),

            toggle() {
                this.open = !this.open;
                this.updateTriggerClass();
                this.updateDropdown();
            },

            select(value) {
                this.selectedValue = value;
                @this.selectCompany(value);
                this.open = false;
                this.updateTriggerClass();
                this.updateDropdown();
            },

            getSelectedText() {
                return this.companies[this.selectedValue] || 'Select Company';
            },

            updateTriggerClass() {
                const trigger = document.querySelector('.company-dropdown-trigger');
                if (trigger) {
                    if (this.open) {
                        trigger.classList.add('active');
                    } else {
                        trigger.classList.remove('active');
                    }
                }
            },

            updateDropdown() {
                const dropdown = document.querySelector('.company-dropdown-content');
                if (dropdown) {
                    if (this.open) {
                        dropdown.classList.add('show');
                    } else {
                        dropdown.classList.remove('show');
                    }
                }
            },

            init() {
                // Initialize
                this.updateTriggerClass();
                this.updateDropdown();

                // Click outside to close
                document.addEventListener('click', (e) => {
                    const selector = document.querySelector('.company-selector');
                    if (selector && !selector.contains(e.target)) {
                        this.open = false;
                        this.updateTriggerClass();
                        this.updateDropdown();
                    }
                });
            }
        };
    });
</script>

<div x-data="companySelectorData" class="company-selector">
    <div class="company-dropdown">
        <!-- Trigger -->
        <div @click="toggle()" class="company-dropdown-trigger">
            <div class="trigger-content">
                <div class="trigger-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div class="trigger-text" x-text="getSelectedText()"></div>
            </div>
            <div class="trigger-arrow">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>

        <!-- Dropdown Content -->
        <div class="company-dropdown-content">
            <!-- All Companies Option - Hidden but kept for reference -->
            <!--
            <div @click="select('all')" :class="{ 'selected': selectedValue === 'all' }" class="company-item">
                <div class="company-item-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div class="company-item-text">Semua Perusahaan</div>
                <div class="company-item-check">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            -->

            <!-- Company Options -->
            @foreach($companies as $company)
                <div @click="select('{{ $company->id }}')" :class="{ 'selected': selectedValue == '{{ $company->id }}' }"
                    class="company-item">
                    <div class="company-item-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div class="company-item-text">{{ $company->name }}</div>
                    <div class="company-item-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>