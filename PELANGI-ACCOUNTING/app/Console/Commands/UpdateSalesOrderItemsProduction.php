<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateSalesOrderItemsProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounting:set-items-production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set is_production to true for all existing sales order items';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting update for existing sales order items...');

        try {
            // Using raw DB query for fast bulk update
            $updatedCount = DB::table('sales_order_items')->update(['is_production' => true]);

            $this->info("Successfully set is_production = TRUE for {$updatedCount} sales order items.");
            Log::info("accounting:set-items-production updated {$updatedCount} sales order items.");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to update items: " . $e->getMessage());
            Log::error("accounting:set-items-production failed: " . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}
