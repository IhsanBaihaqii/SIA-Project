<?php
include '../config/koneksi.php';
include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';

// Konfigurasi paginasi
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';
$params = [];

if ($search !== '') {
    $where = "WHERE nama LIKE :search OR no_hp LIKE :search OR alamat LIKE :search";
    $params[':search'] = "%$search%";
}

// Hitung total data
$countQuery = "SELECT COUNT(*) FROM tbl_pelanggan $where";
$stmt = $pdo->prepare($countQuery);
$stmt->execute($params);
$totalData = $stmt->fetchColumn();
$totalPages = ceil($totalData / $limit);

// Ambil data pelanggan
$query = "SELECT * FROM tbl_pelanggan $where ORDER BY id_pelanggan DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$pelanggan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <!-- Header & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Pelanggan</h1>
            <p class="text-gray-500 text-sm">Kelola semua data pelanggan Anda di sini.</p>
        </div>
        <a href="tambah.php" class="mt-3 sm:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Pelanggan
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
            <?php
            if ($_GET['success'] == 'tambah') echo "Pelanggan berhasil ditambahkan.";
            elseif ($_GET['success'] == 'edit') echo "Pelanggan berhasil diperbarui.";
            elseif ($_GET['success'] == 'hapus') echo "Pelanggan berhasil dihapus.";
            ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <!-- Filter / Pencarian -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-3">
        <form method="GET" action="index.php" class="flex-1 min-w-[200px] flex gap-2">
            <div class="relative flex-1">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, no HP, atau alamat..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-search"></i> Cari
            </button>
            <?php if ($search !== ''): ?>
                <a href="index.php" class="border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition-colors">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Nomor HP</th>
                        <th class="px-6 py-4 font-semibold">Alamat</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (count($pelanggan) > 0): ?>
                        <?php $no = $offset + 1; ?>
                        <?php foreach ($pelanggan as $row): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-500"><?= $no++ ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shrink-0">
                                            <?= strtoupper(substr($row['nama'], 0, 1)) ?>
                                        </div>
                                        <span class="font-medium text-gray-800"><?= htmlspecialchars($row['nama']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <i class="fa-solid fa-phone text-gray-400 text-xs mr-2"></i>
                                    <?= htmlspecialchars($row['no_hp'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($row['alamat'] ?? '') ?>">
                                    <i class="fa-solid fa-location-dot text-gray-400 text-xs mr-2"></i>
                                    <?= htmlspecialchars($row['alamat'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="edit.php?id=<?= $row['id_pelanggan'] ?>" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="hapus.php?id=<?= $row['id_pelanggan'] ?>" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data pelanggan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer tabel: info & paginasi -->
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
            <span class="text-gray-500">
                Menampilkan <?= count($pelanggan) ?> dari <?= $totalData ?> pelanggan
            </span>
            <?php if ($totalPages > 1): ?>
                <div class="flex items-center gap-1">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50">Sebelumnya</a>
                    <?php else: ?>
                        <button class="px-3 py-1 border border-gray-200 rounded-lg opacity-50 cursor-not-allowed" disabled>Sebelumnya</button>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="px-3 py-1 rounded-lg <?= $i == $page ? 'bg-blue-600 text-white' : 'border border-gray-200 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50">Selanjutnya</a>
                    <?php else: ?>
                        <button class="px-3 py-1 border border-gray-200 rounded-lg opacity-50 cursor-not-allowed" disabled>Selanjutnya</button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>