<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\ModalTableSelect;
use Filament\Actions\Action;
use Filament\Forms\Components\Select as FilamentSelect;
use Closure;

class CustomSelect extends ModalTableSelect
{
    protected array | Closure $quickSelectOptions = [];
    
    protected bool | Closure $enableQuickSelect = true;
    
    protected string | Closure $quickSelectLabel = 'Quick Select';
    
    protected string | Closure $quickSelectPlaceholder = 'Choose from common options...';
    
    public function quickSelectOptions(array | Closure $options): static
    {
        $this->quickSelectOptions = $options;
        
        return $this;
    }
    
    public function enableQuickSelect(bool | Closure $condition = true): static
    {
        $this->enableQuickSelect = $condition;
        
        return $this;
    }
    
    public function quickSelectLabel(string | Closure $label): static
    {
        $this->quickSelectLabel = $label;
        
        return $this;
    }
    
    public function quickSelectPlaceholder(string | Closure $placeholder): static
    {
        $this->quickSelectPlaceholder = $placeholder;
        
        return $this;
    }
    
    public function getQuickSelectOptions(): array
    {
        return $this->evaluate($this->quickSelectOptions);
    }
    
    public function isQuickSelectEnabled(): bool
    {
        return $this->evaluate($this->enableQuickSelect) && !empty($this->getQuickSelectOptions());
    }
    
    public function getQuickSelectLabel(): string
    {
        return $this->evaluate($this->quickSelectLabel);
    }
    
    public function getQuickSelectPlaceholder(): string
    {
        return $this->evaluate($this->quickSelectPlaceholder);
    }
    
    protected function selectAction(Closure $callback): static
    {
        parent::selectAction(function (Action $action) use ($callback) {
            $callback($action);
            
            if ($this->isQuickSelectEnabled()) {
                $action->schema(function () {
                    $quickSelectSchema = [
                        \Filament\Schemas\Components\Section::make($this->getQuickSelectLabel())
                            ->description('Choose from frequently used options or search below')
                            ->schema([
                                FilamentSelect::make('quick_selection')
                                    ->label($this->getQuickSelectLabel())
                                    ->placeholder($this->getQuickSelectPlaceholder())
                                    ->options($this->getQuickSelectOptions())
                                    ->searchable()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if (!empty($state)) {
                                            $this->state($state);
                                            $this->dispatch('close-modal', id: $this->getId());
                                        }
                                    })
                                    ->live(),
                            ])
                            ->compact(),
                    ];
                    
                    return $quickSelectSchema;
                });
            }
        });
        
        return $this;
    }
}
