<div class="opening-balance-summary" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
    <div class="summary-card" style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 0.5rem; padding: 1rem;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 0.875rem; font-weight: 500; color: #92400e; margin-bottom: 0.25rem;">
                    {{ __('Total Debits') }}
                </div>
                <div style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
                    {{ number_format($debitTotal, 2) }} {{ $currency }}
                </div>
            </div>
            <div style="width: 2.5rem; height: 2.5rem; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8l-8 8-8-8"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="summary-card" style="background: #d1fae5; border: 1px solid #10b981; border-radius: 0.5rem; padding: 1rem;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 0.875rem; font-weight: 500; color: #065f46; margin-bottom: 0.25rem;">
                    {{ __('Total Credits') }}
                </div>
                <div style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
                    {{ number_format($creditTotal, 2) }} {{ $currency }}
                </div>
            </div>
            <div style="width: 2.5rem; height: 2.5rem; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20v-16m8 8l-8-8-8 8"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="summary-card" style="background: #dbeafe; border: 1px solid #3b82f6; border-radius: 0.5rem; padding: 1rem;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 0.875rem; font-weight: 500; color: #1e40af; margin-bottom: 0.25rem;">
                    {{ __('Accounts with Balances') }}
                </div>
                <div style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
                    {{ $accountCount }}
                </div>
            </div>
            <div style="width: 2.5rem; height: 2.5rem; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if(abs($debitTotal - $creditTotal) > 0.01)
        <div class="summary-card" style="background: #fee2e2; border: 1px solid #ef4444; border-radius: 0.5rem; padding: 1rem;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.875rem; font-weight: 500; color: #991b1b; margin-bottom: 0.25rem;">
                        {{ __('Balance Difference') }}
                    </div>
                    <div style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
                        {{ number_format(abs($debitTotal - $creditTotal), 2) }} {{ $currency }}
                    </div>
                    <div style="font-size: 0.75rem; color: #991b1b; margin-top: 0.25rem;">
                        {{ __('Debits and credits should be equal') }}
                    </div>
                </div>
                <div style="width: 2.5rem; height: 2.5rem; background: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    @endif
</div>