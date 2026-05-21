<?php

namespace App\Models;

use App\Models\DocumentNumbering;
use App\Traits\HasCompanyFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory, SoftDeletes, HasCompanyFilter;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by_user_id = Auth::id();
            }
        });

        static::created(function ($model) {
            // Create default document numbering configurations for the new company
            $model->createDefaultDocumentNumberings();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'tax_id',
        'is_ppn',
        'tax_document',
        'fiscal_year_start',
        'fiscal_year_end',
        'tax_period',
        'is_active',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'delivery_address_line_1',
        'delivery_address_line_2',
        'delivery_city',
        'delivery_state',
        'delivery_postal_code',
        'delivery_country',
        'photo',
        'business_type_id',
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
            'fiscal_year_start' => 'date',
            'fiscal_year_end' => 'date',
            'is_active' => 'boolean',
            'is_ppn' => 'boolean',
            'business_type_id' => 'integer',
            'created_by_user_id' => 'integer',
        ];
    }

    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create default document numbering configurations for this company
     */
    public function createDefaultDocumentNumberings(): void
    {
        $documentTypes = $this->getDocumentTypesWithDefaults();

        foreach ($documentTypes as $documentType => $config) {
            DocumentNumbering::create([
                'document_type' => $documentType,
                'prefix' => $config['prefix'],
                'format' => $config['format'],
                'format_components' => $config['format_components'],
                'next_number' => 0,
                'reset_period' => 'never',
                'is_active' => true,
                'company_id' => $this->id,
                'created_by_user_id' => $this->created_by_user_id,
            ]);
        }
    }

    /**
     * Get document types with their default configurations
     */
    private function getDocumentTypesWithDefaults(): array
    {
        return [
            'sales_invoice' => [
                'prefix' => 'INV',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'purchase_invoice' => [
                'prefix' => 'SUP',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'cash_receipt' => [
                'prefix' => 'CR',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'cash_disbursement' => [
                'prefix' => 'CD',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'journal_entry' => [
                'prefix' => 'JE',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'sales_order' => [
                'prefix' => 'SO',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'purchase_order' => [
                'prefix' => 'PO',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'product' => [
                'prefix' => 'PRD',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'product_group' => [
                'prefix' => 'PRG',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'fixed_asset' => [
                'prefix' => 'FA',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'unit_measurement' => [
                'prefix' => 'UM',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'bank_account' => [
                'prefix' => 'BA',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'warehouse' => [
                'prefix' => 'WH',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'department' => [
                'prefix' => 'DPT',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'tax' => [
                'prefix' => 'TAX',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'expedition' => [
                'prefix' => 'EXP',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'contact' => [
                'prefix' => 'CT',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'bank' => [
                'prefix' => 'BK',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'business_type' => [
                'prefix' => 'BT',
                'format' => '{CODE}{NUMBER}',
                'format_components' => ['prefix', 'number'],
            ],
            'advance_disbursement' => [
                'prefix' => 'ADV',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'advance_receipt' => [
                'prefix' => 'AR',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'cash_transfer' => [
                'prefix' => 'TRF',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'check_disbursement' => [
                'prefix' => 'CHK',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'delivery_document' => [
                'prefix' => 'DO',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'fixed_asset_transaction' => [
                'prefix' => 'FAT',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'goods_receipt' => [
                'prefix' => 'GR',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'inventory_adjustment' => [
                'prefix' => 'IA',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'overpayment_receipt' => [
                'prefix' => 'OR',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'overpayment_refund' => [
                'prefix' => 'RF',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'payable_payment' => [
                'prefix' => 'PP',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'purchase_return' => [
                'prefix' => 'PRN',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'receivable_payment' => [
                'prefix' => 'RP',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'sales_return' => [
                'prefix' => 'SRN',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'stock_opname' => [
                'prefix' => 'SO',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
            'warehouse_transfer' => [
                'prefix' => 'WT',
                'format' => '{CODE}{YYYY}{NUMBER}',
                'format_components' => ['prefix', 'year_full', 'number'],
            ],
        ];
    }


}
