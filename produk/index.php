<?php
include '../config/koneksi.php';

$query = "SELECT * FROM tbl_products";
$stmt = $pdo->prepare($query);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>