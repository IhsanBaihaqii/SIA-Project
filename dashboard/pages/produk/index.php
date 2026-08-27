<?php
include '../config/koneksi.php';

// Search
$keyword = $_GET['search'] ?? '';

$query = "SELECT * FROM tbl_products
          WHERE nama LIKE :keyword
          OR kategori LIKE :keyword
          ORDER BY id_produk DESC";

$stmt = $pdo->prepare($query);

$stmt->execute([
    'keyword' => "%$keyword%"
]);

$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Produk</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-white">

<main class="p-4 md:p-8">

    <!-- Notifikasi -->
    <?php if (isset($_GET['success'])): ?>
        <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>


    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6">

        <!-- Search -->
        <form method="GET" class="relative flex-1 max-w-md">

            <i class="fa-solid fa-magnifying-glass
               absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

            <input
                type="text"
                name="search"
                value="<?= htmlspecialchars($keyword) ?>"
                placeholder="Cari produk....."
                class="w-full bg-gray-100 border border-gray-200 rounded-xl
                       pl-11 pr-4 py-3.5 text-gray-700
                       placeholder-gray-400 focus:outline-none
                       focus:ring-2 focus:ring-purple-400
                       focus:bg-white transition"
            >

        </form>


        <!-- Tambah -->
        <a href="tambah.php"
           class="bg-purple-700 hover:bg-purple-800
                  text-white font-semibold px-6 py-3.5
                  rounded-xl flex items-center justify-center gap-2
                  transition whitespace-nowrap">

            <i class="fa-solid fa-plus"></i>

            Tambah Produk

        </a>

    </div>


    <!-- Table -->
    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-x-auto">

        <table class="w-full min-w-[720px] text-left">

            <thead>

                <tr class="text-gray-700 font-semibold border-b border-gray-200">

                    <th class="px-6 py-4">
                        ID Produk
                    </th>

                    <th class="px-6 py-4">
                        Nama Produk
                    </th>

                    <th class="px-6 py-4">
                        Harga
                    </th>

                    <th class="px-6 py-4">
                        Kategori
                    </th>

                    <th class="px-6 py-4">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-200">

                <?php if (count($products) > 0): ?>

                    <?php foreach ($products as $product): ?>

                        <tr class="hover:bg-gray-100">

                            <!-- ID -->
                            <td class="px-6 py-5 font-semibold">

                                <?= htmlspecialchars($product['id_produk']) ?>

                            </td>


                            <!-- Nama -->
                            <td class="px-6 py-5">

                                <?= htmlspecialchars($product['nama']) ?>

                            </td>


                            <!-- Harga -->
                            <td class="px-6 py-5">

                                Rp <?= number_format(
                                    $product['harga'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </td>


                            <!-- Kategori -->
                            <td class="px-6 py-5">

                                <?= htmlspecialchars($product['kategori']) ?>

                            </td>


                            <!-- Aksi -->
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-4">

                                    <!-- Edit -->
                                    <a href="edit.php?id=<?= $product['id_produk'] ?>"
                                       class="text-blue-500 hover:text-blue-700">

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    <!-- Hapus -->
                                    <a href="hapus.php?id=<?= $product['id_produk'] ?>"
                                       onclick="return confirm('Apakah kamu yakin ingin menghapus produk ini?')"
                                       class="text-red-500 hover:text-red-700">

                                        <i class="fa-solid fa-trash"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="5"
                            class="text-center text-gray-400 py-14">

                            <i class="fa-solid fa-box-open text-3xl mb-2"></i>

                            <p>Produk tidak ditemukan</p>

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

</body>
</html>