# Dokumentasi Modul Pelangi Accounting

## Daftar Modul dan Fitur

### 1. Manajemen Entitas
**Perusahaan**
- Manajemen multi-perusahaan
- Profil dan pengaturan perusahaan
- Konfigurasi pajak (PPN/PKP)
- Filter data per-perusahaan

**Pengguna**
- Manajemen akun pengguna
- Penugasan peran dan hak akses
- Kontrol akses perusahaan

**Peran (Roles)**
- Definisi dan konfigurasi peran
- Manajemen hak akses per peran

---

### 2. Data Induk
**Kontak**
- Database pelanggan dan pemasok
- Manajemen informasi kontak
- Klasifikasi (pelanggan/pemasok/keduanya)
- Status aktif/tidak aktif

**Produk**
- Katalog produk/barang
- Informasi harga dan satuan
- Klasifikasi produk
- Produk per-perusahaan

**Grup Produk**
- Kategorisasi produk
- Manajemen grup

**Satuan Ukur**
- Definisi satuan ukur
- Pengaturan konversi

**Harta Tetap**
- Registrasi dan pelacakan aset
- Perhitungan penyusutan
- Riwayat transaksi aset
- Aset per-perusahaan

**Kategori Harta Tetap**
- Klasifikasi aset
- Aturan penyusutan per kategori

**Syarat Pembayaran**
- Definisi syarat pembayaran
- Aturan perhitungan tanggal jatuh tempo

**Pajak**
- Konfigurasi tarif pajak
- Manajemen jenis pajak

---

### 3. Data Pendukung
**Jenis Usaha**
- Klasifikasi aktivitas bisnis

**Klasifikasi Transaksi**
- Kategorisasi transaksi untuk pelaporan

---

### 4. Penjualan
**Pesanan Penjualan**
- Pembuatan dan manajemen pesanan penjualan
- Pelacakan status pesanan
- Pemrosesan pesanan pelanggan
- Dukungan multi-perusahaan

**Faktur Penjualan**
- Pembuatan dan manajemen faktur
- Pelacakan status faktur (draft, dikirim, lunas, jatuh tempo)
- Pelacakan jumlah tunggakan
- Perhitungan pajak
- Pencatatan pembayaran

**Daftar Piutang**
- Laporan umur piutang pelanggan
- Ringkasan piutang tertagih
- Pelacakan status pembayaran

**Pembayaran Piutang**
- Pencatatan penerimaan pembayaran
- Alokasi pembayaran faktur
- Pelacakan metode pembayaran

---

### 5. Pembelian
**Purchase Order (Pesanan Pembelian)**
- Pembuatan pesanan pembelian
- Manajemen pesanan ke pemasok
- Pelacakan status pesanan
- Dukungan multi-perusahaan

**Faktur Pembelian**
- Pencatatan faktur pemasok
- Alur kerja persetujuan faktur
- Pelacakan pajak
- Manajemen jumlah tunggakan

**Retur Pembelian**
- Manajemen retur ke pemasok
- Otorisasi retur

**Penerimaan Barang**
- Pencatatan penerimaan barang
- Pelacakan pemenuhan PO

**Daftar Hutang Usaha**
- Laporan umur hutang pemasok
- Ringkasan hutang tertagih
- Pelacakan jatuh tempo pembayaran

**Pembayaran Hutang**
- Pencatatan pengeluaran pembayaran
- Alokasi pembayaran faktur
- Pelacakan metode pembayaran

---

### 7. HR & Payroll
**Karyawan**
- Manajemen database karyawan
- Informasi personal (NIK, nama, jabatan)
- Status pajak (PTKP)
- Registrasi BPJS
- Rekening bank untuk transfer gaji
- Konfigurasi gaji pokok

**Departemen**
- Manajemen departemen
- Konfigurasi jam kerja
- Pengaturan hari kerja
- Jadwal per-departemen

**Komponen Gaji**
- Definisi tunjangan
- Definisi potongan
- Komponen tetap vs tidak tetap
- Flag perhitungan pajak dan BPJS
- Pemetaan akun untuk integrasi

**Absensi**
- Pencatatan kehadiran harian
- Deteksi keterlambatan
- Pelacakan pulang cepat
- Status kehadiran (hadir, terlambat, alpa, izin, cuti)

