# Sistem Kasir Toko ATK Maju Jaya

Aplikasi kasir berbasis web dengan fitur POS, manajemen produk, pelanggan, dan laporan penjualan. Dibangun dengan **PHP native + MySQL + Tailwind CSS**.

---

## 🖥️ 1. Perangkat Lunak yang Diperlukan

| Perangkat           | Versi Minimum                   | Keterangan                                  |
| ------------------- | ------------------------------- | ------------------------------------------- |
| **PHP**             | ≥ 8.0 (disarankan 8.1+)         | Wajib mendukung PDO MySQL & `session`       |
| **MySQL / MariaDB** | MySQL ≥ 8.0 atau MariaDB ≥ 10.4 | Database utama                              |
| **Web Server**      | Apache 2.4+ / Nginx 1.20+       | Bisa pakai XAMPP/Laragon/WAMP               |
| **Composer**        | _Tidak diperlukan_              | Aplikasi pakai PHP native (tanpa framework) |
| **Browser**         | Chrome / Edge / Firefox terbaru | Untuk akses UI                              |

**Ekstensi PHP yang wajib aktif:**

- `pdo_mysql` — koneksi database
- `mbstring` — handling string multibyte
- `session` — manajemen login
- `json` — parsing cart transaksi

**Opsional:**

- **XAMPP 8.1+** (Windows/Linux/Mac) — paket all-in-one paling mudah (Apache + PHP + MySQL)
- **Laragon** (Windows) — alternatif ringan
- **Git** — untuk clone repository (jika pakai Git)

**CDN (butuh koneksi internet saat runtime):**

- Tailwind CSS — `cdn.tailwindcss.com`
- Font Awesome 6.5.1 — `cdnjs.cloudflare.com`

> ⚠️ Karena Tailwind & FontAwesome dimuat via CDN, aplikasi **butuh koneksi internet** saat dijalankan. Untuk mode offline, unduh kedua library tersebut dan ganti `<link>`/`<script>` ke path lokal.

---

## 📥 2. Langkah Instalasi

**Extrak File:**

1. Unduh file ZIP project
2. Ekstrak ke folder web server:
   - **XAMPP (Windows):** `C:\xampp\htdocs\sistem-kasir`
   - **Laragon:** `C:\laragon\www\sistem-kasir`
   - **Linux (Apache):** `/var/www/html/sistem-kasir`

### 2.2 Aktifkan Web Server

**XAMPP:**

1. Buka **XAMPP Control Panel**
2. Klik **Start** pada **Apache** dan **MySQL**
3. Pastikan keduanya berstatus hijau (running)

**Linux:**

```bash
sudo systemctl start apache2
sudo systemctl start mysql
```

## 🗄️ 3. Cara Menyiapkan Basis Data

### 3.1 Buat Database

Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) atau via CLI:

```sql
CREATE DATABASE db_kasir_atk
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
```

Atau via terminal:

```bash
mysql -u root -p -e "CREATE DATABASE db_kasir_atk CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
```

### 3.2 Import Struktur & Data

**Via phpMyAdmin:**

1. Klik database `db_kasir_atk`
2. Tab **Import** → **Choose File** → pilih `db_kasir_atk.sql`
3. Klik **Import** / **Go**
4. Tunggu notifikasi "Import has been successfully finished"

**Via terminal:**

```bash
mysql -u root -p db_kasir_atk < path/to/db_kasir_atk.sql
```

### 3.3 Verifikasi Tabel

Setelah import, pastikan **6 tabel** berikut terbentuk:

| No  | Tabel                                 | Isi                   |
| --- | ------------------------------------- | --------------------- |
| 1   | `tbl_user`                            | Akun login            |
| 2   | `tbl_products`                        | Produk & stok         |
| 3   | `tbl_pelanggan`                       | Data pelanggan        |
| 4   | `tbl_transaction`                     | Header transaksi      |
| 5   | `tbl_transaction_details`             | Detail item transaksi |
| 6   | `tbl_akun` _(opsional, untuk jurnal)_ | Chart of accounts     |

