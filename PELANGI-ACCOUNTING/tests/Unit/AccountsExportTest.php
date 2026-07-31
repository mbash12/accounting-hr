<?php

use App\Exports\AccountsExport;
use App\Imports\AccountsImport;
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

test('COA export normalizes internal classifications to values accepted by the importer', function (
    ?string $classificationType,
    string $accountType,
    string $expectedClassificationType,
) {
    $account = Account::make([
        'code' => '5000',
        'name' => 'Importable Account',
        'classification_type' => $classificationType,
        'account_type' => $accountType,
        'is_header' => false,
        'is_cash_bank' => false,
        'is_active' => true,
        'level' => 1,
    ]);

    $export = new AccountsExport();
    $row = array_combine($export->headings(), $export->map($account));
    $validator = validator($row, (new AccountsImport())->rules());

    expect($row['classification_type'])->toBe($expectedClassificationType)
        ->and($validator->passes())->toBeTrue();
})->with([
    'legacy COGS classification' => ['cogs', 'cost_of_goods_sold', 'cost_of_goods_sold'],
    'missing classification' => [null, 'current_liability', 'current_liability'],
]);
