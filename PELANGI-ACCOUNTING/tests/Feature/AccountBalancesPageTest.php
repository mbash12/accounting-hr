<?php

use App\Filament\Pages\Reports\AccountBalances;
use App\Filament\Pages\Reports\BalanceSheet;
use App\Filament\Pages\Reports\CashFlow;
use App\Filament\Pages\Reports\GeneralLedger;
use App\Filament\Pages\Reports\IncomeStatement;
use App\Filament\Pages\Reports\TrialBalance;
use App\Models\Account;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('accounts', function (Blueprint $table) {
        $table->id();
        $table->string('code');
        $table->string('name');
        $table->string('account_type')->nullable();
        $table->string('classification_type')->nullable();
        $table->text('description')->nullable();
        $table->boolean('is_header')->default(false);
        $table->boolean('is_cash_bank')->default(false);
        $table->boolean('is_active')->default(true);
        $table->integer('level')->default(1);
        $table->decimal('opening_balance', 15, 2)->default(0);
        $table->decimal('current_balance', 15, 2)->default(0);
        $table->foreignId('parent_id')->nullable();
        $table->foreignId('classification_id')->nullable();
        $table->foreignId('company_id');
        $table->foreignId('created_by_user_id')->nullable();
        $table->string('cash_flow')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('journal_entries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->nullable();
        $table->string('entry_number')->nullable();
        $table->string('reference_no')->nullable();
        $table->text('description')->nullable();
        $table->date('date')->nullable();
        $table->boolean('is_posted')->default(false);
        $table->string('sub_module')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('journal_entry_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('journal_entry_id')->nullable();
        $table->foreignId('account_id')->nullable();
        $table->text('notes')->nullable();
        $table->decimal('debit', 15, 2)->default(0);
        $table->decimal('credit', 15, 2)->default(0);
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('period_closings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->nullable();
        $table->string('period_type')->nullable();
        $table->string('status')->nullable();
        $table->foreignId('closing_journal_entry_id')->nullable();
        $table->date('end_date')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
});

afterEach(function () {
    Schema::dropIfExists('period_closings');
    Schema::dropIfExists('journal_entry_items');
    Schema::dropIfExists('journal_entries');
    Schema::dropIfExists('accounts');
    Schema::dropIfExists('companies');
});

function makeReportCompanyId(): int
{
    // CompanyFactory references a dropped `settings` column, so insert directly.
    return DB::table('companies')->insertGetId([
        'name' => 'Test Co',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

/**
 * Seed a company with one active and one soft-deleted account that both
 * carry a posted movement, so the deleted account is report-visible.
 *
 * @return array{int, int, int} [companyId, activeId, deletedId]
 */
function seedReportAccounts(string $accountType = 'current_asset'): array
{
    $companyId = makeReportCompanyId();
    session(['selected_company_id' => $companyId]);

    $base = [
        'company_id' => $companyId,
        'account_type' => $accountType,
        'is_header' => false,
        'is_cash_bank' => false,
        'is_active' => true,
        'parent_id' => null,
        'opening_balance' => 0,
        'created_by_user_id' => null,
    ];
    $active = Account::factory()->create($base + ['code' => '1001']);
    $deleted = Account::factory()->create($base + ['code' => '1002', 'deleted_at' => now()]);

    $entryId = DB::table('journal_entries')->insertGetId([
        'company_id' => $companyId,
        'entry_number' => 'JV-1',
        'date' => '2026-06-15',
        'is_posted' => true,
        'sub_module' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    foreach ([$active, $deleted] as $account) {
        DB::table('journal_entry_items')->insert([
            'journal_entry_id' => $entryId,
            'account_id' => $account->id,
            'debit' => 1000,
            'credit' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return [$companyId, $active->id, $deleted->id];
}

/** Flatten a report account tree into a plain id list. */
function treeAccountIds($nodes): array
{
    $ids = [];
    foreach ($nodes as $node) {
        $ids[] = $node->id;
        if (isset($node->children)) {
            $ids = array_merge($ids, treeAccountIds($node->children));
        }
    }

    return $ids;
}

function reportPageData(object $page, array $state, string $method = 'getReportData'): array
{
    $page->data = $state;
    $reflection = new ReflectionMethod($page, $method);

    return $reflection->invoke($page);
}

test('account balances excludes soft deleted accounts', function () {
    [$companyId, $activeId, $deletedId] = seedReportAccounts();

    $page = new AccountBalances;
    $page->data = ['date' => '2026-12-31'];
    $accounts = $page->getAccounts();

    expect($accounts->pluck('id')->all())->toContain($activeId)
        ->not->toContain($deletedId);
});

test('account balances sorts child accounts by code', function () {
    $companyId = makeReportCompanyId();
    session(['selected_company_id' => $companyId]);

    $parent = Account::factory()->create([
        'company_id' => $companyId,
        'code' => '1000',
        'is_header' => true,
        'is_cash_bank' => false,
        'is_active' => true,
        'parent_id' => null,
        'opening_balance' => 0,
        'created_by_user_id' => null,
    ]);
    $child = [
        'company_id' => $companyId,
        'is_header' => false,
        'is_cash_bank' => false,
        'is_active' => true,
        'parent_id' => $parent->id,
        'opening_balance' => 0,
        'created_by_user_id' => null,
    ];
    Account::factory()->create($child + ['code' => '1002']);
    Account::factory()->create($child + ['code' => '1001']);

    $page = new AccountBalances;
    $page->data = ['date' => '2026-12-31'];
    $accounts = $page->getAccounts();
    $root = $accounts->firstWhere('id', $parent->id);

    expect($root->children->pluck('code')->values()->all())->toBe(['1001', '1002']);
});

test('trial balance excludes soft deleted accounts', function () {
    [$companyId, $activeId, $deletedId] = seedReportAccounts();

    $data = reportPageData(new TrialBalance, [
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
    ]);

    $ids = collect($data['rows'])->pluck('account_id')->filter()->all();

    expect($ids)->toContain($activeId)->not->toContain($deletedId);
});

test('general ledger excludes soft deleted accounts when selecting all', function () {
    [$companyId, $activeId, $deletedId] = seedReportAccounts();

    $data = reportPageData(new GeneralLedger, [
        'select_all' => true,
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
    ]);

    $ids = collect($data['accounts_data'])->pluck('account.id')->all();

    expect($ids)->toContain($activeId)->not->toContain($deletedId);
});

test('balance sheet excludes soft deleted accounts', function () {
    [$companyId, $activeId, $deletedId] = seedReportAccounts();

    $data = reportPageData(new BalanceSheet, ['date' => '2026-12-31'], 'getRawData');

    $ids = collect(['assets', 'liabilities', 'equity'])
        ->flatMap(fn ($key) => treeAccountIds($data[$key] ?? collect()))
        ->all();

    expect($ids)->toContain($activeId)->not->toContain($deletedId);
});

test('income statement excludes soft deleted accounts', function () {
    [$companyId, $activeId, $deletedId] = seedReportAccounts('revenue');

    $data = reportPageData(new IncomeStatement, [
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
    ], 'getRawData');

    $ids = collect(['operatingRevenues', 'costOfGoodsSold', 'operatingExpenses', 'otherRevenues', 'otherExpenses'])
        ->flatMap(fn ($key) => treeAccountIds($data[$key] ?? collect()))
        ->all();

    expect($ids)->toContain($activeId)->not->toContain($deletedId);
});

test('cash flow excludes soft deleted accounts', function () {
    [$companyId, $activeId, $deletedId] = seedReportAccounts();

    $data = reportPageData(new CashFlow, [
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
    ]);

    $ids = collect(['plTree', 'nonCashTree', 'opAssetsTree', 'opLiabTree', 'invTree', 'finTree'])
        ->flatMap(fn ($key) => treeAccountIds($data[$key] ?? collect()))
        ->all();

    expect($ids)->not->toContain($deletedId);
});
