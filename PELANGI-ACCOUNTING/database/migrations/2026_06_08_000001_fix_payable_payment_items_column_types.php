<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN amount DROP DEFAULT');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN amount TYPE decimal(15,2) USING amount::numeric(15,2)');

        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN paid_amount DROP DEFAULT');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN paid_amount TYPE decimal(15,2) USING paid_amount::numeric(15,2)');

        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN discount_amount DROP DEFAULT');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN discount_amount TYPE decimal(15,2) USING discount_amount::numeric(15,2)');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN discount_amount SET DEFAULT 0');

        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN write_off_amount DROP DEFAULT');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN write_off_amount TYPE decimal(15,2) USING write_off_amount::numeric(15,2)');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN write_off_amount SET DEFAULT 0');

        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN set_payment DROP DEFAULT');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN set_payment TYPE decimal(15,2) USING set_payment::numeric(15,2)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN amount TYPE varchar(255)');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN paid_amount TYPE varchar(255)');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN discount_amount TYPE varchar(255)');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN write_off_amount TYPE varchar(255)');
        DB::statement('ALTER TABLE payable_payment_items ALTER COLUMN set_payment TYPE varchar(255)');
    }
};
