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
        Schema::create('cash_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_no');
            $table->text('description');
            $table->string('amount');
            $table->enum('status', ["draft","approved","processed","cancelled"]);
            $table->foreignId('from_account_id');
            $table->foreignId('to_account_id');
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
        Schema::dropIfExists('cash_transfers');
    }
};
