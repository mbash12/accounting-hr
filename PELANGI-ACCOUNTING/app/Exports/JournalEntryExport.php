<?php

namespace App\Exports;

use App\Models\JournalEntry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class JournalEntryExport implements FromCollection, WithHeadings, WithTitle
{
    protected int $companyId;

    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
    }

    public function collection(): Collection
    {
        $rows = collect();

        $entries = JournalEntry::with(['items.account', 'department'])
            ->where('company_id', $this->companyId)
            ->whereNull('sub_module')
            ->whereNull('reference_type')
            ->orderBy('date', 'desc')
            ->orderBy('entry_number')
            ->get();

        foreach ($entries as $entry) {
            if ($entry->items->isEmpty()) {
                $rows->push([
                    'entry_number' => $entry->entry_number,
                    'date' => $entry->date->format('Y-m-d'),
                    'reference_no' => $entry->reference_no ?? '',
                    'description' => $entry->description ?? '',
                    'account_code' => '',
                    'account_name' => '',
                    'debit' => 0,
                    'credit' => 0,
                    'notes' => '',
                    'status' => $entry->status,
                    'department' => $entry->department?->name ?? '',
                ]);
                continue;
            }

            foreach ($entry->items as $item) {
                $rows->push([
                    'entry_number' => $entry->entry_number,
                    'date' => $entry->date->format('Y-m-d'),
                    'reference_no' => $entry->reference_no ?? '',
                    'description' => $entry->description ?? '',
                    'account_code' => $item->account->code ?? '',
                    'account_name' => $item->account->name ?? '',
                    'debit' => $item->debit,
                    'credit' => $item->credit,
                    'notes' => $item->notes ?? '',
                    'status' => $entry->status,
                    'department' => $entry->department?->name ?? '',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No Entry',
            'Tanggal',
            'Referensi',
            'Deskripsi',
            'Kode Akun',
            'Nama Akun',
            'Debit',
            'Kredit',
            'Catatan',
            'Status',
            'Department',
        ];
    }

    public function title(): string
    {
        return 'Journal Entries';
    }
}
