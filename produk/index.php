<?php
include '../config/koneksi.php';

$query = "SELECT * FROM tbl_products";
$stmt = $pdo->prepare($query);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// Cek pesan sukses/error dari parameter GET
$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen">
    <div class="p-6">

        <!-- Pesan Sukses / Error -->
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $success === 'tambah' ? 'Produk berhasil ditambahkan.' : ($success === 'edit' ? 'Produk berhasil diupdate.' : 'Produk berhasil dihapus.') ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Header & Tombol Tambah -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <button onclick="openTambahModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Produk
            </button>
        </div>

        <!-- Tabel Produk -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $product['id_product'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($product['nama']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($product['kategori']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($product['harga'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $product['stok'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <!-- Tombol Edit dengan data produk -->
                            <button onclick="openEditModal(<?= $product['id_product'] ?>, '<?= addslashes($product['nama']) ?>', '<?= addslashes($product['kategori']) ?>', <?= $product['harga'] ?>, <?= $product['stok'] ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- Tombol Hapus -->
                            <button onclick="openHapusModal(<?= $product['id_product'] ?>, '<?= addslashes($product['nama']) ?>')" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- ===================== MODAL TAMBAH ===================== -->
<div id="modalTambah" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Tambah Produk</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form action="tambah.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">Nama Produk</label>
                <input type="text" name="nama" id="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="kategori">Kategori</label>
                <input type="text" name="kategori" id="kategori" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="harga">Harga</label>
                <input type="number" name="harga" id="harga" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="stok">Stok</label>
                <input type="number" name="stok" id="stok" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="flex items-center justify-end">
                <button type="button" onclick="closeModal('modalTambah')" class="text-gray-600 hover:text-gray-800 mr-3">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== MODAL EDIT ===================== -->
<div id="modalEdit" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Edit Produk</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form action="edit.php" method="POST">
            <input type="hidden" name="id_product" id="edit_id">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_nama">Nama Produk</label>
                <input type="text" name="nama" id="edit_nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_kategori">Kategori</label>
                <input type="text" name="kategori" id="edit_kategori" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_harga">Harga</label>
                <input type="number" name="harga" id="edit_harga" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_stok">Stok</label>
                <input type="number" name="stok" id="edit_stok" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="flex items-center justify-end">
                <button type="button" onclick="closeModal('modalEdit')" class="text-gray-600 hover:text-gray-800 mr-3">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== MODAL HAPUS ===================== -->
<div id="modalHapus" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Hapus Produk</h3>
            <button onclick="closeModal('modalHapus')" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus produk <strong id="hapus_nama"></strong>?</p>
        <form action="hapus.php" method="GET">
            <input type="hidden" name="id_product" id="hapus_id">
            <div class="flex items-center justify-end">
                <button type="button" onclick="closeModal('modalHapus')" class="text-gray-600 hover:text-gray-800 mr-3">Batal</button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk membuka/tutup modal
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Modal Tambah
    function openTambahModal() {
        openModal('modalTambah');
    }

    // Modal Edit: isi data dari parameter
    function openEditModal(id, nama, kategori, harga, stok) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_kategori').value = kategori;
        document.getElementById('edit_harga').value = harga;
        document.getElementById('edit_stok').value = stok;
        openModal('modalEdit');
    }

    // Modal Hapus
    function openHapusModal(id, nama) {
        document.getElementById('hapus_id').value = id;
        document.getElementById('hapus_nama').textContent = nama;
        openModal('modalHapus');
    }

    // Tutup modal saat klik di luar area modal (opsional)
    window.onclick = function(event) {
        const modals = ['modalTambah', 'modalEdit', 'modalHapus'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    }
</script>

<?php include '../layouts/footer.php'; ?>