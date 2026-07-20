<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @if(!$this->hasCompanySelected())
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 0.75rem; padding: 1rem; color: #92400e; font-size: 0.875rem;">
                {{ __('Please select a specific company from the global selector.') }}
            </div>
        @else
            <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 0.75rem; padding: 1rem; color: #9f1239; font-size: 0.875rem;">
                {{ __('Destructive actions for the active company only: :company. Choose Cascade delete or Nullify FK, then confirm with the company name and CLEAR.', ['company' => $this->companyName()]) }}
            </div>

            {{ $this->table }}
        @endif
    </div>
</x-filament-panels::page>
