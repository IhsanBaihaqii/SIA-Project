<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id = $_POST['id_product'] ?? 0;
$nama = $_POST['nama'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$harga = $_POST['harga'] ?? 0;
$stok = $_POST['stok'] ?? 0;

// Validasi
$errors = [];
if ($id <= 0) $errors[] = "ID produk tidak valid";
if (empty($nama)) $errors[] = "Nama produk wajib diisi";
if (empty($kategori)) $errors[] = "Kategori produk wajib diisi";
if ($harga <= 0) $errors[] = "Harga harus lebih dari 0";
if ($stok < 0) $errors[] = "Stok tidak boleh kurang dari 0";

if (!empty($errors)) {
    $pesan = implode(', ', $errors);
    header("Location: index.php?error=" . urlencode($pesan));
    exit;
}

// Update
$query = "UPDATE tbl_products SET nama = :nama, kategori = :kategori, harga = :harga, stok = :stok WHERE id_product = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':nama' => $nama,
    ':kategori' => $kategori,
    ':harga' => $harga,
    ':stok' => $stok,
    ':id' => $id
]);

header("Location: index.php?success=edit");
exit;