Cek via CLI:

```bash
mysql -u root -p db_kasir_atk -e "SHOW TABLES;"
```

### 3.4 Konfigurasi Koneksi Database

Edit file **`config/koneksi.php`**:

```php
<?php
$host = 'localhost';
$db   = 'db_kasir_atk';
$user = 'root';       // sesuaikan
$pass = '';           // sesuaikan (XAMPP default: kosong)

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

> 💡 **XAMPP default:** user `root`, password kosong.
> **Laragon default:** user `root`, password kosong.
> **Hosting:** sesuaikan dengan kredensial dari cPanel.

---

## 🚀 4. Cara Menjalankan Aplikasi

### 4.1 Akses via Browser

Buka:

```
http://localhost/sistem-kasir/
```

Atau langsung ke halaman login:

```
http://localhost/sistem-kasir/login.php
```

### 4.2 Alur Penggunaan

```
Login → Dashboard → Kasir / Produk / Pelanggan / Transaksi / Laporan
```

| Halaman       | URL                    | Fungsi                                           |
| ------------- | ---------------------- | ------------------------------------------------ |
| **Login**     | `/login.php`           | Autentikasi                                      |
| **Dashboard** | `/dashboard/index.php` | Ringkasan statistik + grafik 7 hari + alert stok |
| **Kasir**     | `/kasir/index.php`     | Input transaksi POS                              |
| **Produk**    | `/produk/index.php`    | CRUD produk                                      |
| **Pelanggan** | `/pelanggan/index.php` | CRUD pelanggan                                   |
| **Transaksi** | `/transaksi/index.php` | Riwayat + filter + hapus                         |
| **Laporan**   | `/laporan/index.php`   | Laporan penjualan per periode                    |

### 4.3 Troubleshooting Umum

| Masalah                               | Penyebab                           | Solusi                                                             |
| ------------------------------------- | ---------------------------------- | ------------------------------------------------------------------ |
| **"Koneksi database gagal"**          | Kredensial salah / MySQL mati      | Cek `config/koneksi.php`, pastikan MySQL running                   |
| **Halaman putih / error 500**         | PHP error tidak tampil             | Aktifkan `display_errors = On` di `php.ini` sementara              |
| **CSS/ikon tidak muncul**             | Tidak ada internet (CDN)           | Unduh Tailwind & FontAwesome ke lokal, ganti `<link>`              |
| **"Unknown column 'harga_pokok'"**    | Schema lama                        | Pastikan import **dump terbaru** (tanpa `harga_pokok`)             |
| **Redirect loop ke login**            | `session.save_path` tidak writable | Cek permission folder session PHP                                  |
| **Notifikasi "Stok tidak mencukupi"** | Stok produk kurang                 | Update stok di halaman Produk                                      |
| **Port 80 dipakai**                   | Aplikasi lain pakai port 80        | Ganti port Apache (misal 8080) → akses `http://localhost:8080/...` |

---

## 🧪 5. Lokasi Data Uji (Test Data)

### 5.1 Data Uji Bawaan (dari `db_kasir_atk.sql`)

Setelah import, database sudah berisi data contoh:

**Akun Pengguna (`tbl_user`)**
| ID | Username | Password | Role |
|----|----------|----------|------|
| 1 | `admin` | `admin123` | admin |

**Pelanggan (`tbl_pelanggan`)**
| ID | Nama | No HP | Alamat |
|----|------|-------|--------|
| 1 | Ihsan Baihaqi | 080123456789 | saentis |

**Produk (`tbl_products`)**
| Kode | Nama | Kategori | Satuan | Harga | Stok |
|------|------|----------|--------|-------|------|
| P001 | Pulpen | peralatan | pcs | 3.000 | 17 |

