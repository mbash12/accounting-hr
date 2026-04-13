<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedAssetCategoryTemplate extends Model
{
    protected $fillable = [
        'name',
        'depreciation_method',
        'useful_life',
        'is_active',
        'asset_account_code',
        'accumulated_depreciation_account_code',
        'depreciation_account_code',
        'sales_account_code',
        'template_name',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'useful_life' => 'integer',
    ];

    public static function getByTemplate($templateName)
    {
        return static::where('template_name', $templateName)
            ->orderBy('name')
            ->get();
    }

    public static function getTemplateNames()
    {
        return static::distinct()
            ->pluck('template_name')
            ->filter();
    }
}
