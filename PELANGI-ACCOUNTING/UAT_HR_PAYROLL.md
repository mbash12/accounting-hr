# Skenario UAT (User Acceptance Testing) - Modul HR & Payroll

Dokumen ini berisi langkah-langkah pengujian untuk memastikan modul HR & Payroll berfungsi sesuai dengan kebutuhan bisnis dan regulasi Indonesia.

## Informasi Pengujian
- **Sistem**: Pelangi Accounting
- **Modul**: HR & Payroll
- **Bahasa**: Bahasa Indonesia

---

## 1. Tahap Persiapan (Konfigurasi)

| ID | Deskripsi Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Status (P/F) |
|:---|:---|:---|:---|:---:|
| 1.1 | Pemetaan Akun Payroll | Buka menu **Buku Besar > Pemetaan Akun**. Pilih tipe **Gaji & Upah (Payroll)**. | Muncul daftar akun (Beban Gaji, Utang Gaji, Utang Pajak, dll) untuk dipetakan. | |
| 1.2 | Pengaturan Departemen | Buka menu **Departemen**. Tambah departemen baru, isi **Jam Masuk**, **Jam Pulang**, dan **Hari Kerja**. | Data tersimpan dan jam kerja tampil di daftar. | |
| 1.3 | Definisi Komponen Gaji | Buka menu **Komponen Gaji**. Buat satu Tunjangan (Allowance) dan satu Potongan (Deduction). | Komponen berhasil dibuat dan muncul di pilihan saat input data karyawan. | |
| 1.4 | Input Data Karyawan | Buka menu **Karyawan**. Tambah karyawan baru, isi data **PTKP**, **BPJS**, dan **Gaji Pokok**. | ID Karyawan terisi otomatis dan status PTKP tervalidasi. | |
| 1.5 | Manajemen Kuota Cuti | Buka menu **Kuota Cuti**. Buat kuota tahunan untuk satu karyawan. Sesuaikan 'Kuota Terpakai' secara manual. | Sisa kuota berkurang otomatis saat kuota terpakai diisi. | |

## 2. Tahap Operasional (Harian)

| ID | Deskripsi Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Status (P/F) |
|:---|:---|:---|:---|:---:|
| 2.1 | Simulasi Absensi | Buka menu **Simulator Absensi**. Pilih karyawan, klik **Absen Masuk** setelah jam masuk departemen. | Sistem mencatat menit keterlambatan secara otomatis. | |
| 2.2 | Pengajuan Izin | Buka menu **Izin / Cuti**. Buat pengajuan baru untuk karyawan tertentu. | Data tersimpan dengan status 'Menunggu'. | |
| 2.3 | Persetujuan Izin | Edit data izin yang dibuat di 2.2, ubah status menjadi **Disetujui**. | Status berubah dan terekam siapa yang menyetujui. | |
| 2.4 | Input Lembur | Buka menu **Aturan Lembur**. Pastikan ada aturan untuk departemen terkait. Input data di **Lembur Log** (Internal Dev). | Nilai lembur muncul berdasarkan durasi jam. | |

## 3. Tahap Pemrosesan Gaji (Bulanan)

| ID | Deskripsi Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Status (P/F) |
|:---|:---|:---|:---|:---:|
| 3.1 | Buat Periode Payroll | Buka menu **Periode Payroll**. Tambah periode baru (misal: Maret 2026). Aktifkan 'Terapkan Potongan Kehadiran'. | Periode baru muncul dengan status 'Draft'. | |
| 3.2 | Generate Payslip | Di daftar Periode Payroll, klik aksi **Generate Payslip** pada baris data. | Sistem membuat data payslip untuk seluruh karyawan aktif. | |
| 3.3 | Validasi BPJS | Buka detail salah satu Payslip. Cek nilai BPJS Kesehatan & Ketenagakerjaan. | Nilai sesuai persentase regulasi (4% perush, 1% kar, dll). | |
| 3.4 | Validasi PPh21 | Cek nilai potongan PPh21 pada Payslip. | Nilai terhitung otomatis berdasarkan metode TER 2024. | |
| 3.5 | Potongan Kehadiran | Cek apakah karyawan yang terlambat (di poin 2.1) mendapatkan potongan gaji. | Muncul baris 'Potongan Kehadiran' di rincian payslip. | |

## 4. Tahap Integrasi & Pelaporan

| ID | Deskripsi Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Status (P/F) |
|:---|:---|:---|:---|:---:|
| 4.1 | Posting ke Jurnal | Di daftar Periode Payroll, klik aksi **Posting ke Jurnal**. | Status berubah menjadi 'Diposting' dan muncul nomor Jurnal Entry. | |
| 4.2 | Verifikasi Buku Besar | Buka menu **Jurnal Umum**. Cari nomor jurnal dari poin 4.1. | Jurnal balance antara Beban Gaji (Debit) dan Utang (Kredit). | |
| 4.3 | Laporan Payroll PDF | Buka menu **Laporan Payroll**. Pilih periode, klik **Unduh PDF**. | PDF terunduh dengan format landscape dan rincian yang benar. | |
| 4.4 | Laporan Kehadiran PDF| Buka menu **Laporan Kehadiran**. Pilih bulan & tahun, klik **Unduh PDF**. | PDF menampilkan rekap hadir, terlambat, dan izin per karyawan. | |

---
**Catatan Penguji:**
___________________________________________________________________________
___________________________________________________________________________
