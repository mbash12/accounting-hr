<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxTemplate extends Model
{
    protected $fillable = [
        'name',
        'code',
        'tax_percentage',
        'tax_type',
        'is_purchase_tax',
        'is_sales_tax',
        'effective_date',
        'expiry_date',
        'compound_tax',
        'is_active',
        'purchase_account_code',
        'sales_account_code',
        'template_name',
        'notes',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
        'is_purchase_tax' => 'boolean',
        'is_sales_tax' => 'boolean',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'compound_tax' => 'boolean',
        'is_active' => 'boolean',
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
