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

$query = "DELETE FROM tbl_products
          WHERE id_product = :id";

$stmt = $pdo->prepare($query);

$stmt->execute([
    ':id' => $id
]);

header("Location: index.php");
exit;
?>