# Dokumentasi Modul HR & Payroll - Pelangi Accounting

## 1. Pendahuluan
Modul HR & Payroll pada sistem Pelangi Accounting dirancang untuk mengelola seluruh aspek sumber daya manusia dan proses penggajian secara otomatis. Modul ini terintegrasi langsung dengan modul Akuntansi, sehingga setiap transaksi penggajian akan secara otomatis tercatat di Buku Besar (General Ledger).

## 2. Struktur Menu (Navigation)
Modul ini dapat diakses melalui grup menu **HR & Payroll** di sidebar. Berikut adalah rincian fungsionalitas setiap menu:

### A. Karyawan
Menu utama untuk mengelola basis data pekerja.
- **Informasi Personal**: Nama, NIK (KTP), Jabatan, dan Departemen.
- **Status Pajak (PTKP)**: Pilihan status (TK/0, K/1, dll) yang akan menentukan besaran potongan PPh21 secara otomatis.
- **BPJS**: Input nomor kartu BPJS Kesehatan dan Ketenagakerjaan untuk perhitungan iuran otomatis.
- **Gaji Pokok**: Nilai dasar gaji bulanan yang menjadi acuan perhitungan lembur dan iuran.
- **Rekening Bank**: Informasi untuk keperluan transfer gaji.

### B. Departemen
Digunakan untuk mengelompokkan karyawan dan menentukan jadwal kerja.
- **Jam Kerja**: Pengaturan jam masuk dan jam pulang (misal: 08:00 - 17:00).
- **Hari Kerja**: Memilih hari aktif kerja dalam seminggu. Pengaturan ini digunakan sistem untuk memvalidasi kehadiran dan mendeteksi keterlambatan.

### C. Komponen Gaji
Definisi elemen-elemen penambah (Tunjangan) atau pengurang (Potongan) gaji.
- **Tipe**: Tunjangan atau Potongan.
- **Sifat**: Tetap (rutin tiap bulan) atau Tidak Tetap.
- **Pajak & BPJS**: Opsi apakah komponen tersebut menjadi objek pajak PPh21 atau menjadi dasar perhitungan iuran BPJS.
- **Pemetaan Akun**: Setiap komponen dapat diarahkan ke akun Biaya (Expense) yang berbeda di Buku Besar.

### D. Absensi
Mencatat data kehadiran harian karyawan.
- **Menit Keterlambatan**: Sistem mencatat otomatis selisih jam masuk dengan jam kerja departemen.
- **Pulang Cepat**: Mencatat otomatis jika karyawan keluar sebelum jam pulang yang ditentukan.
- **Status**: Hadir, Terlambat, Alpa, Izin, atau Cuti.

### E. Izin / Cuti
Manajemen pengajuan ketidakhadiran karyawan.
- **Tipe Izin**: Sakit (dengan lampiran), Cuti Tahunan, Cuti Melahirkan, atau Izin Lainnya.
- **Status Persetujuan**: Menunggu, Disetujui, atau Ditolak oleh atasan/HR.

### F. Aturan Lembur
Konfigurasi perhitungan upah kerja lembur sesuai standar Depnaker (1/173).
- **Pengali Hari Kerja**: Biasanya 1.5x untuk jam pertama dan 2x untuk jam berikutnya.
- **Pengali Hari Libur**: Biasanya 2x lipat dari upah per jam.
- **Binding Departemen**: Aturan bisa dibuat berbeda antar departemen atau berlaku secara global.

### G. Periode Payroll
Pusat pemrosesan gaji bulanan.
- **Otomatisasi**: Tombol "Generate Payslip" akan mengumpulkan data absensi, lembur, izin, dan iuran BPJS/Pajak secara instan.
- **Opsi Potongan**: Tersedia pilihan untuk mengaktifkan atau menonaktifkan pemotongan gaji akibat keterlambatan secara otomatis.
- **Status**: Draft (dalam pengecekan), Diproses (siap dibayar), dan Diposting (sudah masuk pembukuan).
- **Export BCA**: Fitur export data payroll ke format CSV standar BCA (Multi-Transfer) untuk kemudahan upload ke bank.

