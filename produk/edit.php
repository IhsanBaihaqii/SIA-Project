<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php"); exit;
}

$id          = (int)($_POST['id_product'] ?? 0);
$nama        = $_POST['nama'] ?? '';
$kategori    = $_POST['kategori'] ?? '';
$harga       = (int)($_POST['harga'] ?? 0);
$harga_pokok = (int)($_POST['harga_pokok'] ?? 0);
$stok        = (int)($_POST['stok'] ?? 0);

$errors = [];
if ($id <= 0) $errors[] = "ID produk tidak valid";
if (empty($nama)) $errors[] = "Nama produk wajib diisi";
if (empty($kategori)) $errors[] = "Kategori produk wajib diisi";
if ($harga <= 0) $errors[] = "Harga jual harus lebih dari 0";
if ($harga_pokok < 0) $errors[] = "Harga pokok tidak boleh negatif";
if ($stok < 0) $errors[] = "Stok tidak boleh kurang dari 0";

if (!empty($errors)) {
    header("Location: index.php?error=" . urlencode(implode(', ', $errors)));
    exit;
}

$query = "UPDATE tbl_products
          SET nama = :nama, kategori = :kategori,
              harga = :harga, harga_pokok = :harga_pokok, stok = :stok
          WHERE id_product = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':nama'        => $nama,
    ':kategori'    => $kategori,
    ':harga'       => $harga,
    ':harga_pokok' => $harga_pokok,
    ':stok'        => $stok,
    ':id'          => $id
]);

header("Location: index.php?success=edit");
exit;
?>