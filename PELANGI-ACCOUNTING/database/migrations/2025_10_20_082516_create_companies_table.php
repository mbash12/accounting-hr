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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->string('tax_id', 50)->nullable();
            $table->date('fiscal_year_start');
            $table->date('fiscal_year_end');
            $table->enum('tax_period', ["monthly","quarterly","semi_annual","annual"])->default('monthly');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->string('billing_address_line_1', 255)->nullable();
            $table->string('billing_address_line_2', 255)->nullable();
            $table->string('billing_city', 100)->nullable();
            $table->string('billing_state', 100)->nullable();
            $table->string('billing_postal_code', 20)->nullable();
            $table->string('billing_country', 100)->nullable();
            $table->string('delivery_address_line_1', 255)->nullable();
            $table->string('delivery_address_line_2', 255)->nullable();
            $table->string('delivery_city', 100)->nullable();
            $table->string('delivery_state', 100)->nullable();
            $table->string('delivery_postal_code', 20)->nullable();
            $table->string('delivery_country', 100)->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('business_type_id');
            $table->foreignId('created_by_user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
