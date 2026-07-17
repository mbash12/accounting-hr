<?php

use App\Services\PeriodClosingService;

uses(Tests\TestCase::class);

test('yearBounds returns calendar year range', function () {
    [$start, $end] = app(PeriodClosingService::class)->yearBounds(2025);

    expect($start->toDateString())->toBe('2025-01-01');
    expect($end->toDateString())->toBe('2025-12-31');
});
