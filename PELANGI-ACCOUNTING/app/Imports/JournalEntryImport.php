<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JournalEntryImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected int $companyId;
    protected int $userId;
    protected array $errors = [];

    /**
     * Tracks the last non-empty values for entry-level fields to carry forward across rows.
     * Users typically fill these only on the first row of a multi-row journal entry group.
     */
    private static ?array $lastEntryFields = null;

    public function __construct(int $companyId, int $userId)
    {
        $this->companyId = $companyId;
        $this->userId = $userId;
        self::$lastEntryFields = null;
    }

    public function prepareForValidation(array $row): array
    {
        $noEntry   = (string) ($row['no_entry'] ?? $row['no'] ?? '');
        $tanggal   = (string) ($row['tanggal'] ?? $row['date'] ?? '');
        $kodeAkun  = (string) ($row['kode_akun'] ?? $row['no_coa'] ?? $row['account_code'] ?? '');
        $debit     = (float) ($row['debit'] ?? 0);
        $kredit    = (float) ($row['kredit'] ?? $row['credit'] ?? 0);

        // A row with no account code and no amounts is a phantom/empty Excel row.
        $hasLineData = $kodeAkun !== '' || $debit > 0 || $kredit > 0;

        if (! $hasLineData && $noEntry === '') {
            // Phantom row: fill placeholders so it passes validation, filtered out in collection().
            $row['no_entry']  = '__EMPTY__';
            $row['tanggal']   = '2000-01-01';
            $row['kode_akun'] = '__EMPTY__';
            $row['debit']     = 0;
            $row['kredit']    = 0;
            return $row;
        }

        // When the No Entry column is filled, this row starts a new entry group.
        // Capture all entry-level fields to carry forward to subsequent continuation rows.
        if ($noEntry !== '') {
            self::$lastEntryFields = [
                'no_entry'  => $noEntry,
                'tanggal'   => $tanggal,
                'referensi' => (string) ($row['referensi'] ?? $row['reference_no'] ?? ''),
                'deskripsi' => (string) ($row['deskripsi'] ?? $row['description'] ?? ''),
            ];
        } elseif (self::$lastEntryFields !== null) {
            // Continuation row: inherit entry-level fields from the row that started the group.
            $noEntry = self::$lastEntryFields['no_entry'];
            $tanggal = self::$lastEntryFields['tanggal'];
        }

        $row['no_entry']  = $noEntry;
        $row['tanggal']   = $tanggal;
        $row['kode_akun'] = $kodeAkun;
        $row['deskripsi'] = self::$lastEntryFields !== null
            ? (self::$lastEntryFields['deskripsi'] ?? (string) ($row['deskripsi'] ?? $row['description'] ?? ''))
            : (string) ($row['deskripsi'] ?? $row['description'] ?? '');
        $row['referensi'] = self::$lastEntryFields !== null
            ? (self::$lastEntryFields['referensi'] ?? (string) ($row['referensi'] ?? $row['reference_no'] ?? ''))
            : (string) ($row['referensi'] ?? $row['reference_no'] ?? '');
        $row['catatan']   = (string) ($row['catatan'] ?? $row['notes'] ?? '');
        $row['debit']     = $debit;
        $row['kredit']    = $kredit;

        return $row;
    }

    public function collection(Collection $rows): void
    {
        // Remove phantom/empty rows that were given placeholder values to pass validation.
        $rows = $rows->filter(fn ($row) => ($row['kode_akun'] ?? '') !== '__EMPTY__');

        $grouped = $rows->groupBy(fn ($row) => trim((string) ($row['no_entry'] ?? $row['no'] ?? '')));

        $grouped->forget('');

        foreach ($grouped as $entryNumber => $items) {
            $first = $items->first();
            $date = $this->parseDate($first['tanggal'] ?? $first['date'] ?? '');
            $description = trim($first['deskripsi'] ?? $first['description'] ?? '');
            $referenceNo = trim($first['referensi'] ?? $first['reference_no'] ?? '');

            $totalDebit = $items->sum(fn ($r) => (float) ($r['debit'] ?? 0));
            $totalCredit = $items->sum(fn ($r) => (float) ($r['kredit'] ?? $r['credit'] ?? 0));

            if (abs($totalDebit - $totalCredit) > 0.01) {
                $this->errors[] = "Entry {$entryNumber}: Debit ({$totalDebit}) ≠ Credit ({$totalCredit})";
                continue;
            }

            // Resolve all accounts before entering the transaction so we don't
            // commit a partial journal entry when some account codes are missing.
            $resolvedItems = [];
            foreach ($items as $row) {
                $accountCode = trim($row['kode_akun'] ?? $row['no_coa'] ?? $row['account_code'] ?? '');
                $account = Account::where('code', $accountCode)
                    ->where('company_id', $this->companyId)
                    ->where('is_header', false)
                    ->first();

                if (! $account) {
                    $this->errors[] = "Entry {$entryNumber}: Account '{$accountCode}' not found";
                    continue 2; // skip this entire entry
                }

                $resolvedItems[] = [
                    'account_id' => $account->id,
                    'debit'      => (float) ($row['debit'] ?? 0),
                    'credit'     => (float) ($row['kredit'] ?? $row['credit'] ?? 0),
                    'notes'      => trim($row['catatan'] ?? $row['notes'] ?? $row['deskripsi'] ?? $row['description'] ?: ''),
                ];
            }

            DB::transaction(function () use ($entryNumber, $date, $description, $referenceNo, $totalDebit, $resolvedItems) {
                $journalEntry = JournalEntry::create([
                    'entry_number'       => $entryNumber,
                    'date'               => $date,
                    'reference_no'       => $referenceNo ?: null,
                    'description'        => $description ?: null,
                    'amount'             => $totalDebit,
                    'total_amount'       => $totalDebit,
                    'status'             => 'draft',
                    'is_posted'          => false,
                    'company_id'         => $this->companyId,
                    'created_by_user_id' => $this->userId,
                ]);

                foreach ($resolvedItems as $item) {
                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id'       => $item['account_id'],
                        'debit'            => $item['debit'],
                        'credit'           => $item['credit'],
                        'notes'            => $item['notes'],
                    ]);
                }
            });
        }

        if (! empty($this->errors)) {
            throw new \RuntimeException(implode("\n", $this->errors));
        }
    }

    public function rules(): array
    {
        return [
            'no_entry' => 'required',
            'tanggal' => 'required',
            'kode_akun' => 'required',
            'debit' => 'nullable|numeric|min:0',
            'kredit' => 'nullable|numeric|min:0',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'no_entry.required' => 'Entry number (No Entry) is required.',
            'tanggal.required' => 'Date (Tanggal) is required.',
            'kode_akun.required' => 'Account code (Kode Akun) is required.',
        ];
    }

    protected function parseDate(string $value): string
    {
        if (empty($value)) {
            return now()->format('Y-m-d');
        }

        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y', 'Y/m/d'];

        foreach ($formats as $format) {
            $parsed = \Carbon\Carbon::createFromFormat($format, $value);
            if ($parsed) {
                return $parsed->format('Y-m-d');
            }
        }

        return $value;
    }
}
