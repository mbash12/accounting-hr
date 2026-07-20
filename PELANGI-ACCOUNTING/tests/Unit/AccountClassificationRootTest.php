<?php

use App\Models\Account;

uses(Tests\TestCase::class);

test('digitLength strips dots and non-digits', function () {
    expect(Account::digitLength('10'))->toBe(2)
        ->and(Account::digitLength('10.01'))->toBe(4)
        ->and(Account::digitLength('10.01.001'))->toBe(7)
        ->and(Account::digitLength('1'))->toBe(1)
        ->and(Account::digitLength('120000'))->toBe(6);
});

test('classification roots are accounts with minimum digit length', function () {
    $accounts = collect([
        (object) ['id' => 1, 'code' => '10', 'company_id' => 99],
        (object) ['id' => 2, 'code' => '20', 'company_id' => 99],
        (object) ['id' => 3, 'code' => '30', 'company_id' => 99],
        (object) ['id' => 4, 'code' => '10.01', 'company_id' => 99],
        (object) ['id' => 5, 'code' => '10.01.001', 'company_id' => 99],
    ]);

    $min = $accounts->min(fn ($a) => Account::digitLength($a->code));
    expect($min)->toBe(2);

    $roots = $accounts->filter(fn ($a) => Account::digitLength($a->code) === $min)->pluck('code')->values()->all();
    expect($roots)->toBe(['10', '20', '30']);
});

test('classic 1-9 roots still qualify as minimum digit length', function () {
    $accounts = collect([
        (object) ['code' => '1'],
        (object) ['code' => '2'],
        (object) ['code' => '100000'],
    ]);

    $min = $accounts->min(fn ($a) => Account::digitLength($a->code));
    expect($min)->toBe(1);

    $roots = $accounts->filter(fn ($a) => Account::digitLength($a->code) === $min)->pluck('code')->all();
    expect($roots)->toBe(['1', '2']);
});
