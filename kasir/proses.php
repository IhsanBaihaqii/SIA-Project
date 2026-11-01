<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id_pelanggan = !empty($_POST['id_pelanggan']) ? (int)$_POST['id_pelanggan'] : null;
$bayar        = (int)($_POST['bayar'] ?? 0);
$cartJson     = $_POST['cart'] ?? '[]';
$cart         = json_decode($cartJson, true);

//  VALIDASI DASAR 
if (empty($cart) || !is_array($cart)) {
    header("Location: index.php?error=" . urlencode('Keranjang kosong.'));
    exit;
}

try {
    $pdo->beginTransaction();

    $totalTransaksi = 0;
    $totalHpp       = 0;
    $itemsFinal     = [];

    //  1. VALIDASI & LOCK PRODUK 
    foreach ($cart as $item) {
        // ✓ FIX: pakai kode_produk (string), BUKAN id_product (int)
        $kode_produk = trim($item['kode_produk'] ?? '');
        $qty         = (int)($item['qty'] ?? 0);

        if ($kode_produk === '' || $qty < 1) {
            throw new Exception('Data keranjang tidak valid.');
        }

        // ✓ FIX: kolom kode_produk
        $stmt = $pdo->prepare("SELECT * FROM tbl_products WHERE kode_produk = :kode FOR UPDATE");
        $stmt->execute([':kode' => $kode_produk]);
        $produk = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produk) {
            throw new Exception("Produk '$kode_produk' tidak ditemukan.");
        }
        if ($qty > $produk['stok']) {
            throw new Exception("Stok {$produk['nama']} tidak mencukupi (tersisa {$produk['stok']}).");
        }

        // ✓ FIX: harga dari DB (bukan dari client) → aman dari manipulasi
        $harga       = (int)$produk['harga'];
        $harga_pokok = (int)$produk['harga_pokok'];
        $subtotal    = $harga * $qty;
        $hpp         = $harga_pokok * $qty;

        $totalTransaksi += $subtotal;
        $totalHpp       += $hpp;

        $itemsFinal[] = [
            'kode_produk' => $kode_produk,
            'harga'       => $harga,
            'qty'         => $qty,
            'subtotal'    => $subtotal,
        ];
    }

    //  2. CEK PEMBAYARAN 
    if ($bayar < $totalTransaksi) {
        throw new Exception('Uang dibayar kurang dari total transaksi.');
    }

    //  3. INSERT HEADER TRANSAKSI 
    $stmt = $pdo->prepare("INSERT INTO tbl_transaction (id_pelanggan, tanggal, total)
                           VALUES (:id_pelanggan, CURDATE(), :total)");
    $stmt->execute([
        ':id_pelanggan' => $id_pelanggan,
        ':total'        => $totalTransaksi,
    ]);
    $id_transaction = (int)$pdo->lastInsertId();

    //  4. INSERT DETAIL & UPDATE STOK 
    // ✓ FIX: kolom kode_produk (bukan id_product)
    $stmtDetail = $pdo->prepare("INSERT INTO tbl_transaction_details
                                 (kode_produk, id_transaction, harga, qty, subtotal)
                                 VALUES (:kode_produk, :id_transaction, :harga, :qty, :subtotal)");
    $stmtStok   = $pdo->prepare("UPDATE tbl_products
                                 SET stok = stok - :qty
                                 WHERE kode_produk = :kode");

    foreach ($itemsFinal as $it) {
        $stmtDetail->execute([
            ':kode_produk'    => $it['kode_produk'],
            ':id_transaction' => $id_transaction,
            ':harga'          => $it['harga'],
            ':qty'            => $it['qty'],
            ':subtotal'       => $it['subtotal'],
        ]);

        $stmtStok->execute([
            ':qty'  => $it['qty'],
            ':kode' => $it['kode_produk'],
        ]);
    }

    $no_bukti   = 'TRX-' . str_pad($id_transaction, 5, '0', STR_PAD_LEFT);
    $keterangan = 'Penjualan kasir #' . $id_transaction;

    $pdo->commit();

    header("Location: index.php?success=1");
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header("Location: index.php?error=" . urlencode($e->getMessage()));
    exit;
}
