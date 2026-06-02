<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class JournalEntryBulkInputTemplateExport implements FromCollection, WithHeadings, WithColumnWidths
{
    public function collection()
    {
        return collect([
            [
                'no'                => 1,
                'tanggal_transaksi'  => now()->format('Y-m-d'),
                'no_coa'             => '1-1001',
                'nama_coa'           => 'Kas Kecil',
                'deskripsi'          => 'Contoh debit',
                'debit'              => 1000000,
                'credit'             => 0,
            ],
            [
                'no'                => 2,
                'tanggal_transaksi'  => now()->format('Y-m-d'),
                'no_coa'             => '4-1001',
                'nama_coa'           => 'Pendapatan',
                'deskripsi'          => 'Contoh kredit',
                'debit'              => 0,
                'credit'             => 1000000,
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Transaksi',
            'No COA',
            'Nama COA',
            'Deskripsi',
            'Debit',
            'Credit',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 20,
            'C' => 18,
            'D' => 30,
            'E' => 40,
            'F' => 20,
            'G' => 20,
        ];
    }
}
