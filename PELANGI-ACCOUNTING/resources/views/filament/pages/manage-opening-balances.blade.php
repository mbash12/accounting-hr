<x-filament-panels::page>
    @if(session('selected_company_id') === 'all' || !session('selected_company_id'))
        <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 400px; text-align: center;">
            <div style="max-width: 400px;">
                <div style="width: 80px; height: 80px; background: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">{{ __('Select a Company') }}</h3>
                <p style="color: #6b7280; margin: 0 0 1.5rem 0; line-height: 1.5;">
                    {{ __('Please select a specific company from the company selector to view and manage opening balances.') }}
                </p>
            </div>
        </div>
    @else
        <div class="opening-balances-container space-y-4">
            @php
                $totalDebit = $this->getTotalDebit();
                $totalCredit = $this->getTotalCredit();
                $hasBalances = $totalDebit > 0 || $totalCredit > 0;
            @endphp

            @if($hasBalances)
            <!-- Summary Cards -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; border: 1px solid #e5e7eb;">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Total Debit</div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">
                        {{ number_format($totalDebit, 0, ',', '.') }}
                    </div>
                </div>
                <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; border: 1px solid #e5e7eb;">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Total Credit</div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #dc2626;">
                        {{ number_format($totalCredit, 0, ',', '.') }}
                    </div>
                </div>
                @php $diff = $this->getDifference(); @endphp
                <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; border: 1px solid {{ abs($diff) < 0.01 ? '#10b981' : '#ef4444' }};">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Selisih</div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: {{ abs($diff) < 0.01 ? '#059669' : '#dc2626' }};">
                        {{ number_format($diff, 0, ',', '.') }}
                        @if(abs($diff) < 0.01)
                            <span style="font-size: 0.875rem;">✓</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Filters -->
            <div style="display: flex; gap: 1rem; background: white; border-radius: 0.75rem; padding: 1rem; border: 1px solid #e5e7eb;">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Search Account</label>
                    <input
                        type="text"
                        wire:model.live="searchQuery"
                        placeholder="Search account code or name..."
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; font-size: 0.875rem;"
                    />
                </div>
                <div style="width: 200px;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Account Type</label>
                    <select
                        wire:model.live="filterAccountType"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; font-size: 0.875rem; background: white;"
                    >
                        <option value="">All Types</option>
                        @foreach($this->getAccountTypes() as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="opening-balances-table" style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; background: linear-gradient(to right, #f9fafb, #f3f4f6);">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin: 0;">
                        {{ __('Opening Balances') }}
                        <span style="font-size: 0.875rem; font-weight: 400; color: #6b7280;">({{ count($this->openingBalanceData) }} accounts)</span>
                    </h3>
                </div>

                @if(isset($this->openingBalanceData) && count($this->openingBalanceData) > 0)
                    <div style="overflow-x: auto; max-height: 600px; overflow-y: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="position: sticky; top: 0; z-index: 10;">
                                <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; font-weight: 600; color: #374151; width: 120px;">{{ __('Code') }}</th>
                                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; font-weight: 600; color: #374151;">{{ __('Account Name') }}</th>
                                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; font-weight: 600; color: #374151; width: 100px;">{{ __('Type') }}</th>
                                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; font-weight: 600; color: #374151; width: 150px;">{{ __('Debit') }}</th>
                                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; font-weight: 600; color: #374151; width: 150px;">{{ __('Credit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->openingBalanceData as $index => $row)
                                    <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;" 
                                        onmouseover="this.style.backgroundColor='#f9fafb'" 
                                        onmouseout="this.style.backgroundColor='transparent'">
                                        <td style="padding: 0.5rem 1rem; font-family: 'SF Mono', Monaco, monospace; font-size: 0.8rem; color: #6b7280;">
                                            {{ $row['account_code'] }}
                                        </td>
                                        <td style="padding: 0.5rem 1rem; font-size: 0.875rem; color: #374151;">
                                            {{ $row['account_name'] }}
                                        </td>
                                        <td style="padding: 0.5rem 1rem; font-size: 0.75rem; color: #6b7280;">
                                            {{ ucfirst($row['account_type'] ?? '-') }}
                                        </td>
                                        <td style="padding: 0.5rem 1rem; text-align: right;">
                                            <input
                                                type="number"
                                                step="0.01"
                                                wire:model.blur="openingBalanceData.{{ $index }}.debit_amount"
                                                style="width: 100%; padding: 0.375rem 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; font-size: 0.875rem; text-align: right;"
                                                placeholder="0"
                                            />
                                        </td>
                                        <td style="padding: 0.5rem 1rem; text-align: right;">
                                            <input
                                                type="number"
                                                step="0.01"
                                                wire:model.blur="openingBalanceData.{{ $index }}.credit_amount"
                                                style="width: 100%; padding: 0.375rem 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; font-size: 0.875rem; text-align: right;"
                                                placeholder="0"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="position: sticky; bottom: 0; background: #f3f4f6;">
                                <tr style="border-top: 2px solid #e5e7eb; font-weight: 600;">
                                    <td colspan="3" style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; color: #374151;">Total:</td>
                                    <td style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; color: #059669;">
                                        {{ number_format($this->getTotalDebit(), 0, ',', '.') }}
                                    </td>
                                    <td style="padding: 0.75rem 1rem; text-align: right; font-size: 0.875rem; color: #dc2626;">
                                        {{ number_format($this->getTotalCredit(), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="empty-state" style="padding: 3rem; text-align: center;">
                        <div style="width: 64px; height: 64px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <svg style="width: 32px; height: 32px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">{{ __('No Accounts Found') }}</h3>
                        <p style="color: #6b7280; margin: 0; line-height: 1.5;">
                            {{ __('No accounts match your filter criteria.') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</x-filament-panels::page>
