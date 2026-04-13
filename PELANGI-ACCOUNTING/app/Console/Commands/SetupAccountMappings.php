<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\Company;
use Illuminate\Console\Command;

class SetupAccountMappings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:account-mappings
                            {--company= : Company ID to setup mappings for (required)}
                            {--force : Force override existing mappings}
                            {--dry-run : Show what will be created without creating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup account mappings for a company based on accounts.csv';

    /**
     * Account code mappings (based on accounts.csv)
     */
    protected array $accountCodes = [
        // Sales mappings
        'accounts_receivable' => '11000300', // Account Receivable IDR
        'sales' => '40000100', // Penjualan
        'discount' => '41010400', // Sales Term Discount IDR
        'tax' => '160000', // PPn Masukan
        'cogs' => '500000', // COGS
        'inventory' => '120000', // Persediaan Barang Dagang
        'sales_return' => '40000300', // Retur Penjualan
        'advance_receivable' => '210200', // Advance Sales IDR

        // Purchase mappings
        'accounts_payable' => '210100', // Hutang Dagang
        'grni' => '210300', // Goods Received Not Invoiced (accrued liability)
        'purchase_return' => '51000200', // Retur Pembelian Barang
        'advance_payable' => '11000400', // Advance Purchase IDR
    ];

    /**
     * Document types and their required mappings
     * Based on proper accounting principles:
     * - Sales/Purchase Order: No journal (commitment only), optional advance
     * - Sales Delivery: COGS recognition (Dr COGS, Cr Inventory)
     * - Sales Invoice: Revenue recognition (Dr A/R, Cr Sales, Cr Tax)
     * - Goods Receipt: Inventory receipt (Dr Inventory, Cr GRNI)
     * - Purchase Invoice: Record liability (Dr GRNI, Dr Tax, Cr A/P)
     */
    protected array $documentMappingTypes = [
        'sales_order' => ['advance_receivable'],
        'delivery_document' => ['cogs', 'inventory'],
        'sales_invoice' => ['accounts_receivable', 'sales', 'discount', 'tax'],
        'sales_return' => ['accounts_receivable', 'sales_return', 'tax', 'cogs', 'inventory'],
        'purchase_order' => ['advance_payable'],
        'goods_receipt' => ['inventory', 'grni'],
        'purchase_invoice' => ['accounts_payable', 'grni', 'tax', 'discount'],
        'purchase_return' => ['accounts_payable', 'purchase_return', 'tax', 'inventory'],
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $companyId = $this->option('company');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        if (!$companyId) {
            $this->error('Please provide a company ID using --company option');

            // Show available companies
            $companies = Company::all(['id', 'name']);
            if ($companies->count() > 0) {
                $this->newLine();
                $this->info('Available companies:');
                $companies->each(function ($company) {
                    $this->line("  ID: {$company->id} - {$company->name}");
                });
            }

            return 1;
        }

        // Validate company exists
        $company = Company::find($companyId);
        if (!$company) {
            $this->error("Company with ID {$companyId} not found");
            return 1;
        }

        $this->info("Setting up account mappings for company: {$company->name} (ID: {$companyId})");
        $this->newLine();

        // Load accounts
        $accounts = $this->loadAccounts();

        if ($accounts->isEmpty()) {
            $this->error('No accounts found in database');
            $this->warn('Make sure to run account seeder first: php artisan db:seed --class=AccountSeeder');
            return 1;
        }

        $this->info("Found {$accounts->count()} accounts");
        $this->newLine();

        // Check existing mappings
        $existingMappings = AccountMapping::where('company_id', $companyId)->get();
        if ($existingMappings->count() > 0 && !$force) {
            $this->warn("Found {$existingMappings->count()} existing mappings for this company");
            $this->warn("Use --force option to override existing mappings");
            return 1;
        }

        if ($existingMappings->count() > 0 && $force) {
            $this->warn("Found {$existingMappings->count()} existing mappings, will override due to --force option");
            $this->newLine();
        }

        // Create mappings
        $mappingsToCreate = $this->prepareMappings($companyId, $accounts);

        if ($mappingsToCreate->isEmpty()) {
            $this->error('No mappings to create. Check if required accounts exist.');
            return 1;
        }

        // Display what will be created
        $this->displayMappings($mappingsToCreate);

        if ($dryRun) {
            $this->newLine();
            $this->warn('DRY RUN MODE - No changes will be made');
            $this->warn('Run without --dry-run to apply changes');
            return 0;
        }

        // Confirm
        if (!$this->confirm("Create {$mappingsToCreate->count()} account mappings for {$company->name}?", true)) {
            $this->warn('Operation cancelled');
            return 0;
        }

        // Delete existing mappings if force
        if ($force && $existingMappings->count() > 0) {
            AccountMapping::where('company_id', $companyId)->delete();
            $this->warn("Deleted {$existingMappings->count()} existing mappings");
            $this->newLine();
        }

        // Create mappings
        $progressBar = $this->output->createProgressBar($mappingsToCreate->count());
        $progressBar->start();

        foreach ($mappingsToCreate as $mapping) {
            try {
                AccountMapping::create($mapping);
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to create mapping for {$mapping['document_type']} - {$mapping['mapping_type']}: {$e->getMessage()}");
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✓ Successfully created {$mappingsToCreate->count()} account mappings for {$company->name}");

        return 0;
    }

    /**
     * Load accounts from database
     */
    protected function loadAccounts()
    {
        $companyId = $this->option('company');
        $codes = array_filter($this->accountCodes); // Remove null values

        return Account::where('company_id', $companyId)
            ->whereIn('code', $codes)
            ->where('is_active', true)
            ->get()
            ->keyBy('code');
    }

    /**
     * Prepare mappings to be created
     */
    protected function prepareMappings($companyId, $accounts)
    {
        $mappings = collect();

        foreach ($this->documentMappingTypes as $documentType => $requiredMappings) {
            foreach ($requiredMappings as $mappingType) {
                $accountCode = $this->accountCodes[$mappingType] ?? null;

                // Skip if no account code defined
                if (!$accountCode) {
                    $this->warn("No account code defined for {$mappingType}");
                    continue;
                }

                // Skip if account not found
                if (!$accounts->has($accountCode)) {
                    $this->warn("Account with code {$accountCode} not found for {$mappingType}");
                    continue;
                }

                $account = $accounts->get($accountCode);

                $mappings->push([
                    'company_id' => $companyId,
                    'document_type' => $documentType,
                    'mapping_type' => $mappingType,
                    'account_id' => $account->id,
                    'description' => null,
                ]);
            }
        }

        return $mappings;
    }

    /**
     * Display mappings in a table format
     */
    protected function displayMappings($mappings)
    {
        $this->info('Account mappings to create:');
        $this->newLine();

        $grouped = $mappings->groupBy('document_type')->sortKeys();

        foreach ($grouped as $documentType => $documentMappings) {
            $this->line("  <fg=cyan>{$documentType}</>");
            foreach ($documentMappings as $mapping) {
                $account = Account::find($mapping['account_id']);
                $mappingLabel = $this->getMappingLabel($mapping['mapping_type']);
                $this->line("    {$mappingLabel} → <fg=green>{$account->code}</> {$account->name}");
            }
            $this->newLine();
        }
    }

    /**
     * Get human-readable label for mapping type
     */
    protected function getMappingLabel($mappingType): string
    {
        $labels = [
            'accounts_receivable' => 'Accounts Receivable',
            'sales' => 'Sales Revenue',
            'discount' => 'Discount',
            'tax' => 'Tax',
            'cogs' => 'Cost of Goods Sold',
            'inventory' => 'Inventory',
            'sales_return' => 'Sales Return',
            'advance_receivable' => 'Advance Receivable',
            'accounts_payable' => 'Accounts Payable',
            'grni' => 'Goods Received Not Invoiced',
            'purchase_return' => 'Purchase Return',
            'advance_payable' => 'Advance Payable',
        ];

        return $labels[$mappingType] ?? $mappingType;
    }
}
