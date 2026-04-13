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
        Schema::create('journal_entry_items', function (Blueprint $table) {
            $table->id();
            $table->decimal('debit', 15, 2);
            $table->decimal('credit', 15, 2);
            $table->text('notes')->nullable();
            $table->foreignId('journal_entry_id');
            $table->foreignId('account_id');
            $table->foreignId('cost_center_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entry_items');
    }
};
