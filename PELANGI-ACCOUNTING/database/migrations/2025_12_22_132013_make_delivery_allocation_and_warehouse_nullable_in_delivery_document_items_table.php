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
        // Make delivery_allocation nullable as it's not included in the form
        \DB::statement('ALTER TABLE delivery_document_items ALTER COLUMN delivery_allocation DROP NOT NULL');

        // Make warehouse_id nullable as it's not included in the form
        \DB::statement('ALTER TABLE delivery_document_items ALTER COLUMN warehouse_id DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement('ALTER TABLE delivery_document_items ALTER COLUMN delivery_allocation SET NOT NULL');
        \DB::statement('ALTER TABLE delivery_document_items ALTER COLUMN warehouse_id SET NOT NULL');
    }
};
