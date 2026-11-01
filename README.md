::: {align="center"}

# Sistem Kasir Toko ATK Maju Jaya

**Aplikasi Point of Sale (POS) berbasis web untuk membantu proses
penjualan, pengelolaan produk, pelanggan, stok, dan laporan penjualan.**

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?logo=mysql&logoColor=white)
![Tailwind
CSS](https://img.shields.io/badge/Tailwind_CSS-CDN-06B6D4?logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/License-Not%20Specified-lightgrey)
:::

---

## Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Teknologi](#-teknologi-yang-digunakan)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi-dan-konfigurasi)
  - [1. Siapkan Folder Project](#1-siapkan-folder-project)
  - [2. Jalankan Web Server](#2-jalankan-web-server)
  - [3. Buat Database](#3-buat-database)
  - [4. Import Database](#4-import-database)
  - [5. Atur Koneksi Database](#5-atur-koneksi-database)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Akun Demo](#-akun-demo)
- [Struktur Folder](#-struktur-folder)
- [Database](#-database)
- [Data Uji dan Skenario Pengujian](#-data-uji-dan-skenario-pengujian)

---

## 📌 Tentang Project

**Sistem Kasir Toko ATK Maju Jaya** merupakan aplikasi kasir berbasis
web yang dibuat untuk membantu toko alat tulis kantor (ATK) mengelola
aktivitas operasional secara lebih terstruktur.

Aplikasi ini ditujukan untuk menggantikan pencatatan penjualan secara
manual yang berisiko menimbulkan kesalahan perhitungan, menyulitkan
pemantauan stok, dan memperlambat pembuatan laporan.

Dengan aplikasi ini, kasir dapat melakukan transaksi penjualan,
mengelola data produk dan pelanggan, memantau persediaan, serta melihat
laporan penjualan melalui satu aplikasi.

## ✨ Fitur Utama

🔐 Login Autentikasi pengguna sebelum
mengakses aplikasi.

📊 Dashboard Menampilkan ringkasan statistik,
grafik penjualan 7 hari, dan
peringatan stok.

🛒 Kasir / POS Memilih produk, menentukan jumlah
pembelian, menghitung total,
menerima pembayaran, dan memproses
transaksi.

📦 Manajemen Produk Menambah, melihat, mengubah, dan
menghapus data produk.

👥 Manajemen Pelanggan Mengelola informasi pelanggan
seperti nama, nomor HP, dan alamat.

🧾 Riwayat Transaksi Melihat riwayat transaksi,
memfilter data, melihat detail, dan
menghapus transaksi.

📈 Laporan Penjualan Menampilkan laporan penjualan
berdasarkan periode.

⚠️ Peringatan Stok Memberikan penanda untuk produk
dengan stok menipis atau habis.

---

## 🧰 Teknologi yang Digunakan

- **[PHP](https://www.php.net/)** --- bahasa pemrograman sisi server.
- **[MySQL](https://www.mysql.com/)** --- sistem manajemen basis data.
- **[PDO](https://www.php.net/manual/en/book.pdo.php)** --- koneksi
  dan interaksi PHP dengan database.
- **[Tailwind CSS](https://tailwindcss.com/)** --- styling antarmuka
  melalui CDN.
- **[Font Awesome](https://fontawesome.com/)** --- ikon antarmuka
  melalui CDN.
- **[Apache](https://httpd.apache.org/)** --- web server, dapat
  dijalankan melalui XAMPP atau Laragon.

> **Catatan:** Tailwind CSS dan Font Awesome dimuat melalui CDN. Koneksi
> internet diperlukan agar styling dan ikon dapat dimuat. Untuk
> penggunaan offline, unduh library terkait dan ubah pemanggilan CDN
> menjadi file lokal.

## 💻 Persyaratan Sistem

---

| Perangkat lunak Versi minimum Keterangan |
| ---------------------------------------- |

PHP 8.0 (disarankan 8.1+) Wajib mendukung PDO
MySQL dan session.
MySQL / MariaDB MySQL 8.0 / MariaDB 10.4 Database aplikasi.
Apache / Nginx Apache 2.4+ / Nginx 1.20+ Web server.
Browser Versi terbaru Chrome, Edge, atau
Firefoxz
Composer Tidak diperlukan Project menggunakan
PHP native tanpa framework.

---

### Ekstensi PHP

Pastikan ekstensi berikut aktif pada konfigurasi PHP:

- `pdo_mysql` --- koneksi ke MySQL.
- `mbstring` --- pengolahan string multibyte.
- `session` --- pengelolaan sesi login.
- `json` --- pengolahan data JSON pada keranjang transaksi.

**Pilihan paket server lokal:**

- [XAMPP](https://www.apachefriends.org/) --- paket Apache, PHP, dan
  MySQL.
- [Laragon](https://laragon.org/) --- lingkungan pengembangan lokal
  untuk Windows.

---

## 🚀 Instalasi dan Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan project di komputer
lokal.

### 1. Siapkan Folder Project

Unduh atau clone repository, kemudian letakkan folder project di
direktori web server.

**XAMPP (Windows):**

```text
C:\xampp\htdocs\sia-kasir
```

**Laragon (Windows):**

```text
C:\laragon\www\sia-kasir
```

**Linux (Apache):**

```text
/var/www/html/sia-kasir
```

### 2. Jalankan Web Server

**Menggunakan XAMPP:**

1.  Buka **XAMPP Control Panel**.
2.  Klik **Start** pada modul **Apache**.
3.  Klik **Start** pada modul **MySQL**.
4.  Pastikan keduanya berjalan.

**Menggunakan Laragon:**

1.  Buka Laragon.
2.  Klik **Start All**.
3.  Pastikan Apache dan MySQL aktif.

**Menggunakan Linux:**

```bash
sudo systemctl start apache2
sudo systemctl start mysql
```

### 3. Buat Database

Buka phpMyAdmin melalui <http://localhost/phpmyadmin>, lalu buat
database dengan nama `db_kasir_atk`.

Atau jalankan perintah SQL berikut:

```sql
CREATE DATABASE db_kasir_atk
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
```

### 4. Import Database

File database: **`db_kasir_atk.sql`** (berada di root folder project).

**Melalui phpMyAdmin:**

1.  Buka [phpMyAdmin](http://localhost/phpmyadmin).
2.  Pilih database `db_kasir_atk`.
3.  Buka tab **Import**.
4.  Klik **Choose File**, lalu pilih `db_kasir_atk.sql`.
5.  Klik **Import** atau **Go**.
6.  Pastikan proses import berhasil.

**Melalui terminal:**

```bash
mysql -u root -p db_kasir_atk < db_kasir_atk.sql
```

Jalankan perintah tersebut dari direktori yang berisi file SQL, atau
sesuaikan lokasi file.

### 5. Atur Koneksi Database

Buka file **`config/koneksi.php`**, lalu sesuaikan konfigurasi database
dengan komputer masing-masing.

```php
<?php

$host = 'localhost';
$db   = 'db_kasir_atk';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}
```

**Konfigurasi default yang umum digunakan:**

Lingkungan Username Password

---

XAMPP `root` Kosong
Laragon `root` Kosong

Jika menggunakan konfigurasi lain atau hosting, sesuaikan `$host`,
`$db`, `$user`, dan `$pass`.

---

## 🌐 Menjalankan Aplikasi

Setelah Apache, MySQL, dan database siap, buka browser dan akses:

- **Halaman aplikasi:** <http://localhost/sia-kasir/>
- **Halaman login:** <http://localhost/sia-kasir/login.php>

> Jika nama folder project berbeda, ganti `sia-kasir` pada URL sesuai
> nama folder yang digunakan. Jika Apache berjalan pada port selain 80,
> sertakan port tersebut, misalnya `http://localhost:8080/sia-kasir/`.

### Navigasi Halaman

---

| Menu      | Path                  | Kegunaan                                                 |
| --------- | --------------------- | -------------------------------------------------------- |
| Login     | `login.php`           | Autentikasi pengguna.                                    |
| Dashboard | `dashboard/index.php` | Ringkasan statistik, grafik 7 hari, dan peringatan stok. |
| Kasir     | `kasir/index.php`     | Memproses transaksi penjualan.                           |
| Produk    | `produk/index.php`    | Mengelola data produk.                                   |
| Pelanggan | `pelanggan/index.php` | Mengelola data pelanggan.                                |
| Transaksi | `transaksi/index.php` | Melihat riwayat dan detail transaksi.                    |
| Laporan   | `laporan/index.php`   | Melihat laporanpenjualan per periode.                    |

---

**Alur penggunaan aplikasi:**

```text
Login
  ↓
Dashboard
  ├── Kasir
  ├── Produk
  ├── Pelanggan
  ├── Transaksi
  └── Laporan
```

## 🔑 Akun Demo

Database contoh menyediakan satu akun untuk mencoba aplikasi.

Informasi Nilai

---

| username | password   | role    |
| -------- | ---------- | ------- |
| `admin`  | `admin123` | `admin` |

Gunakan akun tersebut pada halaman
[Login](http://localhost/sia-kasir/login.php) setelah database berhasil
diimpor.

> **Keamanan:** Akun di atas merupakan akun uji. Ganti password sebelum
> aplikasi digunakan pada lingkungan produksi. Jangan gunakan kredensial
> demo untuk server publik.

---

## 📁 Struktur Folder

Gambaran struktur direktori utama project:

```text
sia-kasir/
├── config/
│   └── koneksi.php              # Konfigurasi koneksi PDO
├── includes/
│   └── helpers.php              # Fungsi bantuan aplikasi
├── layouts/
│   ├── header.php               # Bagian head dan awal layout
│   ├── sidebar.php              # Navigasi sidebar
│   ├── navbar.php               # Navigasi bagian atas
│   └── footer.php               # Bagian footer layout
├── assets/
│   └── css/
│       └── style.css            # Custom CSS
├── dashboard/
│   └── index.php                # Halaman dashboard
├── produk/
│   ├── index.php                # Daftar produk
│   ├── tambah.php               # Tambah produk
│   ├── edit.php                 # Ubah produk
│   └── hapus.php                # Hapus produk
├── kasir/
│   ├── index.php                # Halaman POS
│   └── proses.php               # Proses transaksi
├── pelanggan/                   # Modul pelanggan
├── transaksi/
│   ├── index.php                # Riwayat transaksi
│   ├── detail.php               # Detail transaksi
│   └── hapus.php                # Hapus transaksi
├── laporan/
│   └── index.php                # Laporan penjualan
├── login.php                    # Halaman login
├── logout.php                   # Proses logout
└── db_kasir_atk.sql             # Struktur dan data database
```

---

## 🗄️ Database

Database utama aplikasi bernama **`db_kasir_atk`**.

Tabel inti yang tercantum pada struktur database project:

| No  | Nama tabel                | Keterangan                               |
| --- | ------------------------- | ---------------------------------------- |
| 1   | `tbl_user`                | Menyimpan akun pengguna.                 |
| 2   | `tbl_products`            | Menyimpan data produk dan stok.          |
| 3   | `tbl_pelanggan`           | Menyimpan data pelanggan.                |
| 4   | `tbl_transaction`         | Menyimpan informasi utama transaksi.     |
| 5   | `tbl_transaction_details` | Menyimpan rincian produk pada transaksi. |

---

## 🧪 Data Uji dan Skenario Pengujian

### Data contoh bawaan

Setelah file SQL diimpor, database contoh memuat data berikut.

**Pelanggan**

| ID  | Nama            | No. HP         | Alamat  |
| --- | --------------- | -------------- | ------- |
| 1   | `Ihsan Baihaqi` | `080123456789` | Saentis |

---

**Produk**

| Kode | Nama   | Kategori  | Satuan | Harga | Stok |
| ---- | ------ | --------- | ------ | ----- | ---- |
| P001 | Pulpen | Peralatan | pcs    | 3000  | 20   |

---

### Menambah data produk

Produk dapat ditambahkan melalui menu **Produk → Tambah Produk**. Kode
produk disebut dibuat otomatis oleh aplikasi.

Contoh penambahan produk melalui SQL:

```sql
INSERT INTO tbl_products
    (kode_produk, nama, kategori, satuan, harga, stok)
VALUES
    ('P002', 'Gula Pasir', 'Bahan Pokok', 'kg', 15000, 50),
    ('P003', 'Air Mineral', 'Minuman', 'dus', 45000, 20),
    ('P004', 'Kopi Sachet', 'Minuman', 'pcs', 3000, 100);
```

### Menguji status stok

Contoh berikut dapat digunakan untuk menguji penanda stok. Sesuaikan
kode produk jika data di database berbeda.

```sql
-- Menguji status stok habis
UPDATE tbl_products
SET stok = 0
WHERE kode_produk = 'P001';

-- Menguji status stok menipis
UPDATE tbl_products
SET stok = 5
WHERE kode_produk = 'P002';

-- Menguji status stok aman
UPDATE tbl_products
SET stok = 100
WHERE kode_produk = 'P003';
```

Batas stok menipis adalah **10 atau
kurang**.

### Menguji transaksi penjualan

1.  Login menggunakan akun demo.
2.  Buka menu **Kasir**.
3.  Pilih produk yang akan dibeli.
4.  Masukkan jumlah produk, lalu tambahkan ke keranjang.
5.  Isi nominal **Uang Dibayar** (contoh: `100000`).
6.  Klik **Proses Transaksi**.
7.  Periksa transaksi pada menu **Transaksi**.
8.  Periksa hasilnya pada menu **Laporan**.

---

::: {align="center"}
**Sistem Kasir Toko ATK Maju Jaya**

_Dikembangkan sebagai aplikasi pengelolaan transaksi penjualan dan stok
toko ATK._
:::
