<?php

include '../config/koneksi.php';


$id = $_GET['id'] ?? null;


if (!$id) {

    header("Location: index.php");
    exit;

}


$query = "DELETE FROM tbl_products
          WHERE id_produk = :id";

$stmt = $pdo->prepare($query);

$stmt->execute([
    'id' => $id
]);


header("Location: index.php?success=Produk berhasil dihapus");

exit;