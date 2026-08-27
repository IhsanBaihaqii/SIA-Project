<?php

include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $harga = $_POST['harga'];
    $kategori = trim($_POST['kategori']);


    // Validasi
    if ($nama === '' || $harga === '' || $kategori === '') {

        header("Location: index.php?error=Semua data wajib diisi");
        exit;

    }


    // INSERT
    $query = "INSERT INTO tbl_products
              (nama, harga, kategori)
              VALUES
              (:nama, :harga, :kategori)";

    $stmt = $pdo->prepare($query);

    $stmt->execute([
        'nama' => $nama,
        'harga' => $harga,
        'kategori' => $kategori
    ]);


    header("Location: index.php?success=Produk berhasil ditambahkan");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Produk</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-gray-100">


<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">
        Tambah Produk
    </h1>


    <form method="POST" class="space-y-4">


        <!-- Nama -->

        <div>

            <label class="block mb-1 font-medium">
                Nama Produk
            </label>

            <input
                type="text"
                name="nama"
                required
                class="w-full border rounded-lg px-4 py-2.5"
            >

        </div>


        <!-- Harga -->

        <div>

            <label class="block mb-1 font-medium">
                Harga
            </label>

            <input
                type="number"
                name="harga"
                min="0"
                required
                class="w-full border rounded-lg px-4 py-2.5"
            >

        </div>


        <!-- Kategori -->

        <div>

            <label class="block mb-1 font-medium">
                Kategori
            </label>

            <input
                type="text"
                name="kategori"
                required
                class="w-full border rounded-lg px-4 py-2.5"
            >

        </div>


        <div class="flex gap-3 pt-3">

            <a href="index.php"
               class="flex-1 text-center border
                      border-gray-300 py-2.5 rounded-lg">

                Batal

            </a>


            <button
                type="submit"
                class="flex-1 bg-purple-700 text-white
                       py-2.5 rounded-lg hover:bg-purple-800">

                Simpan

            </button>

        </div>

    </form>

</div>

</body>

</html>