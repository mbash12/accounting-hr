<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentNumbering extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_type',
        'prefix',
        'format',
        'format_components',
        'next_number', // Used as beginning number, not incremented
        'reset_period',
        'is_active',
        'company_id',
        'created_by_user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'is_active' => 'boolean',
            'company_id' => 'integer',
            'created_by_user_id' => 'integer',
            'format_components' => 'array',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getGeneratedFormatAttribute(): string
    {
        if (!$this->format_components || !is_array($this->format_components)) {
            return $this->format ?? '{CODE}{NUMBER}';
        }

        $formatParts = [];
        foreach ($this->format_components as $component) {
            switch ($component) {
                case 'prefix':
                    $formatParts[] = '{CODE}';
                    break;
                case 'year_full':
                    $formatParts[] = '{YYYY}';
                    break;
                case 'year_short':
                    $formatParts[] = '{YY}';
                    break;
                case 'month_full':
                    $formatParts[] = '{MMMM}';
                    break;
                case 'month_medium':
                    $formatParts[] = '{MMM}';
                    break;
                case 'month_short':
                    $formatParts[] = '{MM}';
                    break;
                case 'month_numeric':
                    $formatParts[] = '{M}';
                    break;
                case 'number':
                    $formatParts[] = '{NUMBER}';
                    break;
            }
        }

        return implode('', $formatParts);
    }

    public function generateDocumentNumber(): string
    {
        $format = $this->getGeneratedFormatAttribute();
        $now = now();
        
        $replacements = [
            '{CODE}' => $this->prefix ?? '',
            '{YYYY}' => $now->format('Y'),
            '{YY}' => $now->format('y'),
            '{MMMM}' => $now->format('F'), // January, February, etc.
            '{MMM}' => $now->format('M'), // Jan, Feb, etc.
            '{MM}' => $now->format('m'), // 01, 02, etc.
            '{M}' => $now->format('n'), // 1, 2, etc.
            '{NUMBER}' => str_pad($this->next_number, 6, '0', STR_PAD_LEFT),
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }

    public function formatCode(int $number): string
    {
        $format = $this->getGeneratedFormatAttribute();
        $now = now();
        $replacements = [
            '{CODE}' => $this->prefix ?? '',
            '{YYYY}' => $now->format('Y'),
            '{YY}' => $now->format('y'),
            '{MMMM}' => $now->format('F'),
            '{MMM}' => $now->format('M'),
            '{MM}' => $now->format('m'),
            '{M}' => $now->format('n'),
            '{NUMBER}' => str_pad($number, 6, '0', STR_PAD_LEFT),
        ];
        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->format_components && is_array($model->format_components)) {
                $model->format = $model->getGeneratedFormatAttribute();
            }
        });
    }
}
