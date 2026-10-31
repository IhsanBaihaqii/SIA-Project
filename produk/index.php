<?php
include '../config/koneksi.php';

$query = "SELECT * FROM tbl_products ORDER BY kode_produk ASC";
$stmt  = $pdo->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-[#eef2ff]">
    <div class="p-6">

        <!-- ===================== ALERT ===================== -->
        <?php if ($success): ?>
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 border-l-4 border-l-green-500 text-green-700 px-4 py-3 rounded-lg mb-4 shadow-sm">
                <i class="fa-solid fa-circle-check text-green-500" aria-hidden="true"></i>
                <span class="text-sm font-medium">
                    <?= $success === 'tambah' ? 'Produk berhasil ditambahkan.' : ($success === 'edit' ? 'Produk berhasil diupdate.' : 'Produk berhasil dihapus.') ?>
                </span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 border-l-4 border-l-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-red-500" aria-hidden="true"></i>
                <span class="text-sm font-medium"><?= htmlspecialchars($error) ?></span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        <?php endif; ?>

        <!-- ===================== HEADER ===================== -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
                <p class="text-sm text-gray-500">Kelola data produk & stok toko</p>
            </div>
            <button onclick="openTambahModal()"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-md shadow-blue-600/25 transition-colors">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Tambah Produk
            </button>
        </div>

        <!-- ===================== TABLE ===================== -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga Jual</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <i class="fa-solid fa-box-open text-3xl" aria-hidden="true"></i>
                                        <p class="text-sm">Belum ada produk. Klik <strong>Tambah Produk</strong> untuk mulai.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product):
                                // Tentukan warna badge stok
                                $stok = (int)$product['stok'];
                                if ($stok === 0) {
                                    $stok_cls = 'bg-red-50 text-red-600 ring-red-100';
                                    $stok_label = 'Habis';
                                } elseif ($stok <= 10) {
                                    $stok_cls = 'bg-amber-50 text-amber-600 ring-amber-100';
                                    $stok_label = $stok . ' ' . htmlspecialchars($product['satuan']);
                                } else {
                                    $stok_cls = 'bg-green-50 text-green-600 ring-green-100';
                                    $stok_label = $stok . ' ' . htmlspecialchars($product['satuan']);
                                }
                            ?>
                                <tr class="hover:bg-blue-50/40 transition-colors">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-xs font-mono font-medium">
                                            <?= htmlspecialchars($product['kode_produk']) ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($product['nama']) ?></p>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                            <?= htmlspecialchars($product['kategori']) ?>
                                        </span>
                                    </td>

                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-semibold text-gray-800">
                                        Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 <?= $stok_cls ?>">
                                            <?= $stok_label ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                        <div class="inline-flex items-center gap-1">
                                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode([
                                                                                'kode_produk' => $product['kode_produk'],
                                                                                'nama'        => $product['nama'],
                                                                                'kategori'    => $product['kategori'],
                                                                                'satuan'      => $product['satuan'],
                                                                                'harga'       => (int)$product['harga'],
                                                                                'stok'        => (int)$product['stok'],
                                                                            ])) ?>)"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-blue-600 hover:bg-blue-100 transition-colors"
                                                title="Edit produk">
                                                <i class="fas fa-edit text-sm" aria-hidden="true"></i>
                                            </button>
                                            <button onclick="openHapusModal(<?= htmlspecialchars(json_encode([
                                                                                'kode_produk' => $product['kode_produk'],
                                                                                'nama'        => $product['nama'],
                                                                            ])) ?>)"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-red-600 hover:bg-red-100 transition-colors"
                                                title="Hapus produk">
                                                <i class="fas fa-trash text-sm" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- ===================== MODAL TAMBAH ===================== -->
