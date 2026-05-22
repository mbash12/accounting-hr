<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Carbon\Carbon;

class ReportTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::find(12);

        if (!$company) {
            $this->command->error("Company with ID 12 not found. Please create a company first.");
            return;
        }

        // We assume chart of accounts is generated or already seeded.
        // Let's get generic accounts based on prefixes / specific accounts for creating full report data.

        // Cash equivalents
        $cashAccounts = Account::where('company_id', $company->id)->where('is_cash_bank', true)->get();
        if ($cashAccounts->isEmpty()) {
            $this->command->error("No Cash/Bank accounts found.");
            return;
        }
        $cashAccount = $cashAccounts->first(); // Example 'Petty Cash'

        // Accounts receivable
        $accountsReceivable = Account::where('company_id', $company->id)->where('code', 'like', '113%')->where('is_header', false)->first();

        // Inventory / Stock
        $inventoryAccount = Account::where('company_id', $company->id)->where('code', 'like', '114%')->where('is_header', false)->first();
        if (!$inventoryAccount) {
            // Find a generic current asset to simulate inventory
            $inventoryAccount = Account::where('company_id', $company->id)->where('account_type', 'current_asset')->where('is_cash_bank', false)->skip(1)->first();
        }

        // Fixed Asset
        $fixedAsset = Account::where('company_id', $company->id)->where('account_type', 'fixed_asset')->where('is_header', false)->first();

        // Accounts Payable
        $accountsPayable = Account::where('company_id', $company->id)->where('account_type', 'current_liability')->where('is_header', false)->first();

        // Long Term Liability (Bank Loan)
        $longTermLiability = Account::where('company_id', $company->id)->where('account_type', 'long_term_liability')->where('is_header', false)->first();

        // Equity
        $equityAccount = Account::where('company_id', $company->id)->where('account_type', 'equity')->where('is_header', false)->first();

        // Revenue (4xxx)
        $revenueAccount = Account::where('company_id', $company->id)->where('code', 'like', '4%')->where('is_header', false)->first();

        // Cost of Goods Sold / Purchase expense (5xxx)
        $cogsAccount = Account::where('company_id', $company->id)->where('code', 'like', '5%')->where('is_header', false)->first();

        // Operating Expense (6xxx)
        $operatingExpenseAccount = Account::where('company_id', $company->id)->where('code', 'like', '6%')->where('is_header', false)->first();


        // Define realistic transaction dates spanning across a realistic timeframe (e.g., within the current month/year)
        // We'll put dates in this month to make reports easy to filter without configuring wide dates
        $startDate = now()->startOfMonth();

        $this->command->info("Creating sample reports data...");

        // 1. Owner's investment (Equity / Financing / Beginning Balance)
        if ($equityAccount) {
            $this->createJournalEntry($company, $startDate->copy()->addDays(1), 'Initial Capital Investment', [
                ['account_id' => $cashAccount->id, 'debit' => 500000000, 'credit' => 0], // D: Cash 500m
                ['account_id' => $equityAccount->id, 'debit' => 0, 'credit' => 500000000] // C: Equity 500m
            ]);
        }

        // 2. Obtain a Long Term Bank Loan (Financing)
        if ($longTermLiability) {
            $this->createJournalEntry($company, $startDate->copy()->addDays(2), 'Long Term Bank Loan', [
                ['account_id' => $cashAccount->id, 'debit' => 200000000, 'credit' => 0], // D: Cash 200m
                ['account_id' => $longTermLiability->id, 'debit' => 0, 'credit' => 200000000] // C: Bank Loan 200m
            ]);
        }

        // 3. Purchase Fixed Assets (Investing)
        if ($fixedAsset) {
            $this->createJournalEntry($company, $startDate->copy()->addDays(3), 'Fixed Asset Purchase', [
                ['account_id' => $fixedAsset->id, 'debit' => 150000000, 'credit' => 0], // D: Fixed Asset 150m
                ['account_id' => $cashAccount->id, 'debit' => 0, 'credit' => 150000000] // C: Cash 150m
            ]);
        }

        // 4. Purchase Inventory on Credit / Cash (Operating Asset / Liability)
        if ($inventoryAccount && $accountsPayable) {
            // Purchase inventory partially cash, partially on credit
            $this->createJournalEntry($company, $startDate->copy()->addDays(5), 'Merchandise Purchase', [
                ['account_id' => $inventoryAccount->id, 'debit' => 100000000, 'credit' => 0], // D: Inventory 100m
                ['account_id' => $cashAccount->id, 'debit' => 0, 'credit' => 50000000], // C: Cash 50m
                ['account_id' => $accountsPayable->id, 'debit' => 0, 'credit' => 50000000] // C: Payable 50m
            ]);
        }

        // 5. Service/Product Revenue -> Cash & AR (Operating / PL)
        if ($revenueAccount && $accountsReceivable) {
            $this->createJournalEntry($company, $startDate->copy()->addDays(10), 'Product Sales', [
                ['account_id' => $cashAccount->id, 'debit' => 120000000, 'credit' => 0], // D: Cash 120m
                ['account_id' => $accountsReceivable->id, 'debit' => 80000000, 'credit' => 0], // D: Receivable 80m
                ['account_id' => $revenueAccount->id, 'debit' => 0, 'credit' => 200000000] // C: Revenue 200m
            ]);
        }

        // 6. Cost of Goods Sold recognized (Operating / PL)
        if ($cogsAccount && $inventoryAccount) {
            $this->createJournalEntry($company, $startDate->copy()->addDays(10), 'Cost of Goods Sold Recognition', [
                ['account_id' => $cogsAccount->id, 'debit' => 80000000, 'credit' => 0], // D: COGS 80m
                ['account_id' => $inventoryAccount->id, 'debit' => 0, 'credit' => 80000000] // C: Inventory 80m
            ]);
        }

        // 7. Pay operating expenses (e.g. Salary, Utilities) (Operating / PL)
        if ($operatingExpenseAccount) {
            $this->createJournalEntry($company, $startDate->copy()->addDays(28), 'Operating Expense Payment', [
                ['account_id' => $operatingExpenseAccount->id, 'debit' => 35000000, 'credit' => 0], // D: Operating Expense 35m
                ['account_id' => $cashAccount->id, 'debit' => 0, 'credit' => 35000000] // C: Cash 35m
            ]);
        }

        // 8. Receive payment from accounts receivable (Operating Assets changed to Cash)
        if ($accountsReceivable) {
            $this->createJournalEntry($company, $startDate->copy()->addDays(29), 'Accounts Receivable Payment Receipt', [
                ['account_id' => $cashAccount->id, 'debit' => 40000000, 'credit' => 0], // D: Cash 40m
                ['account_id' => $accountsReceivable->id, 'debit' => 0, 'credit' => 40000000] // C: Receivable 40m
            ]);
        }

        // 9. Pay accounts payable (Operating Liability reduction)
        if ($accountsPayable) {
            $this->createJournalEntry($company, $startDate->copy()->addDays(30), 'Trade Payable Installment Payment', [
                ['account_id' => $accountsPayable->id, 'debit' => 25000000, 'credit' => 0], // D: Payable 25m
                ['account_id' => $cashAccount->id, 'debit' => 0, 'credit' => 25000000] // C: Cash 25m
            ]);
        }

        $this->command->info("Data successfully injected. Check the Trial Balance, Balance Sheet, Income Statement, and Cash Flow (Indirect Method).");
    }

    private function createJournalEntry(Company $company, Carbon $date, string $description, array $items)
    {
        $amount = collect($items)->sum('debit');

        $journal = JournalEntry::create([
            'company_id' => $company->id,
            'entry_number' => 'TEST-' . time() . '-' . rand(100, 999),
            'date' => $date->format('Y-m-d'),
            'reference_no' => 'MANUAL-SEED',
            'description' => $description,
            'amount' => $amount,
            'total_amount' => $amount,
            'status' => 'posted', // We assume logic considers status posted or is_posted
            'is_posted' => true,
            'posted_at' => $date->format('Y-m-d H:i:s'),
            'sub_module' => 'manual',
            'created_by_user_id' => 1,
            'updated_by_user_id' => 1,
        ]);

        foreach ($items as $item) {
            JournalEntryItem::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $item['account_id'],
                'debit' => $item['debit'],
                'credit' => $item['credit'],
                'notes' => $description,
            ]);
        }
    }
}