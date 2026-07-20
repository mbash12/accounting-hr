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

test('classification roots are accounts without a parent', function () {
    $root = new Account(['code' => '10', 'parent_id' => null]);
    $child = new Account(['code' => '10.01', 'parent_id' => 1]);

    expect($root->isClassificationRoot())->toBeTrue()
        ->and($child->isClassificationRoot())->toBeFalse();
});

test('classic and dotted top-level codes both qualify when parent_id is null', function () {
    foreach (['1', '10', '20', '30'] as $code) {
        $account = new Account(['code' => $code, 'parent_id' => null]);
        expect($account->isClassificationRoot())->toBeTrue();
    }
});