<div id="modalTambah" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 sm:top-20 mx-auto p-6 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Tambah Produk</h3>
                    <p class="text-xs text-gray-400">Isi data produk baru</p>
                </div>
            </div>
            <button onclick="closeModal('modalTambah')" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors" aria-label="Tutup">
                <i class="fas fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <form action="tambah.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="nama">Nama Produk</label>
                <input type="text" name="nama" id="nama" required
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="kategori">Kategori</label>
                    <input type="text" name="kategori" id="kategori" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="satuan">Satuan</label>
                    <select name="satuan" id="satuan" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="pcs">pcs</option>
                        <option value="kg">kg</option>
                        <option value="gram">gram</option>
                        <option value="liter">liter</option>
                        <option value="dus">dus</option>
                        <option value="pack">pack</option>
                        <option value="botol">botol</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="harga">Harga Jual</label>
                    <input type="number" name="harga" id="harga" required min="1"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="stok">Stok Awal</label>
                <input type="number" name="stok" id="stok" required min="0"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modalTambah')"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/25 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== MODAL EDIT ===================== -->
<div id="modalEdit" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 sm:top-20 mx-auto p-6 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <i class="fas fa-pen" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Edit Produk</h3>
                    <p class="text-xs text-gray-400">Ubah data produk</p>
                </div>
            </div>
            <button onclick="closeModal('modalEdit')" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors" aria-label="Tutup">
                <i class="fas fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <form action="edit.php" method="POST" class="space-y-4">
            <input type="hidden" name="kode_produk" id="edit_id">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="edit_nama">Nama Produk</label>
                <input type="text" name="nama" id="edit_nama" required
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="edit_kategori">Kategori</label>
                    <input type="text" name="kategori" id="edit_kategori" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="edit_satuan">Satuan</label>
                    <select name="satuan" id="edit_satuan" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                        <option value="pcs">pcs</option>
                        <option value="kg">kg</option>
                        <option value="gram">gram</option>
                        <option value="liter">liter</option>
                        <option value="dus">dus</option>
                        <option value="pack">pack</option>
                        <option value="botol">botol</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="edit_harga">Harga Jual</label>
                    <input type="number" name="harga" id="edit_harga" required min="1"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="edit_stok">Stok</label>
                <input type="number" name="stok" id="edit_stok" required min="0"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/25 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===================== MODAL HAPUS ===================== -->
<div id="modalHapus" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 sm:top-20 mx-auto p-6 border-0 w-full max-w-sm shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Hapus Produk</h3>
                    <p class="text-xs text-gray-400">Tindakan ini tidak bisa dibatalkan</p>
                </div>
            </div>
            <button onclick="closeModal('modalHapus')" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors" aria-label="Tutup">
                <i class="fas fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <p class="text-gray-600 mb-5 text-sm">
            Yakin ingin menghapus produk <strong id="hapus_nama" class="text-gray-900"></strong>?
        </p>
        <form action="hapus.php" method="GET">
            <input type="hidden" name="kode_produk" id="hapus_id">
            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeModal('modalHapus')"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-red-600 hover:bg-red-700 text-white shadow-md shadow-red-600/25 transition-colors">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openTambahModal() {
        openModal('modalTambah');
    }

    // Terima object JSON (bukan argumen terpisah)
    function openEditModal(data) {
        document.getElementById('edit_id').value = data.kode_produk;
        document.getElementById('edit_nama').value = data.nama;
        document.getElementById('edit_kategori').value = data.kategori;
        document.getElementById('edit_satuan').value = data.satuan;
        document.getElementById('edit_harga').value = data.harga;
        document.getElementById('edit_stok').value = data.stok;
        openModal('modalEdit');
    }

    function openHapusModal(data) {
        document.getElementById('hapus_id').value = data.kode_produk;
        document.getElementById('hapus_nama').textContent = data.nama;
        openModal('modalHapus');
    }

    // Tutup modal saat klik backdrop
    window.addEventListener('click', function(e) {
        ['modalTambah', 'modalEdit', 'modalHapus'].forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) closeModal(id);
        });
    });

    // Tutup modal saat Esc
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['modalTambah', 'modalEdit', 'modalHapus'].forEach(id => closeModal(id));
        }
    });
</script>

<?php include '../layouts/footer.php'; ?>