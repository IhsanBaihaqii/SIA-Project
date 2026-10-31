<?php
include '../config/koneksi.php';

// ============ VALIDASI METHOD ============
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// ============ AMBIL & VALIDASI INPUT ============
$kode     = trim($_POST['kode_produk'] ?? '');
$nama     = trim($_POST['nama'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$satuan   = trim($_POST['satuan'] ?? 'pcs');
$harga    = (int)($_POST['harga'] ?? 0);
$stok     = (int)($_POST['stok'] ?? 0);

// Validasi dasar
if ($kode === '' || $nama === '' || $kategori === '') {
    header('Location: index.php?error=' . urlencode('Data produk tidak lengkap.'));
    exit;
}
if ($harga < 1) {
    header('Location: index.php?error=' . urlencode('Harga jual minimal Rp 1.'));
    exit;
}
if ($stok < 0) {
    header('Location: index.php?error=' . urlencode('Stok tidak boleh negatif.'));
    exit;
}

try {
    // ============ CEK PRODUK ADA ============
    $cek = $pdo->prepare("SELECT kode_produk FROM tbl_products WHERE kode_produk = :kode LIMIT 1");
    $cek->execute([':kode' => $kode]);

    if (!$cek->fetchColumn()) {
        header('Location: index.php?error=' . urlencode('Produk tidak ditemukan.'));
        exit;
    }

    // ============ UPDATE (TANPA harga_pokok) ============
    $sql = "UPDATE tbl_products 
            SET nama     = :nama,
                kategori = :kategori,
                satuan   = :satuan,
                harga    = :harga,
                stok     = :stok
            WHERE kode_produk = :kode";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nama'     => $nama,
        ':kategori' => $kategori,
        ':satuan'   => $satuan,
        ':harga'    => $harga,
        ':stok'     => $stok,
        ':kode'     => $kode,
    ]);

    header('Location: index.php?success=edit');
    exit;
} catch (Throwable $e) {
    header('Location: index.php?error=' . urlencode('Gagal update: ' . $e->getMessage()));
    exit;
}
