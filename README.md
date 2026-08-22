# SIA Project

Sistem Informasi Akuntansi berbasis web untuk mengelola data produk dan transaksi.

## 📋 Daftar Isi

- [Cara Instalasi](#-cara-instalasi)
- [Cara Menggunakan Git](#-cara-menggunakan-git)
- [Struktur Database](#-struktur-database)
  - [tbl_products](#tbl_products)
  - [tbl_transaction](#tbl_transaction)
  - [tbl_transaction_details](#tbl_transaction_details)

---

## 🚀 Cara Instalasi

### 1. Buka Folder XAMPP

Pastikan folder project berada di:

```text
C:\xampp\htdocs
```

### 2. Clone Repository

Buka **Command Prompt / Git Bash**, kemudian masuk ke folder `htdocs`:

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

Pastikan service berikut sudah aktif:

- Apache
- MySQL

---

## 🔄 Cara Menggunakan Git

Project menggunakan branch **`main`** sebagai branch utama.

### Sebelum Mulai Bekerja

Selalu ambil perubahan terbaru dari repository terlebih dahulu:

```bash
git pull origin main
```

Kemudian push ke branch `main`:

```bash
git push origin main
```

### ⚠️ Urutan yang Disarankan

Gunakan urutan berikut setiap kali ingin mengirim perubahan:

```bash
git pull origin main
git add .
git commit -m "Deskripsi perubahan"
git push origin main
```

### Jalankan WEb

[http://localhost/SIA-Project/](http://localhost/SIA-Project/)

> **Catatan:** Selalu lakukan `git pull origin main` sebelum mulai bekerja agar kode lokal tetap mengikuti versi terbaru di repository.

---

## Import Database

buat nama database `db_sia` pada `cmd` atau [PHP MyAdmin](http://localhost/phpmyadmin/)

Lalu salin atau import `/database`

### Pada PHP MyAdmin

- buat database `db_sia`
- Pilih `SQL`
- Tempel Database dalam format `text`
- Klik Go atau Kirim
- Selesai

---

## 🗄️ Struktur Database

Database menggunakan beberapa tabel utama untuk menyimpan data produk dan transaksi.

### 📦 `tbl_users`

Menyimpan data user

| Kolom      | Tipe Data | Keterangan      |
| ---------- | --------- | --------------- |
| `id`       | INT (PK)  | Primary Key     |
| `username` | VARCHAR   | Nama User       |
| `password` | VARCHAR   | Kategori produk |
| `role`     | VARCHAR   | Admin, User     |

---

### 📦 `tbl_products`

Menyimpan data produk.

| Kolom        | Tipe Data | Keterangan      |
| ------------ | --------- | --------------- |
| `id_product` | INT (PK)  | Primary Key     |
| `nama`       | VARCHAR   | Nama produk     |
| `kategori`   | VARCHAR   | Kategori produk |
| `harga`      | INT       | Harga produk    |

---

### 🧾 `tbl_transaction`

Menyimpan data utama setiap transaksi.

| Kolom            | Tipe Data | Keterangan            |
| ---------------- | --------- | --------------------- |
| `id_transaction` | INT (PK)  | Primary Key           |
| `tgl`            | DATE      | Tanggal transaksi     |
| `total`          | INT       | Total nilai transaksi |

---

### 📑 `tbl_transaction_details`

Menyimpan detail produk yang terdapat dalam setiap transaksi.

| Kolom                   | Tipe Data | Keterangan                         |
| ----------------------- | --------- | ---------------------------------- |
| `id_transaction_detail` | INT (PK)  | Primary Key                        |
| `id_product`            | INT (FK)  | Relasi ke `tbl_products`           |
| `id_transaction`        | INT (FK)  | Relasi ke `tbl_transaction`        |
| `harga`                 | INT       | Harga satuan produk saat transaksi |
| `qty`                   | INT       | Jumlah produk                      |
| `subtotal`              | INT       | Jumlah × harga                     |

---

## 🔗 Relasi Antar Tabel

Struktur relasi database:

```text
tbl_products
     │
     │ id_product
     ▼
tbl_transaction_details
     ▲
     │ id_transaction
     │
tbl_transaction
```

### Penjelasan

- Satu produk dapat muncul di banyak detail transaksi.
- Satu transaksi dapat memiliki banyak detail produk.
- `tbl_transaction_details` menjadi penghubung antara produk dan transaksi.
- Nilai `subtotal` dihitung berdasarkan:

```text
subtotal = harga × qty
```

Sedangkan total transaksi merupakan jumlah seluruh subtotal dalam transaksi tersebut.

---

## 👥 Catatan untuk Tim

Agar tidak terjadi konflik kode:

1. **Jangan langsung push tanpa melakukan pull terlebih dahulu.**
2. Gunakan branch `main` sesuai aturan repository.
3. Gunakan commit message yang jelas.
4. Jangan menghapus atau mengubah kode anggota lain tanpa koordinasi.
5. Jika terjadi conflict saat `git pull`, selesaikan conflict terlebih dahulu sebelum melakukan `push`.

Username: `admin`

Password: `admin123`
