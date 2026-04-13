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
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->boolean('is_closed')->default(false);
            $table->string('reference_no');
            $table->text('description');
            $table->enum('status', ["pending","received","inspected","approved","rejected","cancelled"]);
            $table->foreignId('supplier_id');
            $table->foreignId('purchase_order_id');
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
        Schema::dropIfExists('goods_receipts');
    }
};
