<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class CompanyFilterService
{
    /**
     * Apply company filter to a query based on current session selection.
     *
     * @param Builder $query
     * @param string $columnName The column name to filter on (default: 'company_id')
     * @return Builder
     */
    public static function applyCompanyFilter(Builder $query, string $columnName = 'company_id'): Builder
    {
        $selectedCompanyId = session('selected_company_id', 'all');

        // If 'all' is selected or no company is selected, don't filter
        if ($selectedCompanyId === 'all' || !$selectedCompanyId) {
            return $query;
        }

        // Apply the company filter - include records that belong to the selected company OR are shared (null company_id)
        return $query->where(function ($query) use ($columnName, $selectedCompanyId) {
            $query->where($columnName, $selectedCompanyId)
                  ->orWhereNull($columnName);
        });
    }

    /**
     * Get the currently selected company ID from session.
     *
     * @return string|int|null
     */
    public static function getSelectedCompanyId()
    {
        return session('selected_company_id', 'all');
    }

    /**
     * Set the selected company ID in session.
     *
     * @param string|int|null $companyId
     * @return void
     */
    public static function setSelectedCompanyId($companyId): void
    {
        session(['selected_company_id' => $companyId]);
    }

    /**
     * Check if a specific company is currently selected.
     *
     * @param int $companyId
     * @return bool
     */
    public static function isCompanySelected(int $companyId): bool
    {
        $selected = self::getSelectedCompanyId();
        return $selected !== 'all' && (int)$selected === $companyId;
    }

    /**
     * Get the currently selected company model.
     *
     * @return \App\Models\Company|null
     */
    public static function getSelectedCompany()
    {
        $companyId = self::getSelectedCompanyId();

        if ($companyId === 'all' || !$companyId) {
            return null;
        }

        return \App\Models\Company::find($companyId);
    }

    /**
     * Apply company filter to a relationship query.
     *
     * @param Builder $query
     * @param string $relationName
     * @param string $foreignKey
     * @return Builder
     */
    public static function applyCompanyFilterToRelation(Builder $query, string $relationName, string $foreignKey = 'company_id'): Builder
    {
        $selectedCompanyId = session('selected_company_id', 'all');

        if ($selectedCompanyId === 'all' || !$selectedCompanyId) {
            return $query;
        }

        return $query->whereHas($relationName, function (Builder $query) use ($foreignKey, $selectedCompanyId) {
            $query->where($foreignKey, $selectedCompanyId)
                  ->orWhereNull($foreignKey);
        });
    }
}
