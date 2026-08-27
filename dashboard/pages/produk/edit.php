<?php

include '../config/koneksi.php';


// Mengambil ID
$id = $_GET['id'] ?? null;

if (!$id) {

    header("Location: index.php");
    exit;

}


// Mengambil data produk
$query = "SELECT * FROM tbl_products
          WHERE id_produk = :id";

$stmt = $pdo->prepare($query);

$stmt->execute([
    'id' => $id
]);

$product = $stmt->fetch();


if (!$product) {

    header("Location: index.php?error=Produk tidak ditemukan");
    exit;

}


// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $harga = $_POST['harga'];
    $kategori = trim($_POST['kategori']);


    if ($nama === '' || $harga === '' || $kategori === '') {

        $error = "Semua data wajib diisi";

    } else {

        $query = "UPDATE tbl_products SET
                    nama = :nama,
                    harga = :harga,
                    kategori = :kategori
                  WHERE id_produk = :id";

        $stmt = $pdo->prepare($query);

        $stmt->execute([
            'nama' => $nama,
            'harga' => $harga,
            'kategori' => $kategori,
            'id' => $id
        ]);


        header("Location: index.php?success=Produk berhasil diubah");
        exit;
    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Edit Produk</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-gray-100">


<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">
        Edit Produk
    </h1>


    <?php if (isset($error)): ?>

        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <form method="POST" class="space-y-4">


        <!-- ID -->

        <div>

            <label class="block mb-1 font-medium">
                ID Produk
            </label>

            <input
                type="text"
                value="<?= htmlspecialchars($product['id_produk']) ?>"
                disabled
                class="w-full bg-gray-100 border
                       rounded-lg px-4 py-2.5"
            >

        </div>


        <!-- Nama -->

        <div>

            <label class="block mb-1 font-medium">
                Nama Produk
            </label>

            <input
                type="text"
                name="nama"
                value="<?= htmlspecialchars($product['nama']) ?>"
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
                value="<?= htmlspecialchars($product['harga']) ?>"
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
                value="<?= htmlspecialchars($product['kategori']) ?>"
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

                Update

            </button>

        </div>

    </form>

</div>

</body>

</html>