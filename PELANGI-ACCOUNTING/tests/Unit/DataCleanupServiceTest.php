<?php

use App\Services\DataCleanup\DataCleanupRegistry;
use App\Services\DataCleanupService;

uses(Tests\TestCase::class);

test('datasets are grouped by sidebar navigation groups', function () {
    $datasets = app(DataCleanupService::class)->datasets();

    expect($datasets)->not->toBeEmpty();

    $groups = collect($datasets)->pluck('group')->unique()->sort()->values()->all();

    expect($groups)->toContain('General Ledger')
        ->and($groups)->toContain('Master Data')
        ->and($groups)->toContain('Sales')
        ->and($groups)->toContain('Purchasing')
        ->and($groups)->toContain('Cash & Bank')
        ->and($groups)->toContain('HR & Payroll')
        ->and($groups)->not->toContain('Entity Management');
});

test('registry includes chart of accounts and journal entries', function () {
    $keys = array_keys(app(DataCleanupRegistry::class)->all());

    expect($keys)->toContain(DataCleanupService::DATASET_CHART_OF_ACCOUNTS)
        ->and($keys)->toContain('journal_entries')
        ->and($keys)->toContain(DataCleanupService::DATASET_TAXES);
});

test('clear rejects unknown dataset', function () {
    expect(fn () => app(DataCleanupService::class)->clear(['not_a_real_dataset'], 1, DataCleanupService::MODE_CASCADE))
        ->toThrow(RuntimeException::class);
});

test('clear rejects unknown mode', function () {
    expect(fn () => app(DataCleanupService::class)->clear(
        [DataCleanupService::DATASET_TAXES],
        1,
        'explode'
    ))->toThrow(RuntimeException::class);
});

test('preview returns rows for selected datasets', function () {
    $preview = app(DataCleanupService::class)->preview(
        [DataCleanupService::DATASET_TAXES],
        1,
        DataCleanupService::MODE_CASCADE
    );

    expect($preview)->toHaveKeys(['ok', 'rows', 'errors'])
        ->and($preview['ok'])->toBeTrue()
        ->and($preview['rows'])->toHaveCount(1)
        ->and($preview['rows'][0]['key'])->toBe(DataCleanupService::DATASET_TAXES);
});

test('legacy string clear still works as cascade wrapper', function () {
    expect(fn () => app(DataCleanupService::class)->clear('not_a_real_dataset', 1))
        ->toThrow(RuntimeException::class);
});
