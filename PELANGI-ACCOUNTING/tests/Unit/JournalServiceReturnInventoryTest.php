<?php

use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\JournalEntry;
use App\Services\JournalService;
use Illuminate\Support\Collection;

uses(Tests\TestCase::class);

class SpyJournalService extends JournalService
{
    /** @var list<array{account_id: int, type: string, amount: float}> */
    public array $lines = [];

    protected function createJournalItem(
        JournalEntry $journalEntry,
        $account,
        string $type,
        float $amount,
        ?string $notes = null
    ): void {
        if (!$account || $amount <= 0) {
            return;
        }

        $this->lines[] = [
            'account_id' => (int) $account->id,
            'type' => $type,
            'amount' => round($amount, 2),
        ];
    }

    public function runSalesReturn($document, Collection $mappings): void
    {
        $this->createSalesReturnJournalItems($document, new JournalEntry, $mappings);
    }

    public function runPurchaseReturn($document, Collection $mappings): void
    {
        $this->createPurchaseReturnJournalItems($document, new JournalEntry, $mappings);
    }
}

function fakeAccount(int $id): Account
{
    $account = new Account;
    $account->id = $id;
    $account->exists = true;

    return $account;
}

function fakeReturnDocument(array $items, array $extra = []): object
{
    $doc = new class
    {
        public $items;

        public $salesInvoice = null;

        public $purchaseInvoice = null;

        public function relationLoaded($key): bool
        {
            return true;
        }

        public function load($relations): static
        {
            return $this;
        }

        public function loadMissing($relations): static
        {
            return $this;
        }
    };

    $doc->items = collect($items)->map(fn ($row) => (object) $row);

    foreach ($extra as $k => $v) {
        $doc->{$k} = $v;
    }

    return $doc;
}

test('sales_return mapping types include inventory and cogs', function () {
    expect(AccountMapping::DOCUMENT_MAPPING_TYPES['sales_return'])
        ->toContain('inventory')
        ->toContain('cogs');
});

test('purchase_return mapping types include inventory and grni', function () {
    expect(AccountMapping::DOCUMENT_MAPPING_TYPES['purchase_return'])
        ->toContain('inventory')
        ->toContain('grni');
});

test('sales return posts inventory and cogs when mapped', function () {
    $inventory = fakeAccount(101);
    $cogs = fakeAccount(102);
    $ar = fakeAccount(103);
    $salesReturn = fakeAccount(104);

    $doc = fakeReturnDocument([
        ['quantity' => 2, 'unit_cost' => 50, 'unit_price' => 80, 'product_id' => null],
    ]);

    $mappings = collect([
        'inventory' => $inventory,
        'cogs' => $cogs,
        'accounts_receivable' => $ar,
        'sales_return' => $salesReturn,
    ]);

    $spy = new SpyJournalService;
    $spy->runSalesReturn($doc, $mappings);

    $inv = collect($spy->lines)->firstWhere(fn ($l) => $l['account_id'] === 101 && $l['type'] === 'debit');
    $cogsLine = collect($spy->lines)->firstWhere(fn ($l) => $l['account_id'] === 102 && $l['type'] === 'credit');

    expect($inv['amount'])->toBe(100.0)
        ->and($cogsLine['amount'])->toBe(100.0);
});

test('sales return skips stock lines when inventory mapping missing', function () {
    $cogs = fakeAccount(102);
    $ar = fakeAccount(103);
    $salesReturn = fakeAccount(104);

    $doc = fakeReturnDocument([
        ['quantity' => 2, 'unit_cost' => 50, 'unit_price' => 80],
    ]);

    $mappings = collect([
        'cogs' => $cogs,
        'accounts_receivable' => $ar,
        'sales_return' => $salesReturn,
    ]);

    $spy = new SpyJournalService;
    $spy->runSalesReturn($doc, $mappings);

    expect(collect($spy->lines)->whereIn('account_id', [101, 102])->filter(fn ($l) => in_array($l['type'], ['debit', 'credit'], true) && $l['account_id'] === 102 && $l['type'] === 'credit')->count())->toBe(0)
        ->and(collect($spy->lines)->contains(fn ($l) => $l['account_id'] === 104))->toBeTrue();
});

test('purchase return credits inventory instead of purchase_return when inventory mapped', function () {
    $inventory = fakeAccount(201);
    $ap = fakeAccount(202);
    $purchaseReturn = fakeAccount(203);

    $doc = fakeReturnDocument([
        ['quantity' => 3, 'unit_price' => 40, 'unit_cost' => 40],
    ]);

    $mappings = collect([
        'inventory' => $inventory,
        'accounts_payable' => $ap,
        'purchase_return' => $purchaseReturn,
    ]);

    $spy = new SpyJournalService;
    $spy->runPurchaseReturn($doc, $mappings);

    $invCredit = collect($spy->lines)->firstWhere(fn ($l) => $l['account_id'] === 201 && $l['type'] === 'credit');
    $prCredit = collect($spy->lines)->firstWhere(fn ($l) => $l['account_id'] === 203 && $l['type'] === 'credit');

    expect($invCredit['amount'])->toBe(120.0)
        ->and($prCredit)->toBeNull();
});

test('purchase return falls back to purchase_return credit without inventory mapping', function () {
    $ap = fakeAccount(202);
    $purchaseReturn = fakeAccount(203);

    $doc = fakeReturnDocument([
        ['quantity' => 3, 'unit_price' => 40],
    ]);

    $mappings = collect([
        'accounts_payable' => $ap,
        'purchase_return' => $purchaseReturn,
    ]);

    $spy = new SpyJournalService;
    $spy->runPurchaseReturn($doc, $mappings);

    $prCredit = collect($spy->lines)->firstWhere(fn ($l) => $l['account_id'] === 203 && $l['type'] === 'credit');

    expect($prCredit['amount'])->toBe(120.0);
});
