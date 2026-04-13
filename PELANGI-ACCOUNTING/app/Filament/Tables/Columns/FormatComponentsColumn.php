<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Model;
use Closure;

class FormatComponentsColumn extends Column
{
    protected string $view = 'filament.tables.columns.format-components';

    protected ?Closure $formatStateUsing = null;

    public function formatStateUsing(?Closure $callback): static
    {
        $this->formatStateUsing = $callback;

        return $this;
    }

    protected function formatState(mixed $state): string
    {
        if ($this->formatStateUsing) {
            $state = $this->evaluate($this->formatStateUsing, [
                'state' => $state,
            ]);
        }

        return (string) $state;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(function (Model $record): string {
            if ($record->format_components && is_array($record->format_components)) {
                $labels = [];
                foreach ($record->format_components as $component) {
                    switch ($component) {
                        case 'prefix':
                            $labels[] = 'Prefix';
                            break;
                        case 'year_full':
                            $labels[] = 'YYYY';
                            break;
                        case 'year_short':
                            $labels[] = 'YY';
                            break;
                        case 'month_full':
                            $labels[] = 'MMMM';
                            break;
                        case 'month_medium':
                            $labels[] = 'MMM';
                            break;
                        case 'month_short':
                            $labels[] = 'MM';
                            break;
                        case 'month_numeric':
                            $labels[] = 'M';
                            break;
                        case 'number':
                            $labels[] = 'Number';
                            break;
                    }
                }
                return implode(' + ', $labels);
            }
            
            // Parse existing format to extract components
            $format = $record->format ?? '';
            $components = [];
            
            if (str_contains($format, '{prefix}')) $components[] = 'Prefix';
            if (str_contains($format, '{year_short}')) $components[] = 'YY';
            if (str_contains($format, '{year}')) $components[] = 'YYYY';
            if (str_contains($format, '{month_full}')) $components[] = 'MMMM';
            if (str_contains($format, '{month_medium}')) $components[] = 'MMM';
            if (str_contains($format, '{month}')) $components[] = 'MM';
            if (str_contains($format, '{month_numeric}')) $components[] = 'M';
            if (str_contains($format, '{number}')) $components[] = 'Number';
            
            return implode(' + ', $components);
        });
    }
}