<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;

class ToggleableField extends Field
{
    protected string $view = 'filament.components.toggleable-field';

    const MAIN_FIELD_SCHEMA_KEY = 'main_field';
    const SECONDARY_FIELD_SCHEMA_KEY = 'secondary_field';

    protected bool $defaultShowSecondary = false;

    protected ?Component $mainField = null;
    protected ?Component $secondaryField = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([
            'main' => null,
            'secondary' => null,
        ]);
    }

    public function mainField(Component $field): static
    {
        $field->statePath('main');
        $this->mainField = $field;
        $this->childComponents([$field], static::MAIN_FIELD_SCHEMA_KEY);

        return $this;
    }

    public function secondaryField(Component $field): static
    {
        $field->statePath('secondary');
        $this->secondaryField = $field;
        $this->childComponents([$field], static::SECONDARY_FIELD_SCHEMA_KEY);

        return $this;
    }

    public function defaultShowSecondary(bool $show = false): static
    {
        $this->defaultShowSecondary = $show;

        return $this;
    }

    public function getDefaultShowSecondary(): bool
    {
        return $this->defaultShowSecondary;
    }

    public function getMainFieldComponent(): ?Component
    {
        return $this->mainField;
    }

    public function getSecondaryFieldComponent(): ?Component
    {
        return $this->secondaryField;
    }

    public function getMainFieldValue()
    {
        return $this->getState()['main'] ?? null;
    }

    public function getSecondaryFieldValue()
    {
        return $this->getState()['secondary'] ?? null;
    }
}