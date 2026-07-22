<?php

use App\Models\Account;
use Database\Seeders\AccountTemplateSeeder;

test('normal balance follows account type instead of account code prefix', function () {
    $otherIncome = new Account(['code' => '71000100', 'account_type' => 'other_income']);
    $otherExpense = new Account(['code' => '810000', 'account_type' => 'other_expense']);
    $accumulatedDepreciation = new Account([
        'code' => '17100100',
        'account_type' => 'fixed_asset',
        'classification_type' => 'accumulated_depreciation',
    ]);

    expect($otherIncome->isRevenueAccount())->toBeTrue()
        ->and($otherIncome->isDebitNormal())->toBeFalse()
        ->and($otherIncome->balanceFromMovements(0, 100))->toBe(100.0)
        ->and($otherExpense->isExpenseAccount())->toBeTrue()
        ->and($otherExpense->isDebitNormal())->toBeTrue()
        ->and($otherExpense->balanceFromMovements(100, 0))->toBe(100.0)
        ->and($accumulatedDepreciation->isDebitNormal())->toBeFalse();
});

test('balance calculation supports contra balances', function () {
    $asset = new Account(['account_type' => 'current_asset']);
    $liability = new Account(['account_type' => 'current_liability']);

    expect($asset->balanceFromMovements(50, 120, 10))->toBe(-60.0)
        ->and($liability->balanceFromMovements(30, 100, 20))->toBe(90.0);
});

test('posted opening journal takes precedence over legacy account opening balance', function () {
    $account = new Account(['account_type' => 'current_asset', 'opening_balance' => 125000]);

    expect($account->reportOpeningBalance(false))->toBe(125000.0)
        ->and($account->reportOpeningBalance(true))->toBe(0.0);
});

test('standard COA classifications map to accounting types and cash flow groups', function () {
    $seeder = new AccountTemplateSeeder;
    $reflection = new ReflectionClass($seeder);
    $mapType = $reflection->getMethod('mapToAccountType');
    $cashFlow = $reflection->getMethod('getCashFlowType');

    expect($mapType->invoke($seeder, 'account_payable', false))->toBe('current_liability')
        ->and($mapType->invoke($seeder, 'other_revenue', false))->toBe('other_income')
        ->and($mapType->invoke($seeder, 'accumulated_depreciation', false))->toBe('fixed_asset')
        ->and($cashFlow->invoke($seeder, '17000100', 'fixed_asset'))->toBe('investing')
        ->and($cashFlow->invoke($seeder, '230000', 'long_term_liability'))->toBe('financing')
        ->and($cashFlow->invoke($seeder, '63000100', 'expense'))->toBe('undefined');
});
