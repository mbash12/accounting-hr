<?php

use App\Filament\Pages\ManageAccounts;

uses(Tests\TestCase::class);

test('account form exposes the header status field', function () {
    $fieldNames = collect((new ManageAccounts())->getAccountForm())
        ->map(fn ($field) => $field->getName())
        ->all();

    expect($fieldNames)->toContain('is_header');
});
