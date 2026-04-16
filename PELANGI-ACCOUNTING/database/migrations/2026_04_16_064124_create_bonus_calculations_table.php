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
        Schema::create('bonus_calculations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->integer('year');
            $table->date('payout_date');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'processed', 'posted'])->default('draft');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('total_pph21', 15, 2)->default(0);
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries');
            $table->foreignId('company_id')->nullable();
            $table->foreignId('created_by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_calculations');
    }
};
