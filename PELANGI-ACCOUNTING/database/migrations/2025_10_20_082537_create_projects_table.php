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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('job_number', 50);
            $table->enum('status', ["planning","in_progress","on_hold","completed","cancelled"])->default('planning');
            $table->string('customer_po_number', 100)->nullable();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->decimal('total_value', 15, 2)->default(0);
            $table->decimal('total_invoiced', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->decimal('total_delivered', 15, 2)->default(0);
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
        Schema::dropIfExists('projects');
    }
};
