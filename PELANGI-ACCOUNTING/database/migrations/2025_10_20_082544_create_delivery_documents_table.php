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
        Schema::create('delivery_documents', function (Blueprint $table) {
            $table->id();
            $table->enum('delivery_type', ["full","partial","return","replacement","sample"]);
            $table->date('date');
            $table->boolean('is_closed')->default(false);
            $table->string('reference_no');
            $table->text('description');
            $table->enum('delivery_status', ["pending","picked","in_transit","delivered","failed","cancelled"]);
            $table->string('tracking_number', 100)->nullable();
            $table->string('bast_document')->nullable();
            $table->string('tpb_document')->nullable();
            $table->timestamp('dispatch_time')->nullable();
            $table->timestamp('completion_time')->nullable();
            $table->foreignId('customer_id');
            $table->foreignId('sales_order_id');
            $table->foreignId('job_id');
            $table->foreignId('expedition_id');
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
        Schema::dropIfExists('delivery_documents');
    }
};
