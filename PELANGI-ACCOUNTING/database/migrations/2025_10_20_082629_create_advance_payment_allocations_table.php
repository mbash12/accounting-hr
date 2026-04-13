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
        Schema::create('advance_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('allocated_amount');
            $table->date('allocated_date');
            $table->text('notes');
            $table->foreignId('advance_payment_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_payment_allocations');
    }
};
