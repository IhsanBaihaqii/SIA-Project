<?php
include '../config/koneksi.php';

if (!isset($_GET['id_product'])) {
    header("Location: index.php?error=" . urlencode("ID produk tidak ditemukan"));
    exit;
}

$id = $_GET['id_product'];

// Cek apakah produk ada
$query = "SELECT * FROM tbl_products WHERE id_product = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php?error=" . urlencode("Produk tidak ditemukan"));
    exit;
}

// Hapus
$query = "DELETE FROM tbl_products WHERE id_product = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([':id' => $id]);

header("Location: index.php?success=hapus");
exit;