<?php
$host = "localhost";
$db_name = "db_sia";
$username = "root";
$password = "";

try {
  //coba koneksi database
  $pdo = new PDO(
    "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
    $username,
    $password
  );

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $error) {
  die("Koneksi database gagal: " . $error->getMessage());
}

