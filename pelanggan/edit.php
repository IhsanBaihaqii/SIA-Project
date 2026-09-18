<?php
include '../config/koneksi.php';
include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: index.php?error=" . urlencode("ID pelanggan tidak valid"));
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tbl_pelanggan WHERE id_pelanggan = :id");
$stmt->execute([':id' => $id]);
$pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelanggan) {
    header("Location: index.php?error=" . urlencode("Pelanggan tidak ditemukan"));
    exit;
}
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Pelanggan</h1>
            <a href="index.php" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="proses.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_pelanggan" value="<?= $pelanggan['id_pelanggan'] ?>">

                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" required value="<?= htmlspecialchars($pelanggan['nama']) ?>" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                </div>

                <div class="mb-4">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="<?= htmlspecialchars($pelanggan['no_hp'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                </div>

                <div class="mb-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm"><?= htmlspecialchars($pelanggan['alamat'] ?? '') ?></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="index.php" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm hover:bg-gray-50 transition-colors">Batal</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-sm transition-colors text-sm">
                        <i class="fa-solid fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>