<?php

use App\Exports\AccountsExport;
use App\Models\Account;

uses(Tests\TestCase::class);

test('COA export uses the exact import template headings', function () {
    expect((new AccountsExport())->headings())->toBe([
        'code',
        'name',
        'description',
        'classification_type',
        'is_header',
        'is_cash_bank',
        'is_active',
        'level',
        'parent_code',
    ]);
});

test('COA export maps rows in the exact import template shape', function () {
    $parent = Account::make(['code' => '1000']);
    $account = Account::make([
        'code' => '1001',
        'name' => 'Cash',
        'description' => 'Petty cash',
        'classification_type' => 'asset',
        'is_header' => false,
        'is_cash_bank' => true,
        'is_active' => true,
        'level' => 2,
        'parent_id' => 1,
    ]);
    $account->setRelation('parent', $parent);

    expect((new AccountsExport())->map($account))->toBe([
        '1001',
        'Cash',
        'Petty cash',
        'asset',
        'no',
        'yes',
        'yes',
        2,
        '1000',
    ]);
});
