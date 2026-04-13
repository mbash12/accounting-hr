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
        Schema::create('document_numberings', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 50);
            $table->string('prefix', 20);
            $table->string('format', 50);
            $table->integer('next_number')->default(1);
            $table->enum('reset_period', ["never","daily","weekly","monthly","quarterly","yearly"]);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('document_numberings');
    }
};
