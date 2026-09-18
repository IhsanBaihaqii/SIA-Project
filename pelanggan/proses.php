<?php

require_once '../config/database.php';
require_once '../config/auth.php';

$aksi = $_POST['aksi'] ?? '';

if ($aksi == 'tambah') {

    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $nomor_hp = trim($_POST['nomor_hp']);

    if ($nama == '' || $alamat == '' || $nomor_hp == '') {
        die('Semua data pelanggan wajib diisi.');
    }

    $query = "INSERT INTO tbl_pelanggan
              (nama, alamat, nomor_hp)
              VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $nama,
        $alamat,
        $nomor_hp
    );

    mysqli_stmt_execute($stmt);

    header("Location: index.php");
    exit;
}


if ($aksi == 'edit') {

    $id = $_POST['id_pelanggan'];
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $nomor_hp = trim($_POST['nomor_hp']);

    if ($nama == '' || $alamat == '' || $nomor_hp == '') {
        die('Semua data pelanggan wajib diisi.');
    }

    $query = "UPDATE tbl_pelanggan
              SET nama = ?, alamat = ?, nomor_hp = ?
              WHERE id_pelanggan = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $nama,
        $alamat,
        $nomor_hp,
        $id
    );

    mysqli_stmt_execute($stmt);

    header("Location: index.php");
    exit;
}


header("Location: index.php");
exit;