**Akun Akuntansi (`tbl_akun`)** — jika tabel ada
| Kode | Nama Akun | Tipe | Saldo Normal |
|------|-----------|------|--------------|
| 1-1000 | Kas | aset | debit |
| 4-1000 | Pendapatan Penjualan | pendapatan | kredit |
| 5-1000 | Harga Pokok Penjualan | beban | debit |
| 1-1300 | Persediaan Barang | aset | debit |

### 5.2 Lokasi File Data Uji

| File                 | Lokasi                                           | Keterangan          |
| -------------------- | ------------------------------------------------ | ------------------- |
| **Dump SQL**         | `db_kasir_atk.sql` (root project)                | Struktur + data uji |
| **Sample produk**    | `db_kasir_atk.sql` → `INSERT INTO tbl_products`  | 1 produk (P001)     |
| **Sample pelanggan** | `db_kasir_atk.sql` → `INSERT INTO tbl_pelanggan` | 1 pelanggan         |
| **Sample user**      | `db_kasir_atk.sql` → `INSERT INTO tbl_user`      | 1 akun admin        |

### 5.3 Menambah Data Uji Manual

**Tambah produk via UI:**

1. Login → menu **Produk** → **Tambah Produk**
2. Isi nama, kategori, satuan, harga, stok
3. Kode produk otomatis di-generate (`P002`, `P003`, …)

**Tambah via SQL:**

```sql
INSERT INTO tbl_products (kode_produk, nama, kategori, satuan, harga, stok) VALUES
('P002', 'Gula Pasir', 'Bahan Pokok', 'kg',  15000, 50),
('P003', 'Air Mineral', 'Minuman',    'dus', 45000, 20),
('P004', 'Kopi Sachet', 'Minuman',    'pcs', 3000,  100);
```

**Test skenario stok kritis** (untuk cek badge alert):

```sql
UPDATE tbl_products SET stok = 0   WHERE kode_produk = 'P001';  -- → badge "Habis"
UPDATE tbl_products SET stok = 5   WHERE kode_produk = 'P002';  -- → badge "Menipis" (≤10)
UPDATE tbl_products SET stok = 100 WHERE kode_produk = 'P003';  -- → badge "Aman"
```

### 5.4 Test Transaksi

1. Login → menu **Kasir**
2. Pilih produk → isi qty → **Tambah**
3. Ulangi untuk beberapa produk
4. Isi **Uang Dibayar** (misal 100.000)
5. Klik **Proses Transaksi**
6. Cek hasil di menu **Transaksi** dan **Laporan**

---

## 🔑 6. Akun Uji (Login)

| Field         | Nilai                                  |
| ------------- | -------------------------------------- |
| **URL Login** | `http://localhost/sia-kasir/login.php` |
| **Username**  | `admin`                                |
| **Password**  | `admin123`                             |
| **Role**      | `admin`                                |

## 📁 7. Struktur Folder

```
sia-kasir/
├── config/
│   └── koneksi.php              # Koneksi PDO
├── includes/
│   └── helpers.php              # Fungsi bantu (rupiah, bersih)
├── layouts/
│   ├── header.php               # <head>, session, body
│   ├── sidebar.php              # Menu kiri
│   ├── navbar.php               # Top bar
│   └── footer.php               # Footer + tutup HTML
├── assets/
│   └── css/style.css            # Custom CSS (opsional)
├── dashboard/index.php          # Dashboard
├── produk/
│   ├── index.php                # Daftar produk
│   ├── tambah.php               # Proses tambah
│   ├── edit.php                 # Proses edit
│   └── hapus.php                # Proses hapus
├── kasir/
│   ├── index.php                # Halaman POS
│   └── proses.php               # Proses transaksi
├── pelanggan/                   # CRUD pelanggan
├── transaksi/
│   ├── index.php                # Riwayat transaksi
│   ├── detail.php               # Detail AJAX
│   └── hapus.php                # Hapus transaksi
├── laporan/index.php            # Laporan penjualan
├── login.php                    # Halaman login
└── logout.php                   # Logout
```

---
