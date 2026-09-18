<?php

require_once '../config/database.php';
require_once '../config/auth.php';

$id = $_GET['id'] ?? 0;

$query = "SELECT * FROM tbl_pelanggan WHERE id_pelanggan = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$pelanggan = mysqli_fetch_assoc($result);

if (!$pelanggan) {
    die('Data pelanggan tidak ditemukan.');
}

?>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/sidebar.php'; ?>
<?php include '../layouts/navbar.php'; ?>

<main class="p-4 md:p-8">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Edit Pelanggan
        </h1>

        <p class="text-gray-500">
            Ubah data pelanggan
        </p>

    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-2xl">

        <form action="proses.php" method="POST">

            <input type="hidden" name="aksi" value="edit">

            <input
                type="hidden"
                name="id_pelanggan"
                value="<?= $pelanggan['id_pelanggan']; ?>"
            >

            <div class="mb-4">

                <label class="block mb-2 font-medium">
                    Nama Pelanggan
                </label>

                <input
                    type="text"
                    name="nama"
                    required
                    value="<?= htmlspecialchars($pelanggan['nama']); ?>"
                    class="w-full border rounded-lg px-4 py-2"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-2 font-medium">
                    Alamat
                </label>

                <textarea
                    name="alamat"
                    required
                    rows="4"
                    class="w-full border rounded-lg px-4 py-2"
                ><?= htmlspecialchars($pelanggan['alamat']); ?></textarea>

            </div>

            <div class="mb-6">

                <label class="block mb-2 font-medium">
                    Nomor HP
                </label>

                <input
                    type="text"
                    name="nomor_hp"
                    required
                    value="<?= htmlspecialchars($pelanggan['nomor_hp']); ?>"
                    class="w-full border rounded-lg px-4 py-2"
                >

            </div>

            <div class="flex gap-2">

                <a
                    href="index.php"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg"
                >
                    Kembali
                </a>

                <button
                    type="submit"
                    class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded-lg"
                >
                    Update
                </button>

            </div>

        </form>

    </div>

</main>

<?php include '../layouts/footer.php'; ?>