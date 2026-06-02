<?php

use App\Services\JournalEntryBulkInputService;
use App\Models\Account;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

uses(Tests\TestCase::class);

function createTempExcel(array $rows): string
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['No', 'Tanggal Transaksi', 'No COA', 'Nama COA', 'Deskripsi', 'Debit', 'Credit', 'Saldo'];
    foreach ($headers as $col => $header) {
        $sheet->setCellValue([$col + 1, 1], $header);
    }

    foreach ($rows as $rowIdx => $row) {
        foreach ($row as $colIdx => $value) {
            $sheet->setCellValue([$colIdx + 1, $rowIdx + 2], $value);
        }
    }

    $path = sys_get_temp_dir() . '/test_bulk_input_' . uniqid() . '.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($path);

    return $path;
}

function makeStubAccount(int $id, string $code, string $name): Account
{
    $account = new Account();
    $account->id = $id;
    $account->code = $code;
    $account->name = $name;
    $account->exists = true;
    return $account;
}

/**
 * Test double that overrides findAccount to use an in-memory map.
 */
function makeService(array $accountMap): JournalEntryBulkInputService
{
    return new class($accountMap) extends JournalEntryBulkInputService {
        private array $map;

        public function __construct(array $map)
        {
            $this->map = $map;
        }

        protected function findAccount(string $code, ?int $companyId): ?Account
        {
            return $this->map[$code] ?? null;
        }
    };
}

test('parses valid Excel with multiple line items', function () {
    $path = createTempExcel([
        [1, '2026-06-01', '1-1001', 'Kas', 'Bayar listrik', 500000, 0, 500000],
        [2, '2026-06-01', '4-1001', 'Pendapatan', 'Pendapatan jasa', 0, 500000, 0],
    ]);

    $accounts = [
        '1-1001' => makeStubAccount(1, '1-1001', 'Kas'),
        '4-1001' => makeStubAccount(2, '4-1001', 'Pendapatan'),
    ];

    $items = makeService($accounts)->parse($path, null);

    expect($items)->toHaveCount(2);
    expect($items[0])->toMatchArray([
        'account_id' => 1, 'debit' => 500000.0, 'credit' => 0.0, 'notes' => 'Bayar listrik',
    ]);
    expect($items[1])->toMatchArray([
        'account_id' => 2, 'debit' => 0.0, 'credit' => 500000.0, 'notes' => 'Pendapatan jasa',
    ]);

    unlink($path);
});

test('skips empty rows', function () {
    $path = createTempExcel([
        [1, '2026-06-01', '1-1001', 'Kas', 'Debit item', 100000, 0, 100000],
        [2, '', '', '', '', 0, 0, ''],
        [3, '2026-06-01', '4-1001', 'Pendapatan', 'Credit item', 0, 100000, 0],
    ]);

    $accounts = [
        '1-1001' => makeStubAccount(1, '1-1001', 'Kas'),
        '4-1001' => makeStubAccount(2, '4-1001', 'Pendapatan'),
    ];

    $items = makeService($accounts)->parse($path, null);

    expect($items)->toHaveCount(2);

    unlink($path);
});

test('throws for unknown account code', function () {
    $path = createTempExcel([
        [1, '2026-06-01', '9-9999', 'Tidak Ada', 'Fake account', 50000, 0, 50000],
        [2, '2026-06-01', '4-1001', 'Pendapatan', 'Real account', 0, 50000, 0],
    ]);

    $accounts = [
        '4-1001' => makeStubAccount(2, '4-1001', 'Pendapatan'),
    ];

    expect(fn() => makeService($accounts)->parse($path, null))
        ->toThrow(\RuntimeException::class, '9-9999');

    unlink($path);
});

test('throws when row has both debit and credit', function () {
    $path = createTempExcel([
        [1, '2026-06-01', '1-1001', 'Kas', 'Bad row', 100000, 50000, 50000],
    ]);

    $accounts = [
        '1-1001' => makeStubAccount(1, '1-1001', 'Kas'),
    ];

    expect(fn() => makeService($accounts)->parse($path, null))
        ->toThrow(\RuntimeException::class, 'both debit and credit');

    unlink($path);
});

test('throws when file has no data rows', function () {
    $path = createTempExcel([]);

    expect(fn() => makeService([])->parse($path, null))
        ->toThrow(\RuntimeException::class, 'empty');

    unlink($path);
});

test('handles decimal amounts correctly', function () {
    $path = createTempExcel([
        [1, '2026-06-01', '1-1001', 'Kas', 'With decimals', 150000.75, 0, 150000.75],
        [2, '2026-06-01', '4-1001', 'Pendapatan', 'Credit', 0, 150000.75, 0],
    ]);

    $accounts = [
        '1-1001' => makeStubAccount(1, '1-1001', 'Kas'),
        '4-1001' => makeStubAccount(2, '4-1001', 'Pendapatan'),
    ];

    $items = makeService($accounts)->parse($path, null);

    expect($items[0]['debit'])->toEqual(150000.75);
    expect($items[1]['credit'])->toEqual(150000.75);

    unlink($path);
});

test('column mapping recognizes standard headers with spaces', function () {
    $service = makeService([]);

    $r = new ReflectionMethod(JournalEntryBulkInputService::class, 'mapColumns');
    $map = $r->invoke(new JournalEntryBulkInputService(), ['No', 'Tanggal Transaksi', 'No COA', 'Nama COA', 'Deskripsi', 'Debit', 'Credit', 'Saldo']);

    expect($map)->toMatchArray([
        'no_coa' => 2, 'deskripsi' => 4, 'debit' => 5, 'credit' => 6,
    ]);
});

test('column mapping recognizes alternative header names', function () {
    $service = makeService([]);

    $r = new ReflectionMethod(JournalEntryBulkInputService::class, 'mapColumns');
    $map = $r->invoke(new JournalEntryBulkInputService(), ['nocoa', 'uraian', 'debet', 'kredit']);

    expect($map)->toMatchArray([
        'no_coa' => 0, 'deskripsi' => 1, 'debit' => 2, 'credit' => 3,
    ]);
});
