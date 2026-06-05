<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class BankReconciliationTemplateExport implements FromCollection, WithHeadings, WithColumnWidths
{
    public function collection()
    {
        return collect([
            [
                'tanggal' => now()->format('Y-m-d'),
                'referensi' => 'TRF-001',
                'deskripsi' => 'Pembayaran dari PT ABC',
                'kode_akun' => '11000200',
                'nama_akun' => 'Piutang Dagang',
                'debit' => 0,
                'kredit' => 5000000,
                'catatan' => 'Pembayaran invoice',
                'invoice_no' => 'INV2026000039',
            ],
            [
                'tanggal' => now()->format('Y-m-d'),
                'referensi' => 'FEE-001',
                'deskripsi' => 'Biaya administrasi bank',
                'kode_akun' => '51000100',
                'nama_akun' => 'Biaya Bank',
                'debit' => 25000,
                'kredit' => 0,
                'catatan' => 'Biaya admin bulanan',
                'invoice_no' => '',
            ],
        ]);
    }

    public function headings(): array
    {
        return ['Tanggal', 'Referensi', 'Deskripsi', 'Kode Akun', 'Nama Akun', 'Debit', 'Kredit', 'Catatan', 'Invoice No'];
    }

    public function columnWidths(): array
    {
        return ['A' => 16, 'B' => 20, 'C' => 40, 'D' => 15, 'E' => 25, 'F' => 20, 'G' => 20, 'H' => 30, 'I' => 20];
    }
}
