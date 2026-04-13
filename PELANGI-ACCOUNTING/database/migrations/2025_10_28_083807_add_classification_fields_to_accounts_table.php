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
        Schema::table('accounts', function (Blueprint $table) {
            // Add new classification fields
            $table->enum('classification_type', ['asset', 'liability', 'equity', 'revenue', 'expense'])->after('account_type')->nullable();
            $table->text('description')->after('classification_type')->nullable();
            $table->boolean('is_header')->after('description')->default(false);
            $table->foreignId('created_by_user_id')->after('company_id')->nullable();
            
            // Drop the old account_classification_id column (no foreign key constraint exists)
            $table->dropColumn('account_classification_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            // Restore account_classification_id
            $table->foreignId('account_classification_id')->after('parent_id');
            
            // Drop the new fields
            $table->dropColumn(['classification_type', 'description', 'is_header', 'created_by_user_id']);
        });
    }
};
