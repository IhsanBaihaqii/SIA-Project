<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$nama = $_POST['nama'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$harga = $_POST['harga'] ?? 0;
$stok = $_POST['stok'] ?? 0;

// Validasi
$errors = [];
if (empty($nama)) $errors[] = "Nama produk wajib diisi";
if (empty($kategori)) $errors[] = "Kategori produk wajib diisi";
if ($harga <= 0) $errors[] = "Harga harus lebih dari 0";
if ($stok < 0) $errors[] = "Stok tidak boleh kurang dari 0";

if (!empty($errors)) {
    $pesan = implode(', ', $errors);
    header("Location: index.php?error=" . urlencode($pesan));
    exit;
}

// Simpan
$query = "INSERT INTO tbl_products (nama, kategori, harga, stok) VALUES (:nama, :kategori, :harga, :stok)";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':nama' => $nama,
    ':kategori' => $kategori,
    ':harga' => $harga,
    ':stok' => $stok
]);

header("Location: index.php?success=tambah");
exit;