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
        Schema::create('transaction_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 50);
            $table->text('description');
            $table->enum('classification_type', ["operating","investing","financing","non_operating"]);
            $table->enum('tax_impact', ["taxable","exempt","zero_rated","out_of_scope"])->nullable();
            $table->string('reporting_category', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('default_account_id');
            $table->foreignId('company_id');
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
        Schema::dropIfExists('transaction_classifications');
    }
};
