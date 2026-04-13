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
        Schema::table('delivery_documents', function (Blueprint $table) {
            // Drop the existing column
            $table->dropColumn('delivery_type');
        });
        
        Schema::table('delivery_documents', function (Blueprint $table) {
            // Add new enum column
            $table->enum('delivery_type', ['document', 'goods'])->default('goods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_documents', function (Blueprint $table) {
            $table->dropColumn('delivery_type');
        });
        
        Schema::table('delivery_documents', function (Blueprint $table) {
            $table->string('delivery_type');
        });
    }
};