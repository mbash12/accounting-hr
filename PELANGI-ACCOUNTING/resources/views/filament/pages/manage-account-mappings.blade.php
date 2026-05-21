<x-filament-panels::page>
    <div style="display: flex; gap: 1rem; min-height: 600px;">
        {{-- Sidebar: Document Types --}}
        <div style="width: 280px; flex-shrink: 0; background: white; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; padding: 1rem 0;">
            <div style="padding: 0 1rem 0.75rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: #374151;">Jenis Dokumen</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.25rem; overflow-y: auto; max-height: calc(100vh - 200px); padding: 0 0.5rem;">
                @foreach($this->getDocumentTypes() as $type => $label)
                    <button
                        wire:click="selectDocumentType('{{ $type }}')"
                        @if($this->selectedDocumentType === $type)
                            style="padding: 0.625rem 0.875rem; border-radius: 0.5rem; text-align: left; cursor: pointer; font-size: 0.875rem; background-color: #0ea5e9; color: white; border: none; transition: all 0.15s; position: relative;"
                        @else
                            style="padding: 0.625rem 0.875rem; border-radius: 0.5rem; text-align: left; cursor: pointer; font-size: 0.875rem; background-color: transparent; color: #374151; border: none; transition: all 0.15s; position: relative;"
                        @endif
                        onmouseover="if(this.style.backgroundColor !== 'rgb(14, 165, 233)') this.style.backgroundColor = '#f3f4f6';"
                        onmouseout="if(this.style.backgroundColor !== 'rgb(14, 165, 233)') this.style.backgroundColor = 'transparent';"
                    >
                        {{ $label }}
                        @if($this->hasChanges($type))
                            <span style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); width: 8px; height: 8px; background-color: #f59e0b; border-radius: 50%; box-shadow: 0 0 0 2px {{ $this->selectedDocumentType === $type ? '#0ea5e9' : 'white' }};"></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Main Content: Mappings --}}
        <div style="flex: 1; display: flex; flex-direction: column; gap: 0.75rem;">
            @if($this->selectedDocumentType)
                @if(session('selected_company_id') && session('selected_company_id') !== 'all')
                    {{-- Compact Mappings Table --}}
                    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                        <div style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
                            <h2 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0;">
                                {{ $this->getDocumentTypes()[$this->selectedDocumentType] }}
                            </h2>
                        </div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; width: 280px;">Jenis Pemetaan</th>
                                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Akun</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->getMappingTypes() as $mappingType => $mappingLabel)
                                    <tr style="border-bottom: 1px solid #f3f4f6; @if($loop->last) border-bottom: none; @endif">
                                        <td style="padding: 0.75rem 1rem; vertical-align: middle;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                @if($this->hasFieldChanged($this->selectedDocumentType, $mappingType))
                                                    <span style="width: 6px; height: 6px; background-color: #f59e0b; border-radius: 50%; flex-shrink: 0;"></span>
                                                @endif
                                                <span style="font-weight: 500; color: #111827; font-size: 0.875rem;">
                                                    {{ $mappingLabel }}
                                                </span>
                                            </div>
                                        </td>
                                        <td style="padding: 0.75rem 1rem; vertical-align: middle;">
                                            @php
                                                $currentValue = $this->allMappings[$this->selectedDocumentType][$mappingType]['account_id'] ?? '';
                                                $accounts = $this->getAccounts();
                                                $accountExists = isset($accounts[$currentValue]);
                                            @endphp
                                            <select
                                                wire:key="select-{{ $this->selectedDocumentType }}-{{ $mappingType }}"
                                                wire:model.live="allMappings.{{ $this->selectedDocumentType }}.{{ $mappingType }}.account_id"
                                                class="fi-input block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                style="font-size: 0.875rem; padding: 0.5rem 0.75rem; @if($this->hasFieldChanged($this->selectedDocumentType, $mappingType)) border-color: #f59e0b; @endif"
                                            >
                                                <option value="">-- Select Account --</option>
                                                @if($currentValue && !$accountExists)
                                                    <option value="{{ $currentValue }}" selected style="color: #ef4444;">⚠ Account ID {{ $currentValue }} (not found)</option>
                                                @endif
                                                @foreach($accounts as $id => $name)
                                                    <option value="{{ (string) $id }}" @if($currentValue == $id) selected @endif>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- No Company Selected --}}
                    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; padding: 2rem; text-align: center;">
                        <div style="color: #9ca3af; margin-bottom: 0.75rem;">
                            <svg style="width: 2.5rem; height: 2.5rem; margin: 0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 style="font-size: 0.875rem; font-weight: 500; color: #111827; margin-bottom: 0.25rem;">Pilih Perusahaan</h3>
                        <p style="font-size: 0.8125rem; color: #6b7280;">Please select a company from the dropdown to configure account mappings.</p>
                    </div>
                @endif
            @else
                {{-- Initial State --}}
                <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; padding: 2rem; text-align: center; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <div style="color: #9ca3af; margin-bottom: 0.75rem;">
                        <svg style="width: 3rem; height: 3rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 style="font-size: 0.875rem; font-weight: 500; color: #111827; margin-bottom: 0.25rem;">Pilih Jenis Dokumen</h3>
                    <p style="font-size: 0.8125rem; color: #6b7280;">Pilih jenis dokumen dari sidebar untuk mengatur pemetaan akunnya.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for sidebar
            const sidebar = document.querySelector('div[style*="overflow-y: auto"]');
            if (sidebar) {
                sidebar.style.scrollBehavior = 'smooth';
            }
        });
    </script>
</x-filament-panels::page>
