## Struktur Tabel

### 📦 tbl_products

| Kolom      | Tipe Data | Keterangan      |
| ---------- | --------- | --------------- |
| id_product | INT (PK)  | Primary Key     |
| nama       | VARCHAR   | Nama produk     |
| kategori   | VARCHAR   | Kategori produk |
| harga      | INT       | Harga produk    |

---

### 🧾 tbl_transaction

| Kolom          | Tipe Data | Keterangan        |
| -------------- | --------- | ----------------- |
| id_transaction | INT (PK)  | Primary Key       |
| tgl            | DATE      | Tanggal transaksi |
| total          | INT       | Total transaksi   |

---

### 📑 tbl_transaction_details

| Kolom                 | Tipe Data | Keterangan                |
| --------------------- | --------- | ------------------------- |
| id_transaction_detail | INT (PK)  | Primary Key               |
| id_product            | INT (FK)  | Relasi ke tbl_products    |
| id_transaction        | INT (FK)  | Relasi ke tbl_transaction |
| harga                 | INT       | Harga satuan produk       |
| qty                   | INT       | Jumlah produk             |
| subtotal              | INT       | qty × harga               |
