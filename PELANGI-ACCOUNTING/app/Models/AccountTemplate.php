<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountTemplate extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'account_type',
        'classification_type',
        'is_header',
        'is_cash_bank',
        'is_active',
        'cash_flow',
        'parent_code',
        'level',
        'template_name',
        'notes',
    ];

    protected $casts = [
        'is_header' => 'boolean',
        'is_cash_bank' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function children()
    {
        return $this->hasMany(AccountTemplate::class, 'parent_code', 'code')
            ->orderBy('code');
    }

    public function parent()
    {
        return $this->belongsTo(AccountTemplate::class, 'parent_code', 'code');
    }

    public static function getByTemplate($templateName)
    {
        return static::where('template_name', $templateName)
            ->orderBy('code')
            ->get();
    }

    public static function getTemplateNames()
    {
        return static::distinct()
            ->pluck('template_name')
            ->filter();
    }
}
