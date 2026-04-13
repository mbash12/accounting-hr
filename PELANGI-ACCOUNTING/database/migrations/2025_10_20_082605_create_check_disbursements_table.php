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
        Schema::create('check_disbursements', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('check_number');
            $table->string('reference_no');
            $table->date('due_date');
            $table->text('description');
            $table->string('amount');
            $table->enum('status', ["draft","issued","cleared","bounced","cancelled","void"]);
            $table->foreignId('bank_account_id');
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
        Schema::dropIfExists('check_disbursements');
    }
};
