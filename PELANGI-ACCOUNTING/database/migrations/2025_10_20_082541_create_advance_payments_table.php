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
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->string('advance_number', 50)->unique();
            $table->date('date');
            $table->string('amount');
            $table->text('description');
            $table->enum('status', ["pending","active","partially_used","fully_used","expired","cancelled"]);
            $table->string('used_amount')->default('0');
            $table->string('remaining_amount');
            $table->date('expiry_date')->nullable();
            $table->foreignId('customer_id');
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
        Schema::dropIfExists('advance_payments');
    }
};
