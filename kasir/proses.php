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

// ============ VALIDASI ============
if (empty($cart) || !is_array($cart)) {
    header("Location: index.php?error=" . urlencode('Keranjang kosong.'));
    exit;
}

try {
    $pdo->beginTransaction();

    $totalTransaksi = 0;
    $totalHpp       = 0;
    $itemsFinal     = [];

    // ============ 1. KUNCI & VALIDASI SETIAP PRODUK ============
    foreach ($cart as $item) {
        $id_product = (int)($item['id_product'] ?? 0);
        $qty        = (int)($item['qty'] ?? 0);

        if ($id_product < 1 || $qty < 1) {
            throw new Exception('Data keranjang tidak valid.');
        }

        // Lock row produk
        $stmt = $pdo->prepare("SELECT * FROM tbl_products WHERE id_product = :id FOR UPDATE");
        $stmt->execute([':id' => $id_product]);
        $produk = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produk) {
            throw new Exception("Produk ID $id_product tidak ditemukan.");
        }
        if ($qty > $produk['stok']) {
            throw new Exception("Stok {$produk['nama']} tidak mencukupi (tersisa {$produk['stok']}).");
        }

        $harga       = (int)$produk['harga'];
        $harga_pokok = (int)($produk['harga_pokok'] ?? 0);
        $subtotal    = $harga * $qty;
        $hpp         = $harga_pokok * $qty;

        $totalTransaksi += $subtotal;
        $totalHpp       += $hpp;

        $itemsFinal[] = [
            'id_product' => $id_product,
            'harga'      => $harga,
            'qty'        => $qty,
            'subtotal'   => $subtotal,
        ];
    }

    // ============ 2. CEK PEMBAYARAN ============
    if ($bayar < $totalTransaksi) {
        throw new Exception('Uang dibayar kurang dari total transaksi.');
    }

    // ============ 3. INSERT HEADER TRANSAKSI ============
    $stmt = $pdo->prepare("INSERT INTO tbl_transaction (id_pelanggan, tanggal, total)
                           VALUES (:id_pelanggan, CURDATE(), :total)");
    $stmt->execute([
        ':id_pelanggan' => $id_pelanggan,
        ':total'        => $totalTransaksi,
    ]);
    $id_transaction = (int)$pdo->lastInsertId();

    // ============ 4. INSERT DETAIL & UPDATE STOK ============
    $stmtDetail = $pdo->prepare("INSERT INTO tbl_transaction_details
                                 (id_product, id_transaction, harga, qty, subtotal)
                                 VALUES (:id_product, :id_transaction, :harga, :qty, :subtotal)");
    $stmtStok   = $pdo->prepare("UPDATE tbl_products
                                 SET stok = stok - :qty
                                 WHERE id_product = :id");

    foreach ($itemsFinal as $it) {
        $stmtDetail->execute([
            ':id_product'     => $it['id_product'],
            ':id_transaction' => $id_transaction,
            ':harga'          => $it['harga'],
            ':qty'            => $it['qty'],
            ':subtotal'       => $it['subtotal'],
        ]);

        $stmtStok->execute([
            ':qty' => $it['qty'],
            ':id'  => $it['id_product'],
        ]);
    }

    // ============ 5. AUTO JURNAL ============
    // Kas                    (D)  totalTransaksi
    // Penjualan              (K)  totalTransaksi
    // Harga Pokok Penjualan  (D)  totalHpp
    // Persediaan             (K)  totalHpp

    $cariAkun = $pdo->prepare("SELECT id_akun FROM tbl_akun WHERE nama_akun = :n LIMIT 1");
    $getIdAkun = function ($nama) use ($cariAkun) {
        $cariAkun->execute([':n' => $nama]);
        $id = $cariAkun->fetchColumn();
        if (!$id) {
            throw new Exception("Akun '$nama' belum ada di tbl_akun.");
        }
        return (int)$id;
    };

    $id_kas        = $getIdAkun('Kas');
    $id_penjualan  = $getIdAkun('Pendapatan Penjualan');

    $no_bukti   = 'TRX-' . str_pad($id_transaction, 5, '0', STR_PAD_LEFT);
    $keterangan = 'Penjualan kasir #' . $id_transaction;

    $stmtJurnal = $pdo->prepare(
        "INSERT INTO tbl_journal
            (no_bukti, tanggal, keterangan, id_akun, debit, kredit)
         VALUES
            (:no_bukti, NOW(), :keterangan, :id_akun, :debit, :kredit)"
    );

    $jurnal = [
        [$id_kas,        $totalTransaksi, 0],
        [$id_penjualan,  0,               $totalTransaksi],
    ];

    foreach ($jurnal as $j) {
        $stmtJurnal->execute([
            ':no_bukti'   => $no_bukti,
            ':keterangan' => $keterangan,
            ':id_akun'    => $j[0],
            ':debit'      => $j[1],
            ':kredit'     => $j[2],
        ]);
    }

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
