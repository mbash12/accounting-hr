<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropStatusConstraints extends Command
{
    protected $signature = 'constraints:drop-status';
    protected $description = 'Drop status check constraints from tables';

    public function handle()
    {
        $tables = [
            'sales_orders',
            'delivery_documents',
            'sales_invoices',
            'sales_returns',
            'purchase_orders',
            'goods_receipts',
            'purchase_invoices',
            'purchase_returns',
        ];

        foreach ($tables as $table) {
            DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$table}_status_check");
            $this->info("Dropped constraint for {$table}");
        }

        $this->info('All status constraints dropped successfully');
    }
}
