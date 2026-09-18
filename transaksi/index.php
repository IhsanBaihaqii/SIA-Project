<?php
include '../config/koneksi.php';
include '../includes/helpers.php';

// ============ FILTER & PAGINATION ============
$search   = trim($_GET['q'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 10;
$offset   = ($page - 1) * $perPage;

$where  = '';
$params = [];

if ($search !== '') {
    $where = "WHERE p.nama LIKE :search";
    $params[':search'] = "%$search%";
}

// Hitung total
$stmtCount = $pdo->prepare("
    SELECT COUNT(*) FROM tbl_transaction t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    $where
");
$stmtCount->execute($params);
$totalData  = (int)$stmtCount->fetchColumn();
$totalPage  = max(1, (int)ceil($totalData / $perPage));

// Ambil data halaman ini
$sql = "
    SELECT t.id_transaction, t.tanggal, t.total,
           COALESCE(p.nama, 'Umum') AS nama_pelanggan,
           (SELECT COUNT(*) FROM tbl_transaction_details d
            WHERE d.id_transaction = t.id_transaction) AS jml_item
    FROM tbl_transaction t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    $where
    ORDER BY t.id_transaction DESC
    LIMIT $perPage OFFSET $offset
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = $_GET['success'] ?? '';
$error   = $_GET['error']   ?? '';

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
            <p class="text-gray-500 text-sm">Kelola semua data transaksi Anda di sini.</p>
        </div>
        <a href="../kasir/index.php"
           class="mt-3 sm:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2 justify-center">
            <i class="fa-solid fa-plus"></i> Tambah Transaksi
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-check-circle mr-2"></i> Transaksi berhasil dihapus.
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i> <?= bersih($error) ?>
        </div>
    <?php endif; ?>

    <!-- Filter / Pencarian -->
    <form method="GET" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="q" value="<?= bersih($search) ?>"
                       placeholder="Cari nama pelanggan..."
                       class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            <?php if ($search !== ''): ?>
                <a href="index.php"
                   class="border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-xmark mr-1"></i> Reset
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                        <th class="px-6 py-4 font-semibold">Nama Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold text-center">Item</th>
                        <th class="px-6 py-4 font-semibold text-right">Total</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($transaksi)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-receipt text-3xl mb-2 block"></i>
                                Belum ada transaksi.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transaksi as $t): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                #TRX-<?= str_pad($t['id_transaction'], 4, '0', STR_PAD_LEFT) ?>
                            </td>
                            <td class="px-6 py-4"><?= bersih($t['nama_pelanggan']) ?></td>
                            <td class="px-6 py-4 text-gray-600">
                                <?= date('d M Y, H:i', strtotime($t['tanggal'])) ?>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">
                                <?= (int)$t['jml_item'] ?> item
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-blue-600">
                                <?= rupiah($t['total']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                            onclick="bukaDetail(<?= $t['id_transaction'] ?>)"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button type="button"
                                            onclick="bukaHapus(<?= $t['id_transaction'] ?>, '#TRX-<?= str_pad($t['id_transaction'], 4, '0', STR_PAD_LEFT) ?>')"
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalData > 0): ?>
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
            <span class="text-gray-500">
                Menampilkan <?= count($transaksi) ?> dari <?= $totalData ?> transaksi
            </span>

            <?php if ($totalPage > 1): ?>
            <div class="flex items-center gap-1">
                <?php
                // Bangun query string untuk pagination
                $qs = $_GET;
                function buildUrl($page, $qs) {
                    $qs['page'] = $page;
                    return 'index.php?' . http_build_query($qs);
                }
                ?>

                <!-- Sebelumnya -->
                <a href="<?= $page > 1 ? buildUrl($page - 1, $qs) : '#' ?>"
                   class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors
                          <?= $page <= 1 ? 'pointer-events-none opacity-50' : '' ?>">
                    Sebelumnya
                </a>

                <!-- Nomor halaman -->
                <?php
                $start = max(1, $page - 2);
                $end   = min($totalPage, $page + 2);

                if ($start > 1) {
                    echo '<a href="' . buildUrl(1, $qs) . '" class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50">1</a>';
                    if ($start > 2) echo '<span class="px-2 text-gray-400">...</span>';
                }

                for ($i = $start; $i <= $end; $i++):
                ?>
                    <a href="<?= buildUrl($i, $qs) ?>"
                       class="px-3 py-1 rounded-lg transition-colors
                              <?= $i === $page
                                  ? 'bg-blue-600 text-white'
                                  : 'border border-gray-200 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php
                if ($end < $totalPage) {
                    if ($end < $totalPage - 1) echo '<span class="px-2 text-gray-400">...</span>';
                    echo '<a href="' . buildUrl($totalPage, $qs) . '" class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50">' . $totalPage . '</a>';
                }
                ?>

                <!-- Selanjutnya -->
                <a href="<?= $page < $totalPage ? buildUrl($page + 1, $qs) : '#' ?>"
                   class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors
                          <?= $page >= $totalPage ? 'pointer-events-none opacity-50' : '' ?>">
                    Selanjutnya
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- ============ MODAL DETAIL ============ -->
<div id="modalDetail" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-receipt text-blue-600 mr-2"></i> Detail Transaksi
            </h3>
            <button onclick="closeModal('modalDetail')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <div id="detailContent" class="text-sm">
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-spinner fa-spin text-2xl"></i>
                <p class="mt-2">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL HAPUS ============ -->
<div id="modalHapus" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-lg rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> Hapus Transaksi
            </h3>
            <button onclick="closeModal('modalHapus')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <p class="text-gray-700 mb-2">
            Apakah Anda yakin ingin menghapus transaksi <strong id="hapusLabel"></strong>?
        </p>
        <p class="text-sm text-gray-500 mb-6">
            <i class="fas fa-info-circle mr-1"></i>
            Semua detail item dan jurnal terkait akan ikut terhapus. Stok <b>tidak</b> dikembalikan otomatis.
        </p>
        <form action="hapus.php" method="GET" class="flex justify-end gap-2">
            <input type="hidden" name="id" id="hapusId">
            <button type="button" onclick="closeModal('modalHapus')"
                    class="text-gray-600 hover:text-gray-800 px-4 py-2">Batal</button>
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-5 rounded-lg">
                Hapus
            </button>
        </form>
    </div>
</div>

<script>
// ============ MODAL HELPERS ============
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// ============ DETAIL (AJAX) ============
function bukaDetail(id) {
    const content = document.getElementById('detailContent');
    content.innerHTML = `
        <div class="text-center py-8 text-gray-400">
            <i class="fas fa-spinner fa-spin text-2xl"></i>
            <p class="mt-2">Memuat data...</p>
        </div>`;
    openModal('modalDetail');

    fetch('detail.php?id=' + id)
        .then(r => r.text())
        .then(html => { content.innerHTML = html; })
        .catch(() => {
            content.innerHTML = `<p class="text-red-500 text-center py-4">Gagal memuat detail.</p>`;
        });
}

// ============ HAPUS ============
function bukaHapus(id, label) {
    document.getElementById('hapusId').value = id;
    document.getElementById('hapusLabel').textContent = label;
    openModal('modalHapus');
}

// ============ KLIK DI LUAR MODAL ============
window.onclick = function(event) {
    ['modalDetail', 'modalHapus'].forEach(id => {
        const modal = document.getElementById(id);
        if (event.target === modal) modal.classList.add('hidden');
    });
}
</script>

<?php include '../layouts/footer.php'; ?>