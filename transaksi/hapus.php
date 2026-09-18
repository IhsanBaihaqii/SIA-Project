<?php
include '../config/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if ($id < 1) {
    header("Location: index.php?error=" . urlencode('ID transaksi tidak valid.'));
    exit;
}

try {
    // Cek dulu transaksinya ada
    $stmt = $pdo->prepare("SELECT id_transaction FROM tbl_transaction WHERE id_transaction = :id");
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        header("Location: index.php?error=" . urlencode('Transaksi tidak ditemukan.'));
        exit;
    }

    // Kembalikan stok
    $stmt = $pdo->prepare("
        UPDATE tbl_products p
        JOIN tbl_transaction_details d ON d.id_product = p.id_product
        SET p.stok = p.stok + d.qty
        WHERE d.id_transaction = :id
    ");
    $stmt->execute([':id' => $id]);

    // Hapus header. FK ON DELETE CASCADE akan otomatis menghapus
    // tbl_transaction_details & tbl_journal yang terkait.
    $stmt = $pdo->prepare("DELETE FROM tbl_transaction WHERE id_transaction = :id");
    $stmt->execute([':id' => $id]);

    header("Location: index.php?success=1");
    exit;

} catch (Throwable $e) {
    header("Location: index.php?error=" . urlencode('Gagal menghapus: ' . $e->getMessage()));
    exit;
}