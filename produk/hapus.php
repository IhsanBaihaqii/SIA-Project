<?php
include '../config/koneksi.php';

$kode = $_GET['kode_produk'] ?? '';

try {
    $stmt = $pdo->prepare("DELETE FROM tbl_products WHERE kode_produk = :kode");
    $stmt->execute(['kode' => $kode]);
    header('Location: index.php?success=hapus');
} catch (PDOException $e) {
    // Gagal hapus karena ada FK (produk sudah dipakai di transaksi)
    header('Location: index.php?error=' . urlencode('Produk tidak bisa dihapus karena sudah dipakai di transaksi.'));
}
exit;
