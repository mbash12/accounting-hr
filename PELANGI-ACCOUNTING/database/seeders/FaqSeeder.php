<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        $companyId = $company ? $company->id : null;

        $data = [
            [
                'name' => 'Peraturan Umum Perusahaan',
                'sort_order' => 1,
                'faqs' => [
                    [
                        'question' => 'Jam berapa operasional kantor dimulai?',
                        'answer' => '<p>Jam kerja operasional kantor adalah mulai dari pukul <strong>08:00 hingga 17:00</strong>, dari hari Senin sampai dengan Jumat.</p>',
                        'sort_order' => 1,
                    ],
                    [
                        'question' => 'Apakah ada aturan berpakaian di kantor?',
                        'answer' => '<p>Karyawan diwajibkan mengenakan pakaian <strong>Bebas Rapi</strong> (Business Casual) setiap hari, kecuali pada hari-hari tertentu yang telah ditentukan perusahaan.</p>',
                        'sort_order' => 2,
                    ],
                ],
            ],
            [
                'name' => 'Cuti & Izin',
                'sort_order' => 2,
                'faqs' => [
                    [
                        'question' => 'Bagaimana prosedur pengajuan cuti tahunan?',
                        'answer' => '<p>Pengajuan cuti dapat dilakukan melalui aplikasi <strong>NUXI</strong> pada menu "Izin/Cuti". Pastikan Anda mengajukan minimal 3 hari sebelum tanggal cuti yang direncanakan.</p>',
                        'sort_order' => 1,
                    ],
                    [
                        'question' => 'Berapa hari jatah cuti tahunan yang saya miliki?',
                        'answer' => '<p>Karyawan berhak mendapatkan jatah cuti tahunan sebanyak <strong>12 hari kerja</strong> setelah masa kerja mencapai 1 tahun.</p>',
                        'sort_order' => 2,
                    ],
                    [
                        'question' => 'Apa yang harus dilakukan jika saya sakit?',
                        'answer' => '<p>Segera informasikan kepada atasan langsung atau departemen HR. Pengajuan izin sakit harus dilampirkan dengan <strong>Surat Keterangan Dokter</strong> melalui aplikasi NUXI.</p>',
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'name' => 'Penggajian & Tunjangan',
                'sort_order' => 3,
                'faqs' => [
                    [
                        'question' => 'Kapan gaji bulanan dibayarkan?',
                        'answer' => '<p>Gaji bulanan dibayarkan selambat-lambatnya pada <strong>tanggal 25</strong> setiap bulannya. Jika tanggal 25 jatuh pada hari libur, maka pembayaran akan dilakukan pada hari kerja sebelumnya.</p>',
                        'sort_order' => 1,
                    ],
                    [
                        'question' => 'Di mana saya bisa melihat rincian slip gaji (payslip)?',
                        'answer' => '<p>Rincian slip gaji dapat diakses dan diunduh secara mandiri melalui aplikasi <strong>NUXI</strong> pada menu "Profil" atau "Payslip".</p>',
                        'sort_order' => 2,
                    ],
                ],
            ],
        ];

        foreach ($data as $catData) {
            $category = FaqCategory::create([
                'name' => $catData['name'],
                'sort_order' => $catData['sort_order'],
                'company_id' => $companyId,
            ]);

            foreach ($catData['faqs'] as $faq) {
                Faq::create([
                    'faq_category_id' => $category->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['sort_order'],
                ]);
            }
        }
    }
}
