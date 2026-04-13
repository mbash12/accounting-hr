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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_no');
            $table->text('description');
            $table->enum('status', ["requested","approved","shipped","received","processed","rejected","cancelled"]);
            $table->foreignId('supplier_id');
            $table->foreignId('purchase_invoice_id');
            $table->foreignId('job_id');
            $table->foreignId('company_id');
            $table->foreignId('created_by_user_id');
            $table->foreignId('updated_by_user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
