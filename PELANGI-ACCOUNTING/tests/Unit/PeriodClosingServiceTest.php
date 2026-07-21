<?php

use App\Services\PeriodClosingService;
use Illuminate\Validation\ValidationException;

uses(Tests\TestCase::class);

test('yearBounds returns calendar year range', function () {
    [$start, $end] = app(PeriodClosingService::class)->yearBounds(2025);

    expect($start->toDateString())->toBe('2025-01-01');
    expect($end->toDateString())->toBe('2025-12-31');
});

test('createClosingJournal returns null when there are no lines', function () {
    $svc = app(PeriodClosingService::class);
    $method = new ReflectionMethod(PeriodClosingService::class, 'createClosingJournal');
    $method->setAccessible(true);

    $result = $method->invoke($svc, 1, 2026, '2026-12-31', 'Year-End Closing 2026', []);

    expect($result)->toBeNull();
});

test('journal entry scope excludes period_closing submodule', function () {
    $query = \App\Models\JournalEntry::query()->excludePeriodClosing();

    expect($query->toSql())->toContain('sub_module');
    expect($query->getBindings())->toContain('period_closing');
});

test('unposted journals message is thrown by assertNoUnpostedJournals', function () {
    $svc = new class extends PeriodClosingService
    {
        public function countUnpostedJournals(int $companyId, int $year): int
        {
            return 3;
        }
    };

    expect(fn () => $svc->assertNoUnpostedJournals(1, 2026))
        ->toThrow(ValidationException::class);
});
