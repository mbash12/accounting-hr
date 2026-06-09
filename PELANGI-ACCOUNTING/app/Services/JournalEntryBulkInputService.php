<?php

namespace App\Services;

use App\Models\Account;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JournalEntryBulkInputService
{
    /**
     * Parse an uploaded Excel file and return an array of journal entry items.
     *
     * Expected columns: No | Tanggal Transaksi | No COA | Nama COA | Deskripsi | Debit | Credit | Saldo
     * Columns used:      No COA → account lookup, Deskripsi → notes, Debit → debit, Credit → credit
     *
     * @param string $filePath  Absolute path to the uploaded .xlsx file
     * @param int|null $companyId  Restrict account lookup to this company (null = no restriction)
     * @return array<int, array{account_id: int, notes: ?string, debit: float, credit: float}>
     *
     * @throws \RuntimeException
     */
    public function parse(string $filePath, ?int $companyId): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet   = $spreadsheet->getActiveSheet();
        $rows        = $worksheet->toArray();

        if (count($rows) < 2) {
            throw new \RuntimeException(__('The uploaded file is empty or has no data rows.'));
        }

        $headers = array_map(fn($h) => is_string($h) ? trim($h) : '', $rows[0]);

        // Detect column indices (case-insensitive, trim-safe)
        $colIndex = $this->mapColumns($headers);

        $items = [];
        $errors = [];
        $rowNumber = 1; // header row is 1, data starts at 2

        for ($i = 1, $len = count($rows); $i < $len; $i++) {
            $rowNumber = $i + 1;
            $row = $rows[$i];

            $accountCode = trim((string) ($row[$colIndex['no_coa']] ?? ''));
            $description = trim((string) ($row[$colIndex['deskripsi']] ?? ''));
            $debitRaw    = $this->parseAmount($row[$colIndex['debit']] ?? 0);
            $creditRaw   = $this->parseAmount($row[$colIndex['credit']] ?? 0);

            // Skip completely empty rows
            if ($accountCode === '' && $debitRaw == 0 && $creditRaw == 0) {
                continue;
            }

            if ($accountCode === '') {
                $errors[] = __('Row :row: Account code (No COA) is empty.', ['row' => $rowNumber]);
                continue;
            }

            // Lookup account
            $account = $this->findAccount($accountCode, $companyId);

            if (! $account) {
                $errors[] = __('Row :row: Account with code ":code" not found.', [
                    'row' => $rowNumber,
                    'code' => $accountCode,
                ]);
                continue;
            }

            if ($debitRaw < 0 || $creditRaw < 0) {
                $errors[] = __('Row :row: Negative amounts are not allowed.', ['row' => $rowNumber]);
                continue;
            }

            if ($debitRaw == 0 && $creditRaw == 0) {
                $errors[] = __('Row :row: Must have either a debit or credit amount.', ['row' => $rowNumber]);
                continue;
            }

            if ($debitRaw > 0 && $creditRaw > 0) {
                $errors[] = __('Row :row: Cannot have both debit and credit on the same line.', ['row' => $rowNumber]);
                continue;
            }

            $items[] = [
                'account_id'    => $account->id,
                'notes'         => $description ?: null,
                'debit'         => round($debitRaw, 2),
                'credit'        => round($creditRaw, 2),
            ];
        }

        if (! empty($errors)) {
            throw new \RuntimeException(implode("\n", $errors));
        }

        if (empty($items)) {
            throw new \RuntimeException(__('No valid items found in the uploaded file.'));
        }

        return $items;
    }

    /**
     * Map Excel header names to column indices.
     *
     * @param array<int, string> $headers
     * @return array{no_coa: int, deskripsi: int, debit: int, credit: int}
     *
     * @throws \RuntimeException when a required column is missing
     */
    protected function mapColumns(array $headers): array
    {
        $normalized = array_map(fn($h) => strtolower(str_replace(' ', '', $h)), $headers);

        $map = [];
        foreach (['no_coa' => ['ncoa', 'nocoa', 'kodeakun', 'code'], 'deskripsi' => ['deskripsi', 'description', 'keterangan', 'uraian'], 'debit' => ['debit', 'debet'], 'credit' => ['credit', 'kredit', 'kridit']] as $key => $aliases) {
            $found = false;
            foreach ($aliases as $alias) {
                $idx = array_search($alias, $normalized, true);
                if ($idx !== false) {
                    $map[$key] = $idx;
                    $found = true;
                    break;
                }
            }
            if (! $found) {
                throw new \RuntimeException(__('Required column not found: :col (expected one of: :aliases)', [
                    'col' => $key,
                    'aliases' => implode(', ', $aliases),
                ]));
            }
        }

        return $map;
    }

    /**
     * Find an account by its code, optionally scoped to a company.
     */
    protected function findAccount(string $code, ?int $companyId): ?Account
    {
        $query = Account::where('is_header', false)->where('is_active', true);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->where('code', $code)->first();
    }

    protected function parseAmount(mixed $value): float
    {
        // Already a number type (int/float) — no string parsing needed
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }
        $str = trim((string) $value);
        if ($str === '') {
            return 0.0;
        }
        $dotCount = substr_count($str, '.');
        $commaCount = substr_count($str, ',');

        if ($dotCount > 1) {
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        } elseif ($commaCount > 1) {
            $str = str_replace(',', '', $str);
        } elseif ($dotCount === 1 && $commaCount === 1) {
            $lastComma = strrpos($str, ',');
            $lastDot = strrpos($str, '.');
            if ($lastComma > $lastDot) {
                $str = str_replace('.', '', $str);
                $str = str_replace(',', '.', $str);
            } else {
                $str = str_replace(',', '', $str);
            }
        } elseif ($dotCount === 1 && $commaCount === 0) {
            $parts = explode('.', $str);
            if (strlen($parts[1]) === 3) {
                $str = str_replace('.', '', $str);
            }
        } elseif ($commaCount === 1 && $dotCount === 0) {
            $parts = explode(',', $str);
            if (strlen($parts[1]) === 3) {
                $str = str_replace(',', '', $str);
            } else {
                $str = str_replace(',', '.', $str);
            }
        }

        return (float) str_replace(' ', '', $str);
    }
}
