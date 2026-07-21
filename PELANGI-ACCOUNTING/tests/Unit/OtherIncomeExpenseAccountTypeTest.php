<?php

use App\Models\Account;

uses(Tests\TestCase::class);

test('normalize forces header and expense classification for other_income_expense', function () {
    $data = Account::normalizeOtherIncomeExpenseAttributes([
        'account_type' => 'other_income_expense',
        'is_header' => false,
        'classification_type' => 'revenue',
    ]);

    expect($data['is_header'])->toBeTrue()
        ->and($data['classification_type'])->toBe('expense');
});

test('normalize leaves other types unchanged', function () {
    $data = Account::normalizeOtherIncomeExpenseAttributes([
        'account_type' => 'other_income',
        'is_header' => false,
        'classification_type' => 'revenue',
    ]);

    expect($data['is_header'])->toBeFalse()
        ->and($data['classification_type'])->toBe('revenue');
});

test('other_income child under other_income_expense parent is allowed', function () {
    $parent = new Account(['account_type' => 'other_income_expense', 'is_header' => true]);

    expect(Account::validateOtherIncomeExpenseHierarchy('other_income', $parent))->toBeNull();
});

test('other_expense child under other_income_expense parent is allowed', function () {
    $parent = new Account(['account_type' => 'other_income_expense', 'is_header' => true]);

    expect(Account::validateOtherIncomeExpenseHierarchy('other_expense', $parent))->toBeNull();
});

test('non other income/expense child under other_income_expense parent is rejected', function () {
    $parent = new Account(['account_type' => 'other_income_expense', 'is_header' => true]);

    expect(Account::validateOtherIncomeExpenseHierarchy('expense', $parent))->not->toBeNull();
});

test('nested other_income_expense is rejected', function () {
    $parent = new Account(['account_type' => 'other_income_expense', 'is_header' => true]);

    expect(Account::validateOtherIncomeExpenseHierarchy('other_income_expense', $parent))->not->toBeNull();
});
