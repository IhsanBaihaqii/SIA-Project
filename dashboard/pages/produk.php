<?php

include '../config/koneksi.php';

function redirectProduk($message = '', $type = 'success')
{
    $url = 'produk';

    if ($message !== '') {
        $url .= '?message=' . urlencode($message);
        $url .= '&type=' . urlencode($type);
    }

    header("Location: $url");
    exit;
}

// PROSES CRUD
$action = $_GET['action'] ?? '';

// TAMBAH PRODUK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'tambah') {

    $nama     = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $harga    = $_POST['harga'] ?? '';

    // Validasi
    if ($nama === '' || $kategori === '' || $harga === '') {
        redirectProduk('Semua data produk wajib diisi.', 'error');
    }

    if (!is_numeric($harga) || $harga < 0) {
        redirectProduk('Harga produk tidak valid.', 'error');
    }

    $query = "INSERT INTO tbl_products
              (nama, kategori, harga)
              VALUES
              (:nama, :kategori, :harga)";

    $stmt = $pdo->prepare($query);

    $stmt->execute([
        'nama'     => $nama,
        'kategori' => $kategori,
        'harga'    => $harga
    ]);

    redirectProduk('Produk berhasil ditambahkan.');
}

// UPDATE PRODUK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {

    $id       = $_POST['id_product'] ?? '';
    $nama     = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $harga    = $_POST['harga'] ?? '';

    // Validasi
    if ($id === '' || $nama === '' || $kategori === '' || $harga === '') {
        redirectProduk('Semua data produk wajib diisi.', 'error');
    }

    if (!is_numeric($harga) || $harga < 0) {
        redirectProduk('Harga produk tidak valid.', 'error');
    }

    $query = "UPDATE tbl_products
              SET nama = :nama,
                  kategori = :kategori,
                  harga = :harga
              WHERE id_product = :id";

    $stmt = $pdo->prepare($query);

    $stmt->execute([
        'nama'     => $nama,
        'kategori' => $kategori,
        'harga'    => $harga,
        'id'       => $id
    ]);

    redirectProduk('Produk berhasil diperbarui.');
}

// HAPUS PRODUK
if ($action === 'hapus') {

    $id = $_GET['id'] ?? '';

    if ($id === '') {
        redirectProduk('ID produk tidak ditemukan.', 'error');
    }

    $query = "DELETE FROM tbl_products
              WHERE id_product = :id";

    $stmt = $pdo->prepare($query);

    $stmt->execute([
        'id' => $id
    ]);

    redirectProduk('Produk berhasil dihapus.');
}

// SEARCH
$keyword = trim($_GET['search'] ?? '');

$query = "SELECT *
          FROM tbl_products";

$params = [];

if ($keyword !== '') {
    $query .= " WHERE nama LIKE :keyword
                OR kategori LIKE :keyword";

    $params['keyword'] = "%$keyword%";
}

$query .= " ORDER BY id_product DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// EDIT DATA
$editProduct = null;

if ($action === 'edit' && isset($_GET['id'])) {

    $id = $_GET['id'];

    $query = "SELECT *
              FROM tbl_products
              WHERE id_product = :id
              LIMIT 1";

    $stmt = $pdo->prepare($query);

    $stmt->execute([
        'id' => $id
    ]);

    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}

// PESAN
$message = $_GET['message'] ?? '';
$type    = $_GET['type'] ?? 'success';

?>

     <!-- HEADER -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Produk
        </h1>
        <p class="text-gray-500 mt-1">
            Kelola data produk yang tersedia di sistem kasir.
        </p>
    </div>

    <a href="produk?action=tambah"
       class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition">

        <i class="fas fa-plus"></i>

        Tambah Produk

    </a>

</div>


     <!-- ALERT -->
<?php if ($message !== ''): ?>

    <div class="mb-6 px-4 py-3 rounded-lg
        <?= $type === 'error'
            ? 'bg-red-100 text-red-700 border border-red-200'
            : 'bg-green-100 text-green-700 border border-green-200'
        ?>">

        <div class="flex items-center gap-2">

            <i class="fas <?= $type === 'error'
                ? 'fa-circle-exclamation'
                : 'fa-circle-check'
            ?>"></i>

            <span>
                <?= htmlspecialchars($message) ?>
            </span>

        </div>

    </div>

<?php endif; ?>

     <!-- FORM TAMBAH / EDIT -->
