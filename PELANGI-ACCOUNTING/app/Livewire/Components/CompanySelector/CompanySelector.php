<?php

namespace App\Livewire\Components\CompanySelector;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CompanySelector extends Component
{
    public $selectedCompany;

    public function mount(): void
    {
        $this->selectedCompany = session('selected_company_id');

        // If no company is selected, get the user's first company
        if (!$this->selectedCompany && Auth::check()) {
            $firstCompany = Auth::user()->companies()->first();
            if ($firstCompany) {
                $this->selectedCompany = $firstCompany->id;
                session(['selected_company_id' => $firstCompany->id]);
            } else {
                $this->selectedCompany = null;
            }
        } elseif (!$this->selectedCompany) {
            $this->selectedCompany = null;
        }
    }

    public function selectCompany($value): void
    {
        $this->selectedCompany = $value;
        session(['selected_company_id' => $value]);

        // Dispatch event to notify other components
        $this->dispatch('company-changed', companyId: $value);

        // Redirect to refresh the current page
        $this->redirect(request()->header('Referer', route('filament.main.pages.dashboard')));
    }

    public function render()
    {
        $user = Auth::user();
        $companies = $user ? $user->companies()->orderBy('name')->get() : collect();

        return view('livewire.components.company-selector.company-selector', [
            'companies' => $companies,
        ]);
    }

    public function canView(): bool
    {
        return Auth::check() && Auth::user()->companies()->count() > 0;
    }
}
