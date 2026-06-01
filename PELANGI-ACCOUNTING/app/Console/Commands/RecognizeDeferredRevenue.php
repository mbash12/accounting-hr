<?php

namespace App\Console\Commands;

use App\Services\DeferredRevenueService;
use Illuminate\Console\Command;

class RecognizeDeferredRevenue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deferred-revenue:recognize {--company-id= : Optional company ID to scope recognition}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recognize due deferred revenue amortization entries and create journal entries';

    /**
     * Execute the console command.
     */
    public function handle(DeferredRevenueService $service): int
    {
        $companyId = $this->option('company-id');

        $this->info('Processing deferred revenue recognitions...');

        $count = $service->recognizeDue($companyId ? (int) $companyId : null);

        if ($count > 0) {
            $this->info("Successfully recognized {$count} deferred revenue schedule(s).");
        } else {
            $this->info('No deferred revenue schedules are due for recognition.');
        }

        return self::SUCCESS;
    }
}
