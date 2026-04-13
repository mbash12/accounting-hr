<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Closure;

class AccountSearchTable extends Field
{
    protected string $view = 'filament.forms.components.account-search-table';
    
    protected int|Closure|null $companyId = null;
    protected string|Closure|null $componentId = null;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->dehydrated(false);
        $this->hiddenLabel();
    }
    
    public function companyId(int|Closure|null $companyId): static
    {
        $this->companyId = $companyId;
        
        return $this;
    }
    
    public function componentId(string|Closure|null $componentId): static
    {
        $this->componentId = $componentId;
        
        return $this;
    }
    
    public function getCompanyId(): ?int
    {
        return $this->evaluate($this->companyId);
    }
    
    public function getComponentId(): ?string
    {
        return $this->evaluate($this->componentId);
    }
    
    public function getViewData(): array
    {
        $data = parent::getViewData();
        $data['companyId'] = $this->getCompanyId();
        $data['componentId'] = $this->getComponentId();
        return $data;
    }
}

