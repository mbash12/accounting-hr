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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number', 50);
            $table->date('date');
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', ["draft","posted","reversed","cancelled"])->default('draft');
            $table->string('document_no')->nullable();
            $table->date('document_date')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->foreignId('department_id');
            $table->foreignId('posted_by_user_id');
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
        Schema::dropIfExists('journal_entries');
    }
};
