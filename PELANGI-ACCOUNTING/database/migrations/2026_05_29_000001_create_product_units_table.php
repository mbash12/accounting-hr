<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            $table->decimal('conversion_factor', 15, 6)->default(1);
            $table->boolean('is_purchase_unit')->default(false);
            $table->boolean('is_sales_unit')->default(false);
            $table->foreignId('company_id');
            $table->foreignId('created_by_user_id');
            $table->timestamps();
            $table->softDeletes();
        });

        // Partial unique index for PostgreSQL (ignores soft-deleted rows)
        DB::statement('CREATE UNIQUE INDEX product_units_product_id_unit_id_unique ON product_units (product_id, unit_id) WHERE deleted_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
