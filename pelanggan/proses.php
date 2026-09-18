<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$action = $_POST['action'] ?? '';
$nama = trim($_POST['nama'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

// Validasi
$errors = [];
if (empty($nama)) {
    $errors[] = "Nama pelanggan wajib diisi";
}

if (!empty($errors)) {
    $pesan = implode(', ', $errors);
    if ($action === 'edit') {
        $id = $_POST['id_pelanggan'] ?? 0;
        header("Location: edit.php?id=$id&error=" . urlencode($pesan));
    } else {
        header("Location: tambah.php?error=" . urlencode($pesan));
    }
    exit;
}

if ($action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO tbl_pelanggan (nama, no_hp, alamat) VALUES (:nama, :no_hp, :alamat)");
    $stmt->execute([
        ':nama' => $nama,
        ':no_hp' => $no_hp,
        ':alamat' => $alamat
    ]);
    header("Location: index.php?success=tambah");
    exit;
} elseif ($action === 'edit') {
    $id = $_POST['id_pelanggan'] ?? 0;
    if ($id <= 0) {
        header("Location: index.php?error=" . urlencode("ID pelanggan tidak valid"));
        exit;
    }

    $stmt = $pdo->prepare("UPDATE tbl_pelanggan SET nama = :nama, no_hp = :no_hp, alamat = :alamat WHERE id_pelanggan = :id");
    $stmt->execute([
        ':nama' => $nama,
        ':no_hp' => $no_hp,
        ':alamat' => $alamat,
        ':id' => $id
    ]);
    header("Location: index.php?success=edit");
    exit;
} else {
    header("Location: index.php?error=" . urlencode("Aksi tidak valid"));
    exit;
}