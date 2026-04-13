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
        Schema::create('account_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('account_type');
            $table->string('classification_type');
            $table->boolean('is_header')->default(false);
            $table->boolean('is_cash_bank')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('cash_flow')->default('undefined');
            $table->string('parent_code')->nullable();
            $table->integer('level')->default(1);
            $table->string('template_name');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['template_name', 'code']);
            $table->index(['template_name', 'parent_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_templates');
    }
};
