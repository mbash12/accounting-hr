# Konversi UOM Global

## Konsep

Konversi satuan (UOM) dikelola secara global melalui **kategori**. Setiap satuan masuk ke satu kategori. Tiap kategori punya satu satuan dasar sebagai acuan. Tiap satuan menyimpan faktor konversi terhadap satuan dasar kategorinya: **1 satuan ini = X satuan dasar**.

Rumus konversi antar satuan dalam satu kategori: **satuan asal → satuan dasar → satuan tujuan**. Tanpa perlu konteks produk.

### Contoh

Kategori **"Jumlah"** dengan satuan dasar **pcs**:

| Satuan | Faktor | Arti |
|---|---|---|
| pcs | 1 | 1 pcs = 1 pcs (acuan) |
| dozen | 12 | 1 dozen = 12 pcs |
| box | 24 | 1 box = 24 pcs |

Konversi 3 box → pcs: `3 × 24 / 1 = 72 pcs`.
Konversi 5 dozen → box: `5 × 12 / 24 = 2,5 box`.

## Struktur Data

### Tabel Kategori Satuan
- Nama kategori, contoh: "Berat", "Panjang", "Jumlah"
- Satuan dasar — FK ke tabel satuan, sebagai acuan dalam kategori ini

### Tabel Satuan
- FK ke kategori (nullable — kosong berarti satuan belum masuk kategori)
- `conversion_factor` — **1 satuan ini = X satuan dasar kategori**. Default 1.

### Tabel Relasi Produk-Satuan (opsional)
- Menandai satuan mana yang berlaku untuk pembelian/penjualan per produk
- `conversion_factor` di sini opsional — diisi hanya jika produk butuh faktor berbeda dari standar global

## Alur Konversi

1. Cek apakah kedua satuan dalam **kategori yang sama**. Jika tidak, konversi tidak bisa dilakukan.
2. Ambil `conversion_factor` masing-masing satuan.
3. Hasil: `kuantitas × faktorAsal / faktorTujuan`.

Jika satuan asal dan tujuan sama, hasil = kuantitas (faktor 1).

## Alur Menampilkan Satuan yang Tersedia untuk Produk

Saat menampilkan dropdown satuan untuk sebuah produk:

1. **Satuan dasar produk** — selalu tampil
2. **Relasi produk-satuan** — satuan yang sengaja ditautkan ke produk, dengan flag pembelian/penjualan
3. **Satuan sekategori** — semua satuan lain dalam kategori yang sama dengan satuan dasar produk, otomatis dianggap tersedia

Ketiga sumber digabung dan deduplikasi. Satuan dari relasi eksplisit prioritas flag-nya; satuan sekategori default berlaku untuk pembelian dan penjualan.

## Mengapa Faktor di Satuan, Bukan di Pivot

Satu satuan hanya masuk ke satu kategori. Menyimpan faktor langsung di tabel satuan sama informatifnya dengan tabel pivot antara kategori dan satuan, tapi lebih sederhana: satu tabel lebih sedikit, satu query lebih sedikit.

## Satuan Dasar Selalu Berfaktor 1

Satuan dasar adalah acuan, faktornya 1 menurut definisi. Dijaga di dua level:
- **UI**: field faktor dikunci saat satuan yang sedang diedit adalah satuan dasar kategorinya
- **Backend**: setiap kali kategori disimpan, satuan dasarnya dipaksa `conversion_factor = 1`

## Service Konversi

Satu kelas service tunggal menangani semua logika konversi. Cukup dipanggil saat dibutuhkan, tidak perlu registrasi khusus.

Tanggung jawab service:
- Konversi kuantitas antar dua satuan
- Mengambil faktor konversi antar dua satuan (dengan fallback ke relasi produk-satuan bila disediakan konteks produk)
- Mengambil daftar satuan yang tersedia untuk sebuah produk
- Mengambil opsi satuan siap pakai untuk dropdown form, lengkap dengan label faktor
