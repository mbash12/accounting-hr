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
        // Make reference_no nullable
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN reference_no DROP NOT NULL');

        // Make delivery_status nullable to allow NULL values
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN delivery_status DROP NOT NULL');

        // Make job_id nullable to allow NULL values when inferred from sales order
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN job_id DROP NOT NULL');

        // Make expedition_id nullable as it's not included in the form
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN expedition_id DROP NOT NULL');

        // Make updated_by_user_id nullable as it's not being set in the form
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN updated_by_user_id DROP NOT NULL');

        // Make tracking_number nullable as it's not included in the form
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN tracking_number DROP NOT NULL');

        // Make dispatch_time nullable as it's not included in the form
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN dispatch_time DROP NOT NULL');

        // Make completion_time nullable as it's not included in the form
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN completion_time DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN reference_no SET NOT NULL');
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN delivery_status SET NOT NULL');
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN job_id SET NOT NULL');
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN expedition_id SET NOT NULL');
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN updated_by_user_id SET NOT NULL');
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN tracking_number SET NOT NULL');
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN dispatch_time SET NOT NULL');
        \DB::statement('ALTER TABLE delivery_documents ALTER COLUMN completion_time SET NOT NULL');
    }
};
