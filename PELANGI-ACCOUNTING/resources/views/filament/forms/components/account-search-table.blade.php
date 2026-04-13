@php
    use App\Models\Account;
    
    $companyId = $companyId ?? null;
    $componentId = $componentId ?? null;
    
    $selectedCompanyId = session('selected_company_id');
    
    $query = Account::where('is_header', false)
        ->where('is_active', true)
        ->whereNull('deleted_at')
        ->with('classification');
    
    if ($companyId) {
        $query->where(function ($q) use ($companyId) {
            $q->where('company_id', $companyId)
                ->orWhereNull('company_id');
        });
    } elseif ($selectedCompanyId && $selectedCompanyId !== 'all') {
        $query->where(function ($q) use ($selectedCompanyId) {
            $q->where('company_id', $selectedCompanyId)
                ->orWhereNull('company_id');
        });
    }
    
    $accounts = $query->orderBy('code')->get()->map(function ($account) {
        $classificationName = '-';
        if ($account->relationLoaded('classification') && $account->classification) {
            $classificationName = $account->classification->name;
        } elseif ($account->classification_id) {
            $classification = Account::find($account->classification_id);
            if ($classification) {
                $classificationName = $classification->name;
            }
        }
        
        return [
            'id' => $account->id,
            'code' => $account->code,
            'name' => $account->name,
            'classification_name' => $classificationName,
            'is_active' => (bool) $account->is_active,
        ];
    })->values()->all();
    
    $accounts = $accounts ?? [];
@endphp

<div 
    x-data="{
        selectedAccountId: null,
        searchTerm: '',
        accounts: @js($accounts),
        get filteredAccounts() {
            if (!this.searchTerm) return this.accounts;
            const term = this.searchTerm.toLowerCase();
            return this.accounts.filter(account => 
                (account.code || '').toLowerCase().includes(term) || 
                (account.name || '').toLowerCase().includes(term) ||
                (account.classification_name || '').toLowerCase().includes(term)
            );
        },
        selectAccount(accountId) {
            this.selectedAccountId = accountId;
            this.updateHiddenInput();
        },
        updateHiddenInput() {
            if (this.selectedAccountId) {
                const hiddenInput = document.querySelector('input[name=\'selected_account_id\']');
                if (hiddenInput) {
                    hiddenInput.value = this.selectedAccountId;
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }
    }" 
    class="w-full bg-white"
>
    <!-- Top Actions Bar -->
    <div class="flex items-center justify-end gap-2 pb-6">
        <!-- Search Input -->
        <div class="relative">
            <input 
                type="text" 
                x-model="searchTerm"
                placeholder=""
                class="w-64 h-9 pl-3 pr-10 rounded border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
            />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <x-heroicon-o-magnifying-glass class="h-4 w-4 text-gray-400" />
            </div>
        </div>

        <!-- Refresh Button -->
        <button type="button" class="p-2 border border-gray-300 rounded hover:bg-gray-50 bg-white shadow-sm transition-colors">
            <x-heroicon-o-arrow-path class="h-4 w-4 text-gray-600" />
        </button>

        <!-- Filter Button -->
        <button type="button" class="p-2 border border-gray-300 rounded hover:bg-gray-50 bg-white shadow-sm transition-colors px-3">
            <x-heroicon-o-funnel class="h-4 w-4 text-gray-600" />
        </button>
    </div>

    <!-- Table Container -->
    <div class="border-t border-gray-100">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-100 text-[10.5px] font-bold text-gray-500 uppercase tracking-widest">
                    <th class="py-4 text-left">
                        <div class="flex items-center gap-1">
                            KODE
                            <x-heroicon-m-arrow-small-up class="h-4 w-4 text-blue-500" />
                        </div>
                    </th>
                    <th class="py-4 text-left">NAMA</th>
                    <th class="py-4 text-left">SUBKLASIFIKASI</th>
                    <th class="py-4 text-left">STATUS</th>
                    <th class="py-4 text-left w-6"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <template x-for="account in filteredAccounts" :key="account.id">
                    <tr 
                        @click="selectAccount(account.id)"
                        :class="selectedAccountId === account.id ? 'bg-[#eef8ff]' : 'hover:bg-gray-25'"
                        class="cursor-pointer transition-colors group"
                    >
                        <td class="py-4 text-[13.5px] text-gray-600 font-normal" x-text="account.code"></td>
                        <td class="py-4 text-[13.5px] text-gray-800 font-medium" x-text="account.name"></td>
                        <td class="py-4 text-[13.5px] text-gray-500 font-normal" x-text="account.classification_name"></td>
                        <td class="py-4">
                            <span 
                                :class="account.is_active ? 'bg-[#eafaf1] text-[#2ebd71]' : 'bg-red-50 text-red-600'"
                                class="px-3 py-1 text-[10.5px] font-bold rounded-md"
                                x-text="account.is_active ? 'Aktif' : 'Tidak Aktif'"
                            ></span>
                        </td>
                        <td class="py-4 text-right">
                            <x-heroicon-m-ellipsis-vertical class="h-5 w-5 text-gray-300" />
                        </td>
                    </tr>
                </template>
                
                <!-- Empty State -->
                <template x-if="filteredAccounts.length === 0">
                    <tr>
                        <td colspan="5" class="py-12 text-center text-sm text-gray-500 bg-white">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <x-heroicon-o-magnifying-glass class="h-8 w-8 text-gray-200" />
                                <span x-show="accounts.length === 0">Tidak ada akun tersedia</span>
                                <span x-show="accounts.length > 0 && searchTerm">Tidak ada hasil ditemukan</span>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

