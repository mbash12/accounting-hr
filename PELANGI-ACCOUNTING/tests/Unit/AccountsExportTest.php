<?php

use App\Exports\AccountsExport;
use App\Exports\AccountsTemplateExport;
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
        'account_type' => 'current_asset',
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
        'current_asset',
        'no',
        'yes',
        'yes',
        2,
        '1000',
    ]);
});

test('COA export preserves the legacy broad classification for a root account', function () {
    $account = Account::make([
        'code' => '10',
        'name' => 'Assets',
        'classification_type' => 'asset',
        'account_type' => 'current_asset',
        'is_header' => true,
        'is_cash_bank' => false,
        'is_active' => true,
        'level' => 0,
        'parent_id' => null,
    ]);

    $export = new AccountsExport();
    $row = array_combine($export->headings(), $export->map($account));

    expect($row['classification_type'])->toBe('asset');
});

test('COA export reverses flattened standard COA classifications by code', function (
    string $code,
    string $classificationType,
    string $accountType,
    ?int $parentId,
    string $expectedClassificationType,
) {
    $account = Account::make([
        'code' => $code,
        'name' => 'Standard Account',
        'classification_type' => $classificationType,
        'account_type' => $accountType,
        'is_header' => true,
        'is_cash_bank' => false,
        'is_active' => true,
        'level' => $parentId === null ? 1 : 2,
        'parent_id' => $parentId,
    ]);

    if ($parentId !== null) {
        $account->setRelation('parent', Account::make(['code' => 'parent']));
    }

    $export = new AccountsExport();
    $row = array_combine($export->headings(), $export->map($account));

    expect($row['classification_type'])->toBe($expectedClassificationType);
})->with([
    'current asset subgroup root' => ['12', 'asset', 'current_asset', null, 'current_asset'],
    'fixed asset under code 12' => ['12.02', 'asset', 'current_asset', 1, 'fixed_asset'],
    'fixed asset root' => ['14', 'asset', 'current_asset', null, 'fixed_asset'],
    'other income root' => ['60', 'expense', 'expense', null, 'other_income'],
    'other income child' => ['60.01', 'expense', 'expense', 1, 'other_income'],
    'other expense child' => ['60.02', 'expense', 'expense', 1, 'other_expense'],
]);

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
        'parent_id' => 1,
    ]);
    $account->setRelation('parent', Account::make(['code' => '1000']));

    $export = new AccountsExport();
    $row = array_combine($export->headings(), $export->map($account));
    $validator = validator($row, (new AccountsImport())->rules());

    expect($row['classification_type'])->toBe($expectedClassificationType)
        ->and($validator->passes())->toBeTrue();
})->with([
    'current asset remains detailed' => ['asset', 'current_asset', 'current_asset'],
    'fixed asset remains detailed' => ['asset', 'fixed_asset', 'fixed_asset'],
    'other asset remains detailed' => ['asset', 'other_asset', 'other_asset'],
    'COGS uses the legacy expense value' => ['cogs', 'cost_of_goods_sold', 'expense'],
    'current liability uses the legacy liability value' => [null, 'current_liability', 'liability'],
    'long-term liability remains detailed' => ['liability', 'long_term_liability', 'long_term_liability'],
    'equity remains equity' => ['equity', 'equity', 'equity'],
    'revenue remains revenue' => ['revenue', 'revenue', 'revenue'],
    'other income remains detailed' => ['revenue', 'other_income', 'other_income'],
    'expense remains expense' => ['expense', 'expense', 'expense'],
    'other expense remains detailed' => ['expense', 'other_expense', 'other_expense'],
    'combined other income and expense uses legacy expense' => ['expense', 'other_income_expense', 'expense'],
]);

test('COA export and import template use the legacy nine-column format', function () {
    $template = new AccountsTemplateExport();
    $row = $template->collection()->first();
    $mappedRow = array_combine($template->headings(), $template->map($row));
    $invalidRows = $template->collection()
        ->map(fn (array $templateRow) => array_combine($template->headings(), $template->map($templateRow)))
        ->filter(fn (array $templateRow) => validator($templateRow, (new AccountsImport())->rules())->fails());

    expect($template->headings())->toBe([
        'code',
        'name',
        'description',
        'classification_type',
        'is_header',
        'is_cash_bank',
        'is_active',
        'level',
        'parent_code',
    ])->and((new AccountsExport())->headings())->toBe($template->headings())
        ->and($mappedRow)->not->toHaveKey('account_type')
        ->and($invalidRows)->toBeEmpty();
});
