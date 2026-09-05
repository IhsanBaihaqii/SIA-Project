<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if (empty($nama)) {
        die("Nama produk wajib diisi");
    }
    if (empty($kategori)) {
        die("Kategori produk wajib diisi");
    }
    if ($harga <= 0) {
        die("Harga harus lebih dari 0");
    }
    if ($stok < 0) {
        die("Stok tidak boleh kurang dari 0");
    }

    $query = "INSERT INTO tbl_products (nama, kategori, harga, stok)
              VALUES (:nama, :kategori, :harga, :stok)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nama' => $nama,
        ':kategori' => $kategori,
        ':harga' => $harga,
        ':stok' => $stok
    ]);

    echo "Produk berhasil ditambahkan";
}
?>