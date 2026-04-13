<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCompanyFilter
{
    /**
     * Scope to apply global company filter to queries.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithCompanyFilter(Builder $query): Builder
    {
        $selectedCompanyId = session('selected_company_id', 'all');

        // If 'all' is selected or no company is selected, don't filter
        if ($selectedCompanyId === 'all' || !$selectedCompanyId) {
            return $query;
        }

        // For models that have a direct company_id column
        if (property_exists($this, 'companyFilterColumn') && $this->companyFilterColumn) {
            return $query->where(function ($query) use ($selectedCompanyId) {
                $query->where($this->companyFilterColumn, $selectedCompanyId)
                      ->orWhereNull($this->companyFilterColumn);
            });
        }

        // For models that have a direct company_id relationship
        if (method_exists($this, 'company')) {
            return $query->whereHas('company', function (Builder $query) use ($selectedCompanyId) {
                $query->where('companies.id', $selectedCompanyId)
                      ->orWhereNull('companies.id');
            });
        }

        // For models that have a many-to-many relationship with companies
        if (method_exists($this, 'companies')) {
            return $query->whereHas('companies', function (Builder $query) use ($selectedCompanyId) {
                $query->where('companies.id', $selectedCompanyId);
            });
        }

        // For models with custom company filter logic
        if (method_exists($this, 'applyCompanyFilter')) {
            return $this->applyCompanyFilter($query, $selectedCompanyId);
        }

        // Default: don't filter if we can't determine how to filter
        return $query;
    }

    /**
     * Apply company filter to a custom column.
     *
     * @param Builder $query
     * @param string $columnName
     * @return Builder
     */
    protected function applyCustomCompanyFilter(Builder $query, string $columnName, string|int $selectedCompanyId): Builder
    {
        return $query->where(function ($query) use ($columnName, $selectedCompanyId) {
            $query->where($columnName, $selectedCompanyId)
                  ->orWhereNull($columnName);
        });
    }
}