**Izin / Cuti**
- Manajemen pengajuan ketidakhadiran
- Jenis izin (sakit, tahunan, melahirkan, lainnya)
- Alur kerja persetujuan
- Pelacakan status

**Aturan Lembur**
- Konfigurasi pengali lembur
- Tarif lembur hari kerja
- Tarif lembur hari libur
- Aturan per-departemen

**Periode Payroll**
- Pemrosesan gaji bulanan
- Generasi slip gaji
- Perhitungan otomatis (absensi, lembur, izin)
- Perhitungan BPJS dan pajak
- Manajemen status (draft, diproses, diposting)
- Posting ke jurnal
- **Export BCA Payroll (Format CSV standar bank)**

**THR (Tunjangan Hari Raya)**
- Perhitungan otomatis berdasarkan masa kerja (pro-rata)
- Opsi perhitungan pajak PPh21
- Posting ke jurnal otomatis (Beban THR)
- Export BCA THR Payout

**Kuota Cuti**
- Manajemen kuota cuti tahunan
- Pelacakan saldo cuti
- Kemampuan penyesuaian manual
- Tampilan saldo real-time

**Hari Libur**
- Kalender hari libur resmi
- Manajemen tanggal hari libur

---

### 8. Kas & Bank
**Penerimaan Kas**
- Pencatatan penerimaan kas
- Dukungan multi-akun
- Kategorisasi penerimaan
- Organisasi berdasarkan tanggal

**Pengeluaran Kas**
- Pencatatan pengeluaran kas
- Otorisasi pembayaran
- Dukungan multi-akun

**Transfer Kas**
- Transfer dana antar akun
- Pencatatan transaksi antar-akun

---

### 9. Jurnal Umum
**Transaksi Jurnal Umum**
- Pembuatan jurnal manual
- Manajemen entri debit/kredit
- Pelacakan referensi transaksi
- Jurnal multi-baris
- Alur kerja persetujuan jurnal

---

### 10. Buku Besar
**Buku Besar**
- Penampilan buku besar akun
- Riwayat transaksi per akun
- Pelacakan saldo
- Filter rentang tanggal

**Kelola Saldo Awal**
- Pengaturan saldo awal
- Inisialisasi periode
- Migrasi saldo

**Kelola Pemetaan Akun**
- Konfigurasi COA (Chart of Accounts)
- Pengaturan pemetaan akun

**Kelola Akun**
- Manajemen COA
- Pengaturan hierarki akun
- Konfigurasi tipe akun

---

### 11. Laporan Keuangan
**Neraca (Balance Sheet)**
- Pelaporan aset, kewajiban, ekuitas
- Periode perbandingan
- Dukungan multi-perusahaan

**Laba Rugi (Income Statement)**
- Pelaporan pendapatan dan beban
- Perhitungan laba/rugi
- Perbandingan periode

**Arus Kas (Cash Flow)**
- Pelacakan aliran kas
- Aktivitas operasional, investasi, pendanaan

**Neraca Percobaan (Trial Balance)**
- Validasi debit/kredit
- Verifikasi saldo

**General Ledger**
- Daftar transaksi terperinci
- Pelaporan berdasarkan akun

**Saldo Akun**
- Ringkasan saldo per akun
- Saldo akhir periode

---

### 12. Laporan HR & Payroll
**Laporan Payroll**
- Ringkasan gaji per periode
- Perhitungan total gaji
- Ringkasan potongan
- Persiapan transfer bank

**Laporan Kehadiran**
- Laporan performa kehadiran
- Ringkasan keterlambatan
- Ringkasan izin dan cuti
- Statistik kehadiran bulanan

---

## Catatan
- **Dukungan Multi-Perusahaan**: Sebagian besar modul mendukung fungsi multi-perusahaan dengan filter data per-perusahaan
- **Integrasi**: Modul HR & Payroll terintegrasi dengan Buku Besar melalui posting jurnal otomatis
- **Hak Akses Pengguna**: Akses ke modul dikontrol melalui hak akses berbasis peran (role-based permissions)
- **Export Perbankan**: Mendukung format CSV standard BCA untuk mempermudah proses penggajian massal
