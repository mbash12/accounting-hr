<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        Schema::table('employees', function (Blueprint $table) {
            $table->text('foto')->nullable();
            $table->vector('foto_vector', 1408)->nullable();
        });

        DB::statement('CREATE INDEX employees_foto_vector_idx ON employees USING hnsw (foto_vector vector_cosine_ops)');
    }   

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS employees_foto_vector_idx');
        
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('foto');
            $table->dropColumn('foto_vector');  
        });
    }
};
