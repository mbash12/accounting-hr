<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_order_other_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['sales_order_id', 'sort_order']);
        });

        Schema::create('sales_invoice_other_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->constrained('sales_invoices')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['sales_invoice_id', 'sort_order']);
        });

        Schema::create('purchase_order_other_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['purchase_order_id', 'sort_order']);
        });

        Schema::create('purchase_invoice_other_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_invoice_id')->constrained('purchase_invoices')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['purchase_invoice_id', 'sort_order']);
        });

        $this->backfill('sales_orders', 'sales_order_other_charges', 'sales_order_id');
        $this->backfill('sales_invoices', 'sales_invoice_other_charges', 'sales_invoice_id');
        $this->backfill('purchase_orders', 'purchase_order_other_charges', 'purchase_order_id');
        $this->backfill('purchase_invoices', 'purchase_invoice_other_charges', 'purchase_invoice_id');
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_invoice_other_charges');
        Schema::dropIfExists('purchase_order_other_charges');
        Schema::dropIfExists('sales_invoice_other_charges');
        Schema::dropIfExists('sales_order_other_charges');
    }

    private function backfill(string $parentTable, string $childTable, string $parentFk): void
    {
        $rows = DB::table($parentTable)
            ->select(['id', 'other_charges', 'other_charges_account_id'])
            ->whereNull('deleted_at')
            ->whereRaw('CAST(other_charges AS NUMERIC) > 0')
            ->get();

        $now = now();

        foreach ($rows as $row) {
            DB::table($childTable)->insert([
                $parentFk => $row->id,
                'name' => 'Other Charges',
                'account_id' => $row->other_charges_account_id,
                'amount' => $row->other_charges,
                'sort_order' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
};
