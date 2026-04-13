<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SelectWithModal extends Select
{
    protected ?string $modalHeading = null;
    protected ?array $modalTableColumns = null;
    protected ?string $modalTableSearchColumn = null;
    protected array $modalTableSearchColumns = [];
    protected ?string $modalTableRelationship = null;
    protected ?string $modalTableRecordTitleAttribute = null;
    protected bool $modalTableAllowCreate = false;
    protected ?\Closure $modalTableCreateFormCallback = null;
    protected ?\Closure $modalTableModifyQueryUsing = null;
    
    public function modalHeading(?string $heading): static
    {
        $this->modalHeading = $heading;
        return $this;
    }
    
    public function modalTable(array $columns): static
    {
        $this->modalTableColumns = $columns;
        return $this;
    }
    
    public function modalTableSearchColumn(string $column): static
    {
        $this->modalTableSearchColumn = $column;
        $this->modalTableSearchColumns = [$column];
        return $this;
    }
    
    public function modalTableSearchColumns(array $columns): static
    {
        $this->modalTableSearchColumns = $columns;
        return $this;
    }
    
    public function modalTableRelationship(string $relationship, string $titleAttribute): static
    {
        $this->modalTableRelationship = $relationship;
        $this->modalTableRecordTitleAttribute = $titleAttribute;
        return $this;
    }
    
    public function modalTableAllowCreate(bool $allow = true, ?\Closure $callback = null): static
    {
        $this->modalTableAllowCreate = $allow;
        $this->modalTableCreateFormCallback = $callback;
        return $this;
    }
    
    public function modalTableModifyQueryUsing(?\Closure $callback): static
    {
        $this->modalTableModifyQueryUsing = $callback;
        return $this;
    }
    
    public function getModalHeading(): string
    {
        return $this->modalHeading ?? $this->getLabel();
    }
    
    public function getModalTableColumns(): ?array
    {
        return $this->modalTableColumns;
    }
    
    public function getModalTableSearchColumns(): array
    {
        return $this->modalTableSearchColumns;
    }
    
    public function getModalTableRelationship(): ?string
    {
        return $this->modalTableRelationship;
    }
    
    public function getModalTableRecordTitleAttribute(): ?string
    {
        return $this->modalTableRecordTitleAttribute;
    }
    
    public function getModalTableAllowCreate(): bool
    {
        return $this->modalTableAllowCreate;
    }
    
    public function getModalTableCreateFormCallback(): ?\Closure
    {
        return $this->modalTableCreateFormCallback;
    }
    
    public function getModalTableModifyQueryUsing(): ?\Closure
    {
        return $this->modalTableModifyQueryUsing;
    }
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->suffixAction(
            fn ($component) => Action::make('openModal')
                ->icon('heroicon-o-magnifying-glass')
                ->iconButton()
                ->modalHeading($component->getModalHeading())
                ->modalSubmitActionLabel('Select')
                ->modalWidth('5xl')
                ->fillForm(function ($component) {
                    $state = $component->getState();
                    return $state ? ['record_id' => $state] : [];
                })
                ->form(function ($component) {
                    $relationship = $component->getModalTableRelationship();
                    $titleAttribute = $component->getModalTableRecordTitleAttribute();
                    
                    if (!$relationship || !$titleAttribute) {
                        return [];
                    }
                    
                    $field = \Filament\Forms\Components\ModalTableSelect::make('record_id')
                        ->label('Search and Select')
                        ->relationship($relationship, $titleAttribute)
                        ->tableConfiguration(\App\Forms\Components\CustomerSearchTable::class);
                    
                    return [$field];
                })
                ->action(function (array $data, $component) {
                    if (isset($data['record_id'])) {
                        $component->state($data['record_id']);
                    }
                })
        );
    }
}