<?php if ($action === 'tambah' || $action === 'edit'): ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

        <div class="flex items-center justify-between mb-6">

            <div>

                <h2 class="text-lg font-bold text-gray-800">

                    <?= $action === 'edit'
                        ? 'Edit Produk'
                        : 'Tambah Produk'
                    ?>

                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    <?= $action === 'edit'
                        ? 'Perbarui informasi produk.'
                        : 'Masukkan informasi produk baru.'
                    ?>

                </p>

            </div>

            <a href="produk"
               class="text-gray-500 hover:text-gray-800">

                <i class="fas fa-xmark text-xl"></i>

            </a>

        </div>


        <?php if ($action === 'edit' && !$editProduct): ?>

            <div class="bg-red-50 text-red-700 p-4 rounded-lg">
                Produk tidak ditemukan.
            </div>

        <?php else: ?>

            <form method="POST"
                  action="produk?action=<?= $action === 'edit' ? 'edit' : 'tambah' ?>">

                <?php if ($action === 'edit'): ?>

                    <input
                        type="hidden"
                        name="id_product"
                        value="<?= htmlspecialchars($editProduct['id_product']) ?>"
                    >

                <?php endif; ?>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- NAMA -->

                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Produk
                        </label>

                        <input
                            type="text"
                            name="nama"
                            required
                            value="<?= htmlspecialchars($editProduct['nama'] ?? '') ?>"
                            placeholder="Contoh: Beras Kencur"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none"
                        >

                    </div>


                    <!-- KATEGORI -->

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori
                        </label>

                        <input
                            type="text"
                            name="kategori"
                            required
                            value="<?= htmlspecialchars($editProduct['kategori'] ?? '') ?>"
                            placeholder="Contoh: Jamu"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none"
                        >

                    </div>


                    <!-- HARGA -->

                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Harga
                        </label>

                        <div class="relative">

                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="harga"
                                required
                                min="0"
                                value="<?= htmlspecialchars($editProduct['harga'] ?? '') ?>"
                                placeholder="30000"
                                class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none"
                            >

                        </div>

                    </div>

                </div>


                <!-- BUTTON -->

                <div class="flex justify-end gap-3 mt-6">

                    <a href="produk"
                       class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold rounded-lg transition">

                        <i class="fas fa-save mr-2"></i>

                        <?= $action === 'edit'
                            ? 'Simpan Perubahan'
                            : 'Simpan Produk'
                        ?>

                    </button>

                </div>

            </form>

        <?php endif; ?>

    </div>

<?php endif; ?>


<!-- ==================================================
     SEARCH
================================================== -->

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">

    <form method="GET"
          action="produk"
          class="flex flex-col md:flex-row gap-3">

        <div class="relative flex-1">

            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

            <input
                type="text"
                name="search"
                value="<?= htmlspecialchars($keyword) ?>"
                placeholder="Cari nama atau kategori produk..."
                class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none"
            >

        </div>

        <button
            type="submit"
            class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg transition">

            <i class="fas fa-search mr-2"></i>

            Cari

        </button>

        <?php if ($keyword !== ''): ?>

            <a href="produk"
               class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 flex items-center justify-center transition">

                Reset

            </a>

        <?php endif; ?>

    </form>

</div>


     <!-- TABLE PRODUK -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">

        <div>

            <h2 class="font-bold text-gray-800">
                Daftar Produk
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                <?= count($products) ?> produk ditemukan
            </p>

        </div>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-50">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                        No
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                        Nama Produk
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                        Kategori
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                        Harga
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100">

                <?php if (count($products) > 0): ?>

                    <?php foreach ($products as $index => $product): ?>

                        <tr class="hover:bg-gray-50 transition">

                            <!-- NO -->

                            <td class="px-6 py-4 text-gray-500">

                                <?= $index + 1 ?>

                            </td>


                            <!-- NAMA -->

                            <td class="px-6 py-4">

                                <div class="font-semibold text-gray-800">

                                    <?= htmlspecialchars($product['nama']) ?>

                                </div>

                            </td>


                            <!-- KATEGORI -->

                            <td class="px-6 py-4">

                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">

                                    <?= htmlspecialchars($product['kategori']) ?>

                                </span>

                            </td>


                            <!-- HARGA -->

                            <td class="px-6 py-4 font-semibold text-gray-800">

                                Rp <?= number_format(
                                    $product['harga'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </td>


                            <!-- AKSI -->

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    <!-- EDIT -->

                                    <a
                                        href="produk?action=edit&id=<?= urlencode($product['id_product']) ?>"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition"
                                        title="Edit">

                                        <i class="fas fa-pen"></i>

                                    </a>


                                    <!-- HAPUS -->

                                    <a
                                        href="produk?action=hapus&id=<?= urlencode($product['id_product']) ?>"
                                        onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition"
                                        title="Hapus">

                                        <i class="fas fa-trash"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="5"
                            class="px-6 py-12 text-center">

                            <div class="flex flex-col items-center">

                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">

                                    <i class="fas fa-box-open text-2xl text-gray-400"></i>

                                </div>

                                <h3 class="font-semibold text-gray-700">
                                    Belum ada produk
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Silakan tambahkan produk terlebih dahulu.
                                </p>

                            </div>

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>
