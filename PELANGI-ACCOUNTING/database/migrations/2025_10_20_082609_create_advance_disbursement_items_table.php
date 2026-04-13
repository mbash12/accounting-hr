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
        Schema::create('advance_disbursement_items', function (Blueprint $table) {
            $table->id();
            $table->string('amount');
            $table->text('description');
            $table->foreignId('advance_disbursement_id');
            $table->foreignId('transaction_classification_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_disbursement_items');
    }
};
