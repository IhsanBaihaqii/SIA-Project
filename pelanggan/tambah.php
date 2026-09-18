<?php
require_once '../config/database.php';
require_once '../config/auth.php';
?>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/sidebar.php'; ?>
<?php include '../layouts/navbar.php'; ?>

<main class="p-4 md:p-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Pelanggan
        </h1>

        <p class="text-gray-500">
            Tambahkan data pelanggan baru
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-2xl">

        <form action="proses.php" method="POST">

            <input type="hidden" name="aksi" value="tambah">

            <div class="mb-4">

                <label class="block mb-2 font-medium">
                    Nama Pelanggan
                </label>

                <input
                    type="text"
                    name="nama"
                    required
                    class="w-full border rounded-lg px-4 py-2"
                    placeholder="Masukkan nama pelanggan"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-2 font-medium">
                    Alamat
                </label>

                <textarea
                    name="alamat"
                    required
                    class="w-full border rounded-lg px-4 py-2"
                    rows="4"
                    placeholder="Masukkan alamat pelanggan"
                ></textarea>

            </div>

            <div class="mb-6">

                <label class="block mb-2 font-medium">
                    Nomor HP
                </label>

                <input
                    type="text"
                    name="nomor_hp"
                    required
                    class="w-full border rounded-lg px-4 py-2"
                    placeholder="Contoh: 08123456789"
                >

            </div>

            <div class="flex gap-2">

                <a href="index.php"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Kembali
                </a>

                <button
                    type="submit"
                    class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</main>

<?php include '../layouts/footer.php'; ?>