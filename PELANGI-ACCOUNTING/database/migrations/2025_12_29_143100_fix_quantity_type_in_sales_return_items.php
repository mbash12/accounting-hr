<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE sales_return_items ALTER COLUMN quantity TYPE decimal(15,2) USING quantity::decimal(15,2)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE sales_return_items ALTER COLUMN quantity TYPE varchar(255)');
    }
};
