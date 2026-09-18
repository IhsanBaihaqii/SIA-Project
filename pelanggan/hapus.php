<?php

require_once '../config/database.php';
require_once '../config/auth.php';

$id = $_GET['id'] ?? 0;

$query = "DELETE FROM tbl_pelanggan WHERE id_pelanggan = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

header("Location: index.php");
exit;