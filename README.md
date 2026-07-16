# StepUp Shoes — Website Toko Sepatu (Native PHP + Bootstrap + MySQL)

Aplikasi web toko sepatu dengan:
- **Home page**: etalase produk (bisa difilter per kategori)
- **Dashboard CRUD**: Create, Read, Update, Delete data produk sepatu

## Teknologi
- Native PHP (PDO, tanpa framework)
- Bootstrap 5 (via CDN)
- MySQL

## Struktur Folder
```
toko-sepatu/
├── config/
│   └── database.php        # koneksi PDO ke MySQL
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php       # helper: flash message, format rupiah, clean input
├── assets/
│   ├── css/style.css
│   └── uploads/            # gambar produk yang diupload
├── dashboard/
│   ├── index.php           # READ - daftar produk + statistik
│   ├── create.php          # CREATE - tambah produk
│   ├── edit.php             # UPDATE - edit produk
│   └── delete.php           # DELETE - hapus produk
├── sql/
│   └── toko_sepatu.sql      # struktur tabel + data contoh
└── index.php                 # HOME PAGE
```

## Cara Instalasi (XAMPP / Laragon / sejenisnya)

1. Salin folder `toko-sepatu` ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
2. Jalankan **Apache** dan **MySQL** melalui panel XAMPP/Laragon.
3. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
4. Buat database baru dengan cara import file `sql/toko_sepatu.sql`
   (ini otomatis membuat database `toko_sepatu`, tabel `produk`, dan mengisi data contoh).
5. Cek pengaturan koneksi di `config/database.php` jika username/password MySQL Anda berbeda
   (default: user `root`, password kosong).
6. Buka browser dan akses:
   - Home page: `http://localhost/toko-sepatu/index.php`
   - Dashboard: `http://localhost/toko-sepatu/dashboard/index.php`

## Fitur
- Desain responsif (mobile & desktop) menggunakan Bootstrap 5
- CRUD lengkap dengan validasi input & prepared statement (aman dari SQL Injection)
- Upload gambar produk (opsional, format jpg/jpeg/png/webp, maksimal 2MB)
- Pencarian produk berdasarkan nama/merek di dashboard
- Filter kategori di home page
- Statistik ringkas (total produk, total stok, estimasi nilai stok) di dashboard
- Flash message (notifikasi sukses/gagal) setelah aksi CRUD

## Catatan Keamanan
- Semua query menggunakan **prepared statement (PDO)** untuk mencegah SQL Injection.
- Semua output di-escape dengan `htmlspecialchars()` untuk mencegah XSS.
- Validasi file upload (tipe & ukuran) untuk mencegah upload file berbahaya.

## Pengembangan Lanjutan (opsional)
- Tambahkan sistem login admin untuk mengamankan halaman dashboard
- Tambahkan pagination pada tabel produk jika data sudah banyak
- Tambahkan fitur keranjang belanja & checkout di sisi customer
