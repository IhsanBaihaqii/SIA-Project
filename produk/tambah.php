<?php
include '../config/koneksi.php';

$nama       = trim($_POST['nama'] ?? '');
$kategori   = trim($_POST['kategori'] ?? '');
$satuan     = trim($_POST['satuan'] ?? 'pcs');
$harga      = (int)($_POST['harga'] ?? 0);
$stok       = (int)($_POST['stok'] ?? 0);

// Generate kode_produk otomatis: P001, P002, ...
$last = $pdo->query("SELECT kode_produk FROM tbl_products ORDER BY kode_produk DESC LIMIT 1")->fetchColumn();
$num  = $last ? ((int)substr($last, 1)) + 1 : 1;
$kode = 'P' . str_pad($num, 3, '0', STR_PAD_LEFT);

$sql = "INSERT INTO tbl_products (kode_produk, nama, kategori, satuan, harga, stok)
        VALUES (:kode, :nama, :kategori, :satuan, :harga, :stok)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'kode'     => $kode,
    'nama'     => $nama,
    'kategori' => $kategori,
    'satuan'   => $satuan,
    'harga'    => $harga,
    'stok'     => $stok,
]);

header('Location: index.php?success=tambah');
exit;
