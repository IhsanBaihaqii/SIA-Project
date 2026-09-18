<?php
include '../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: index.php?error=" . urlencode("ID pelanggan tidak valid"));
    exit;
}

// Cek apakah data ada
$stmt = $pdo->prepare("SELECT * FROM tbl_pelanggan WHERE id_pelanggan = :id");
$stmt->execute([':id' => $id]);
$pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelanggan) {
    header("Location: index.php?error=" . urlencode("Pelanggan tidak ditemukan"));
    exit;
}

// Hapus data
$stmt = $pdo->prepare("DELETE FROM tbl_pelanggan WHERE id_pelanggan = :id");
$stmt->execute([':id' => $id]);

header("Location: index.php?success=hapus");
exit;