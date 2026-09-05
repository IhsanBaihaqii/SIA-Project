<?php
include '../config/koneksi.php';

if (!isset($_GET['id_product'])) {
    die("ID produk tidak ditemukan");
}

$id = $_GET['id_product'];

$query = "SELECT * FROM tbl_products
          WHERE id_product = :id";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':id' => $id
]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produk tidak ditemukan");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_product'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if (empty($nama)) {
        die("Nama produk wajib diisi");
    }
    if (empty($kategori)) {
        die("Kategori produk wajib diisi");
    }
    if ($harga <= 0) {
        die("Harga harus lebih dari 0");
    }
    if ($stok < 0) {
        die("Stok tidak boleh kurang dari 0");
    }

    $query = "UPDATE tbl_products
              SET nama = :nama,
                  kategori = :kategori,
                  harga = :harga,
                  stok = :stok
              WHERE id_product = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nama' => $nama,
        ':kategori' => $kategori,
        ':harga' => $harga,
        ':stok' => $stok,
        ':id' => $id
    ]);

    header("Location: index.php");
    exit;
}
?>
