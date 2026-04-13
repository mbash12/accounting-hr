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
        Schema::table('invoice_sync_jobs', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('completed_at');
            $table->string('job_number')->nullable()->after('invoice_number');
            $table->string('customer_name')->nullable()->after('job_number');
            $table->decimal('total_amount', 15, 2)->nullable()->after('customer_name');
            $table->date('invoice_date')->nullable()->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_sync_jobs', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'job_number', 'customer_name', 'total_amount', 'invoice_date']);
        });
    }
};
