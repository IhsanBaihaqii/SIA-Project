<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id < 1) {
    header('Location: index.php?error=' . urlencode('ID transaksi tidak valid.'));
    exit;
}

try {
    $pdo->beginTransaction();

    // Hapus jurnal terkait (FK CASCADE dari tbl_transaction sebenarnya sudah handle,
    // tapi eksplisit lebih aman)

    // Hapus detail (FK CASCADE juga, tapi eksplisit)
    $pdo->prepare("DELETE FROM tbl_transaction_details WHERE id_transaction = :id")->execute([':id' => $id]);

    // Hapus header transaksi
    $stmt = $pdo->prepare("DELETE FROM tbl_transaction WHERE id_transaction = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Transaksi tidak ditemukan.');
    }

    $pdo->commit();

    header('Location: index.php?success=1');
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    header('Location: index.php?error=' . urlencode($e->getMessage()));
    exit;
}
