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

    public function __construct(int $companyId, int $userId)
    {
        $this->companyId = $companyId;
        $this->userId = $userId;
    }

    public function prepareForValidation(array $row): array
    {
        $row['no_entry'] = (string) ($row['no_entry'] ?? $row['no'] ?? '');
        $row['tanggal'] = (string) ($row['tanggal'] ?? $row['date'] ?? '');
        $row['kode_akun'] = (string) ($row['kode_akun'] ?? $row['no_coa'] ?? $row['account_code'] ?? '');
        $row['deskripsi'] = (string) ($row['deskripsi'] ?? $row['description'] ?? '');
        $row['referensi'] = (string) ($row['referensi'] ?? $row['reference_no'] ?? '');
        $row['catatan'] = (string) ($row['catatan'] ?? $row['notes'] ?? '');
        $row['debit'] = (float) ($row['debit'] ?? 0);
        $row['kredit'] = (float) ($row['kredit'] ?? $row['credit'] ?? 0);

        return $row;
    }

    public function collection(Collection $rows): void
    {
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

            DB::transaction(function () use ($entryNumber, $date, $description, $referenceNo, $items, $totalDebit) {
                $journalEntry = JournalEntry::create([
                    'entry_number' => $entryNumber,
                    'date' => $date,
                    'reference_no' => $referenceNo ?: null,
                    'description' => $description ?: null,
                    'amount' => $totalDebit,
                    'total_amount' => $totalDebit,
                    'status' => 'draft',
                    'is_posted' => false,
                    'company_id' => $this->companyId,
                    'created_by_user_id' => $this->userId,
                ]);

                foreach ($items as $row) {
                    $accountCode = trim($row['kode_akun'] ?? $row['no_coa'] ?? $row['account_code'] ?? '');
                    $account = Account::where('code', $accountCode)
                        ->where('company_id', $this->companyId)
                        ->where('is_header', false)
                        ->first();

                    if (!$account) {
                        $this->errors[] = "Entry {$entryNumber}: Account '{$accountCode}' not found";
                        continue;
                    }

                    JournalEntryItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $account->id,
                        'debit' => (float) ($row['debit'] ?? 0),
                        'credit' => (float) ($row['kredit'] ?? $row['credit'] ?? 0),
                        'notes' => trim($row['catatan'] ?? $row['notes'] ?? $row['deskripsi'] ?? $row['description'] ?: ''),
                    ]);
                }
            });
        }

        if (!empty($this->errors)) {
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
