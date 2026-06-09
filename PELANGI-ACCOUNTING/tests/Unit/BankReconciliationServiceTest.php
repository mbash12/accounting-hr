<?php

use App\Services\BankReconciliationService;
use App\Models\BankAccount;
use App\Models\Bank;
use App\Models\SalesInvoice;
use App\Models\Contact;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);

function createBankStatementExcel(array $rows): string
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Date', 'Description', 'Debit', 'Credit'];
    foreach ($headers as $col => $header) {
        $sheet->setCellValue([$col + 1, 1], $header);
    }

    foreach ($rows as $rowIdx => $row) {
        foreach ($row as $colIdx => $value) {
            $sheet->setCellValue([$colIdx + 1, $rowIdx + 2], $value);
        }
    }

    $path = sys_get_temp_dir() . '/test_bank_stmt_' . uniqid() . '.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($path);

    return $path;
}

test('parses bank statement Excel correctly', function () {
    $path = createBankStatementExcel([
        ['2026-06-15', 'Customer payment', 0, 5000000],
        ['2026-06-15', 'Bank fee', 25000, 0],
    ]);

    // Use reflection to test the private readBankStatement method
    $r = new \ReflectionMethod(BankReconciliationService::class, 'readBankStatement');
    $lines = $r->invoke(new BankReconciliationService(), $path);

    expect($lines)->toHaveCount(2);
    expect($lines[0])->toMatchArray(['type' => 'outgoing', 'debit' => 0.0, 'credit' => 5000000.0]);
    expect($lines[1])->toMatchArray(['type' => 'incoming', 'debit' => 25000.0, 'credit' => 0.0]);

    unlink($path);
});

test('skips empty rows in bank statement', function () {
    $path = createBankStatementExcel([
        ['2026-06-15', 'Payment', 0, 100000],
        ['', '', 0, 0],
        ['2026-06-16', 'Fee', 5000, 0],
    ]);

    $r = new \ReflectionMethod(BankReconciliationService::class, 'readBankStatement');
    $lines = $r->invoke(new BankReconciliationService(), $path);

    expect($lines)->toHaveCount(2);

    unlink($path);
});

test('throws when both debit and credit', function () {
    $path = createBankStatementExcel([
        ['2026-06-15', 'Bad row', 50000, 30000],
    ]);

    $r = new \ReflectionMethod(BankReconciliationService::class, 'readBankStatement');

    expect(fn() => $r->invoke(new BankReconciliationService(), $path))
        ->toThrow(\RuntimeException::class, 'both debit and credit');

    unlink($path);
});

test('throws for missing date', function () {
    $path = createBankStatementExcel([
        ['', 'No date', 10000, 0],
    ]);

    $r = new \ReflectionMethod(BankReconciliationService::class, 'readBankStatement');

    expect(fn() => $r->invoke(new BankReconciliationService(), $path))
        ->toThrow(\RuntimeException::class, 'Date');

    unlink($path);
});

test('handles various column name formats', function () {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue([1, 1], 'Transaction Date');
    $sheet->setCellValue([2, 1], 'Narration');
    $sheet->setCellValue([3, 1], 'Pengeluaran');
    $sheet->setCellValue([4, 1], 'Pemasukan');
    $sheet->setCellValue([1, 2], '2026-06-15');
    $sheet->setCellValue([2, 2], 'Test payment');
    $sheet->setCellValue([3, 2], 0);
    $sheet->setCellValue([4, 2], 500000);

    $path = sys_get_temp_dir() . '/test_alt_headers_' . uniqid() . '.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($path);

    $r = new \ReflectionMethod(BankReconciliationService::class, 'readBankStatement');
    $lines = $r->invoke(new BankReconciliationService(), $path);

    expect($lines)->toHaveCount(1);
    expect($lines[0]['type'])->toEqual('outgoing');

    unlink($path);
});