### H. Kuota Cuti
Manajemen saldo cuti tahunan karyawan.
- **Pengaturan Tahunan**: Input kuota cuti untuk setiap karyawan per tahun (misal: 12 hari).
- **Monitoring & Penyesuaian**: Menampilkan sisa cuti secara real-time. User dapat menyesuaikan jumlah 'Kuota Terpakai' secara manual jika aplikasi baru mulai digunakan di pertengahan tahun.

### I. THR (Tunjangan Hari Raya)
Modul khusus untuk menghitung dan mengelola pembayaran THR tahunan.
- **Otomatisasi Perhitungan**: Sistem menghitung THR berdasarkan masa kerja (>= 12 bulan = 1 bulan gaji, < 12 bulan = pro-rata).
- **PPh21 Kontrol**: Tersedia opsi (checkbox) untuk mengaktifkan atau menonaktifkan perhitungan pajak PPh21 pada THR.
- **Integrasi Akuntansi**: Posting THR akan mendebit akun **Beban THR** dan mengkredit **Utang Gaji** serta **Utang PPh21**.
- **Recalculate**: Fitur untuk menghitung ulang nilai THR jika terjadi perubahan data karyawan sebelum diposting.
- **Export BCA**: Export data pembayaran THR ke format CSV BCA.

### J. Bonus
Modul untuk pengelolaan bonus atau insentif karyawan di luar gaji rutin.
- **Input Manual**: User dapat menambahkan daftar karyawan penerima bonus beserta nominalnya.
- **PPh21 Kontrol**: Tersedia opsi untuk menghitung otomatis potongan pajak atas bonus atau dibayarkan secara gross.
- **Integrasi Akuntansi**: Posting Bonus akan mendebit akun **Beban Bonus** dan mengkredit **Utang Gaji** serta **Utang PPh21**.

## 3. Fitur Kepatuhan (Indonesian Compliance)

### Perhitungan BPJS
Sistem secara otomatis menghitung porsi yang dibayar Perusahaan dan Karyawan:
- **Ketenagakerjaan**: JKK, JKM, JHT (3.7% & 2%), dan JP (Pension).
- **Kesehatan**: 4% dibayar Perusahaan, 1% dibayar Karyawan dengan batas plafon gaji yang berlaku.

### Perhitungan PPh21 (TER 2024)
Sistem menggunakan **Metode TER (Tarif Efektif Rata-rata)** terbaru sesuai regulasi pemerintah tahun 2024. Besaran pajak ditentukan langsung dari total penghasilan bruto dan kategori PTKP karyawan.

### THR (Tunjangan Hari Raya)
Sistem menghitung THR otomatis sesuai regulasi:
- Masa kerja >= 12 bulan: 1 kali Gaji Pokok.
- Masa kerja < 12 bulan: Dihitung secara proporsional (pro-rata).
- Terintegrasi dengan PPh21 (TER 2024) dengan opsi toggle pajak.

## 4. Alur Kerja Integrasi Akuntansi
Setelah payroll/THR selesai diperiksa, langkah terakhir adalah **Posting ke Jurnal**. Proses ini akan:
1. Membuat entri jurnal di modul Akuntansi secara otomatis.
2. Mendebit akun biaya yang sesuai (**Beban Gaji**, **Beban THR**, atau **Beban BPJS**).
3. Mengkredit **Utang Gaji** (bersih), **Utang PPh21**, dan **Utang BPJS**.
Hal ini memastikan sinkronisasi antara operasional HR dan laporan keuangan perusahaan.

## 5. Laporan (Reporting)
Terdapat grup menu **Laporan HR & Payroll** yang menyediakan:
- **Laporan Payroll**: Rekapitulasi gaji total per periode, berguna untuk basis transfer bank.
- **Laporan Kehadiran**: Ringkasan performa kehadiran karyawan dalam satu bulan (Total hadir, terlambat, izin).
