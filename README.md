# SIA Project

Sistem Informasi Akuntansi berbasis web untuk mengelola data pengguna, pelanggan, produk, stok, kasir, dan transaksi.

## Daftar Isi

- [Tentang Project](#tentang-project)
- [Fitur](#fitur)
- [Role Pengguna](#role-pengguna)
- [Cara Instalasi](#cara-instalasi)
- [Import Database](#import-database)
- [Struktur Project](#struktur-project)
- [Struktur Database](#struktur-database)
  - [tbl_user](#tbl_user)
  - [tbl_products](#tbl_products)
  - [tbl_transaction](#tbl_transaction)
  - [tbl_transaction_details](#tbl_transaction_details)

- [Relasi Antar Tabel](#relasi-antar-tabel)
- [Cara Menggunakan Git](#cara-menggunakan-git)
- [Catatan untuk Tim](#catatan-untuk-tim)
- [Akun Default](#akun-default)

---

## Tentang Project

SIA Project merupakan Sistem Informasi Akuntansi berbasis web yang digunakan untuk membantu pengelolaan data pelanggan, produk, stok, pengguna, serta pencatatan transaksi penjualan.

Sistem menggunakan autentikasi pengguna dengan role sehingga setiap pengguna memiliki hak akses sesuai dengan perannya.

---

## Fitur

Fitur utama yang tersedia dalam sistem:

- Login dan logout pengguna
- Autentikasi berdasarkan session
- Role pengguna
- Dashboard
- Pengelolaan pelanggan
- Pengelolaan produk
- Pengelolaan stok produk
- Kasir
- Pencatatan transaksi
- Detail transaksi
- Pengelolaan user untuk admin
- Pengurangan stok otomatis ketika transaksi berhasil
- Penambahan stok produk
- Pengurangan stok produk

---

## Role Pengguna

Sistem memiliki role pengguna yang menentukan hak akses terhadap menu.

### Admin

Admin memiliki akses ke seluruh fitur:

- Dashboard
- Pelanggan
- Produk
- Kasir
- Transaksi
- User
- Logout

### Kasir

Kasir memiliki akses ke fitur operasional:

- Dashboard
- Pelanggan
- Produk
- Kasir
- Transaksi
- Logout

Menu `User` hanya dapat diakses oleh Admin.

---

## Cara Instalasi

### 1. Buka Folder XAMPP

Pastikan XAMPP sudah terinstall.

Folder project harus berada di:

```text
C:\xampp\htdocs
```

### 2. Clone Repository

Buka Command Prompt atau Git Bash, kemudian masuk ke folder `htdocs`:

```bash
cd C:\xampp\htdocs
```

Clone repository:

```bash
git clone https://github.com/IhsanBaihaqii/SIA-Project.git
```

Setelah selesai, masuk ke folder project:

```bash
cd SIA-Project
```

### 3. Jalankan XAMPP

Buka XAMPP Control Panel dan aktifkan:

```text
Apache
MySQL
```

### 4. Jalankan Project

Buka browser dan akses:

```text
http://localhost/SIA-Project/
```

---

## Import Database

Database yang digunakan dalam project ini adalah:

```text
db_sia
```

File database berada di folder:

```text
/database
```

### Menggunakan phpMyAdmin

Buka:

```text
http://localhost/phpmyadmin/
```

Kemudian:

1. Buat database dengan nama `db_sia`.
2. Pilih database `db_sia`.
3. Pilih menu `SQL`.
4. Buka file SQL yang berada di folder `/database`.
5. Salin isi file SQL.
6. Tempelkan ke halaman SQL.
7. Klik `Go` atau `Kirim`.
8. Pastikan seluruh tabel berhasil dibuat.

---

## Struktur Project

Struktur folder project:

```text
SIA-Project/
│
├── index.php
├── login.php
├── logout.php
│
├── config/
│   ├── database.php
│   └── auth.php
│
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── login.css
│   │   └── dashboard.css
│   │
│   ├── js/
│   │   ├── app.js
│   │   ├── kasir.js
│   │   └── transaksi.js
│   │
│   └── images/
│       └── logo.png
│
├── layouts/
│   ├── header.php
│   ├── sidebar.php
│   ├── navbar.php
│   └── footer.php
│
├── dashboard/
│   └── index.php
│
├── pelanggan/
│   ├── index.php
│   ├── tambah.php
│   ├── edit.php
│   ├── hapus.php
│   └── proses.php
│
├── produk/
│   ├── index.php
│   ├── tambah.php
│   ├── edit.php
│   ├── hapus.php
│   ├── stok.php
│   └── proses.php
│
├── kasir/
│   ├── index.php
│   ├── proses.php
│   └── cetak.php
│
├── transaksi/
│   ├── index.php
│   ├── detail.php
│   └── hapus.php
│
├── user/
│   ├── index.php
│   ├── tambah.php
│   ├── edit.php
│   ├── hapus.php
│   └── proses.php
│
├── includes/
│   ├── functions.php
│   └── helpers.php
│
└── database/
    └── db_sia.sql
```

### Penjelasan Folder

#### `config/`

Berisi konfigurasi utama sistem.

```text
database.php
```

Digunakan untuk koneksi ke database `db_sia`.

```text
auth.php
```

Digunakan untuk autentikasi dan pengecekan session serta role pengguna.

#### `assets/`

Berisi file pendukung tampilan dan interaksi sistem seperti CSS, JavaScript, dan gambar.

#### `layouts/`

Berisi komponen tampilan yang digunakan secara bersama-sama pada halaman dashboard.

```text
header.php
sidebar.php
navbar.php
footer.php
```

Sidebar berisi menu:

```text
Dashboard
Pelanggan
Produk
Kasir
Transaksi
User
Logout
```

Menu `User` hanya ditampilkan untuk pengguna dengan role `admin`.

#### `dashboard/`

Berisi halaman utama setelah pengguna berhasil login.

#### `pelanggan/`

Digunakan untuk mengelola data pelanggan.

Fitur:

- Menampilkan pelanggan
- Menambah pelanggan
- Mengedit pelanggan
- Menghapus pelanggan

#### `produk/`

Digunakan untuk mengelola produk dan stok.

Fitur:

- Menampilkan produk
- Menambah produk
- Mengedit produk
- Menghapus produk
- Menambah stok
- Mengurangi stok

#### `kasir/`

Digunakan untuk membuat transaksi penjualan.

Proses utama:

```text
Pilih pelanggan
       |
       v
Pilih produk
       |
       v
Masukkan jumlah
       |
       v
Cek stok
       |
       v
Simpan transaksi
       |
       v
Stok berkurang
```

#### `transaksi/`

Digunakan untuk melihat transaksi yang sudah tersimpan.

Fitur:

- Melihat daftar transaksi
- Melihat detail transaksi
- Menghapus transaksi

#### `user/`

Digunakan Admin untuk mengelola pengguna sistem.

Fitur:

- Menampilkan user
- Menambah user
- Mengedit user
- Menghapus user
- Mengatur role user

Folder ini hanya dapat diakses oleh Admin.

#### `includes/`

Berisi fungsi-fungsi umum yang digunakan oleh beberapa halaman.

---

## Struktur Database

Database menggunakan nama:

```text
db_sia
```

Database terdiri dari empat tabel utama:

```text
tbl_user
tbl_products
tbl_transaction
tbl_transaction_details
```

---

## `tbl_user`

Menyimpan data pengguna sistem.

| Kolom      | Tipe Data    | Keterangan        |
| ---------- | ------------ | ----------------- |
| `id`       | INT (PK)     | Primary Key       |
| `username` | VARCHAR(50)  | Username pengguna |
| `password` | VARCHAR(255) | Password pengguna |
| `role`     | VARCHAR(20)  | Role pengguna     |

Contoh role:

```text
admin
kasir
```

---

## `tbl_products`

Menyimpan data produk dan stok.

| Kolom        | Tipe Data    | Keterangan         |
| ------------ | ------------ | ------------------ |
| `id_product` | INT (PK)     | Primary Key        |
| `nama`       | VARCHAR(100) | Nama produk        |
| `kategori`   | VARCHAR(100) | Kategori produk    |
| `harga`      | INT          | Harga produk       |
| `stok`       | INT          | Jumlah stok produk |

Stok digunakan untuk mengetahui jumlah produk yang tersedia.

Ketika transaksi berhasil, stok produk akan berkurang berdasarkan jumlah `qty` yang terjual.

---

## `tbl_transaction`

Menyimpan data utama transaksi.

| Kolom            | Tipe Data | Keterangan        |
| ---------------- | --------- | ----------------- |
| `id_transaction` | INT (PK)  | Primary Key       |
| `tanggal`        | DATE      | Tanggal transaksi |
| `total`          | INT       | Total transaksi   |

Satu transaksi dapat memiliki beberapa produk.

---

## `tbl_transaction_details`

Menyimpan detail produk dalam setiap transaksi.

| Kolom                   | Tipe Data | Keterangan                  |
| ----------------------- | --------- | --------------------------- |
| `id_transaction_detail` | INT (PK)  | Primary Key                 |
| `id_product`            | INT (FK)  | Relasi ke `tbl_products`    |
| `id_transaction`        | INT (FK)  | Relasi ke `tbl_transaction` |
| `harga`                 | INT       | Harga produk saat transaksi |
| `qty`                   | INT       | Jumlah produk               |
| `subtotal`              | INT       | Harga × qty                 |

Nilai `subtotal` dihitung dengan:

```text
subtotal = harga × qty
```

Sedangkan total transaksi merupakan jumlah seluruh subtotal dalam satu transaksi.

---

## Relasi Antar Tabel

Struktur relasi database:

```text
tbl_products
     |
     | id_product
     |
     v
tbl_transaction_details
     ^
     |
     | id_transaction
     |
tbl_transaction
```

Relasi user:

```text
tbl_user
```

digunakan untuk proses autentikasi dan pengaturan hak akses pengguna.

### Penjelasan Relasi

- Satu produk dapat muncul pada banyak detail transaksi.
- Satu transaksi dapat memiliki banyak detail produk.
- `tbl_transaction_details` menjadi penghubung antara produk dan transaksi.
- `id_product` pada `tbl_transaction_details` merupakan foreign key ke `tbl_products`.
- `id_transaction` pada `tbl_transaction_details` merupakan foreign key ke `tbl_transaction`.
- Harga pada `tbl_transaction_details` menyimpan harga produk pada saat transaksi.
- Stok produk disimpan pada `tbl_products`.

---

## Alur Transaksi dan Stok

Ketika kasir melakukan transaksi:

```text
Kasir
  |
  v
Pilih Produk
  |
  v
Masukkan Qty
  |
  v
Cek Stok
  |
  +---- Stok Tidak Cukup
  |          |
  |          v
  |      Transaksi Ditolak
  |
  +---- Stok Cukup
             |
             v
      Simpan Transaksi
             |
             v
      Simpan Detail
             |
             v
        Kurangi Stok
             |
             v
        Transaksi Selesai
```

Contoh:

```text
Stok awal = 20
Qty terjual = 3

Stok akhir = 20 - 3
           = 17
```

Penambahan stok dapat dilakukan melalui halaman Produk.

Contoh:

```text
Stok awal = 17
Tambah stok = 10

Stok akhir = 17 + 10
           = 27
```

Pengurangan stok manual juga dapat dilakukan melalui halaman Produk.

---

## Cara Menggunakan Git

Project menggunakan branch:

```text
main
```

sebagai branch utama repository.

### Sebelum Mulai Bekerja

Selalu ambil perubahan terbaru dari repository:

```bash
git pull origin main
```

### Setelah Selesai Melakukan Perubahan

Gunakan urutan:

```bash
git add .
git commit -m "Deskripsi perubahan"
git push origin main
```

### Urutan yang Disarankan

Setiap kali mulai bekerja:

```bash
git pull origin main
```

Setelah selesai:

```bash
git add .
git commit -m "Deskripsi perubahan"
git push origin main
```

Contoh commit message:

```bash
git commit -m "Menambahkan fitur stok produk"
```

atau:

```bash
git commit -m "Memperbaiki validasi login"
```

atau:

```bash
git commit -m "Menambahkan halaman detail transaksi"
```

---

## Catatan untuk Tim

Agar tidak terjadi konflik kode:

1. Jangan langsung melakukan `push` tanpa melakukan `pull` terlebih dahulu.
2. Gunakan branch `main` sesuai aturan repository.
3. Gunakan commit message yang jelas dan menjelaskan perubahan.
4. Jangan menghapus atau mengubah kode anggota lain tanpa koordinasi.
5. Sebelum mengerjakan fitur baru, lakukan `git pull origin main`.
6. Setelah selesai mengerjakan fitur, lakukan pengecekan terlebih dahulu sebelum `commit`.
7. Jika terjadi conflict saat `git pull`, selesaikan conflict terlebih dahulu sebelum melakukan `push`.
8. Jangan memasukkan file konfigurasi pribadi atau password database ke repository jika tidak diperlukan.
9. Pastikan perubahan database juga disimpan pada folder `/database`.
10. Jika mengubah struktur database, komunikasikan kepada anggota tim lainnya agar database lokal mereka dapat diperbarui.

---

## Akun Default

Akun administrator bawaan:

```text
Username : admin
Password : admin123
Role     : admin
```

Gunakan akun tersebut untuk login pertama kali dan mengelola pengguna sistem.
