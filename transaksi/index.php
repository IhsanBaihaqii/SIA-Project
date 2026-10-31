<?php
include '../config/koneksi.php';
include '../includes/helpers.php';

// ============ FILTER PERIODE ============
$periode_pilihan = [
    'all'     => ['label' => 'Semua',       'sql' => null,                                                         'icon' => 'fa-infinity'],
    '7d'      => ['label' => '7 Hari',      'sql' => 't.tanggal >= CURDATE() - INTERVAL 6 DAY',                    'icon' => 'fa-calendar-day'],
    '30d'     => ['label' => '30 Hari',     'sql' => 't.tanggal >= CURDATE() - INTERVAL 29 DAY',                   'icon' => 'fa-calendar-days'],
    'bulan'   => ['label' => 'Bulan Ini',   'sql' => 'YEAR(t.tanggal) = YEAR(CURDATE()) AND MONTH(t.tanggal) = MONTH(CURDATE())', 'icon' => 'fa-calendar'],
];

$periode = $_GET['periode'] ?? '30d';
if (!isset($periode_pilihan[$periode])) {
    $periode = '30d';
}

// ============ SEARCH & PAGINATION ============
$search  = trim($_GET['q'] ?? '');
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset  = ($page - 1) * $perPage;

$where  = [];
$params = [];

// Filter periode
if ($periode_pilihan[$periode]['sql'] !== null) {
    $where[] = $periode_pilihan[$periode]['sql'];
}

// Filter search
if ($search !== '') {
    $where[] = "p.nama LIKE :search";
    $params[':search'] = "%$search%";
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ============ SUMMARY STATS (untuk periode terpilih) ============
$sqlStats = "
    SELECT 
        COUNT(*) AS total_trx,
        COALESCE(SUM(t.total), 0) AS total_pendapatan,
        COALESCE(AVG(t.total), 0) AS rata_rata,
        COALESCE(MAX(t.total), 0) AS max_trx
    FROM tbl_transaction t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    $whereSql
";
$stmtStats = $pdo->prepare($sqlStats);
$stmtStats->execute($params);
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

// ============ HITUNG TOTAL DATA ============
$stmtCount = $pdo->prepare("
    SELECT COUNT(*) FROM tbl_transaction t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    $whereSql
");
$stmtCount->execute($params);
$totalData = (int)$stmtCount->fetchColumn();
$totalPage = max(1, (int)ceil($totalData / $perPage));

// ============ AMBIL DATA ============
$sql = "
    SELECT t.id_transaction, t.tanggal, t.total,
           COALESCE(p.nama, 'Umum') AS nama_pelanggan,
           (SELECT COUNT(*) FROM tbl_transaction_details d
            WHERE d.id_transaction = t.id_transaction) AS jml_item
    FROM tbl_transaction t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    $whereSql
    ORDER BY t.id_transaction DESC
    LIMIT $perPage OFFSET $offset
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = $_GET['success'] ?? '';
$error   = $_GET['error']   ?? '';

// Helper URL (pertahankan filter saat pindah halaman)
function urlWith(array $overrides = []): string
{
    $qs = array_merge($_GET, $overrides);
    return 'index.php?' . http_build_query($qs);
}

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-[#eef2ff] p-6">
    <!-- ============ HEADER ============ -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
            <p class="text-gray-500 text-sm">Kelola dan pantau semua transaksi penjualan.</p>
        </div>
        <a href="../kasir/index.php"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-md shadow-blue-600/25 transition-colors font-semibold text-sm">
            <i class="fa-solid fa-plus" aria-hidden="true"></i> Tambah Transaksi
        </a>
    </div>

    <!-- ============ NOTIFIKASI ============ -->
    <?php if ($success): ?>
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 border-l-4 border-l-green-500 text-green-700 px-4 py-3 rounded-lg mb-4 shadow-sm">
            <i class="fa-solid fa-circle-check text-green-500" aria-hidden="true"></i>
            <span class="text-sm font-medium">Transaksi berhasil dihapus.</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 border-l-4 border-l-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-red-500" aria-hidden="true"></i>
            <span class="text-sm font-medium"><?= bersih($error) ?></span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- ============ FILTER PERIODE (TABS) ============ -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-4">
        <div class="flex flex-wrap items-center gap-1">
            <?php foreach ($periode_pilihan as $key => $p):
                $active = $periode === $key;
            ?>
                <a href="<?= urlWith(['periode' => $key, 'page' => 1]) ?>"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium transition-all
                   <?= $active
                        ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                    <i class="fa-solid <?= $p['icon'] ?> text-xs" aria-hidden="true"></i>
                    <?= $p['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ============ SUMMARY CARDS ============ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Transaksi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($stats['total_trx']) ?></p>
                    <p class="text-xs text-gray-400 mt-1"><?= $periode_pilihan[$periode]['label'] ?></p>
                </div>
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-600 mt-1 truncate" title="<?= rupiah($stats['total_pendapatan']) ?>">
                        <?= rupiah($stats['total_pendapatan']) ?>
                    </p>
                    <p class="text-xs text-gray-400 mt-1"><?= $periode_pilihan[$periode]['label'] ?></p>
                </div>
                <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center text-green-600 shrink-0">
                    <i class="fa-solid fa-coins" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <!-- Rata-rata -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500">Rata-rata / Transaksi</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1 truncate" title="<?= rupiah($stats['rata_rata']) ?>">
                        <?= rupiah($stats['rata_rata']) ?>
                    </p>
                    <p class="text-xs text-gray-400 mt-1"><?= $periode_pilihan[$periode]['label'] ?></p>
                </div>
                <div class="w-11 h-11 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 shrink-0">
                    <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <!-- Transaksi Terbesar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500">Transaksi Terbesar</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1 truncate" title="<?= rupiah($stats['max_trx']) ?>">
                        <?= rupiah($stats['max_trx']) ?>
                    </p>
                    <p class="text-xs text-gray-400 mt-1"><?= $periode_pilihan[$periode]['label'] ?></p>
                </div>
                <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 shrink-0">
                    <i class="fa-solid fa-arrow-trend-up" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ SEARCH BAR ============ -->
    <form method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-4 flex flex-wrap items-center gap-3">
        <input type="hidden" name="periode" value="<?= bersih($periode) ?>">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                <input type="text" name="q" value="<?= bersih($search) ?>"
                    placeholder="Cari nama pelanggan..."
                    class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors inline-flex items-center gap-1.5">
                <i class="fa-solid fa-search" aria-hidden="true"></i> Cari
            </button>
            <?php if ($search !== ''): ?>
                <a href="<?= urlWith(['q' => null, 'page' => 1]) ?>"
                    class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i> Reset
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- ============ TABEL ============ -->
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <i class="fas fa-receipt text-3xl" aria-hidden="true"></i>
                                    <p class="text-sm">Belum ada transaksi di periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transaksi as $t): ?>
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-xs font-mono font-medium">
                                        #TRX-<?= str_pad($t['id_transaction'], 4, '0', STR_PAD_LEFT) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800"><?= bersih($t['nama_pelanggan']) ?></td>
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                    <?= date('d M Y', strtotime($t['tanggal'])) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                        <?= (int)$t['jml_item'] ?> item
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-blue-600 whitespace-nowrap">
                                    <?= rupiah($t['total']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button"
                                            onclick="bukaDetail(<?= $t['id_transaction'] ?>)"
                                            class="w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Detail">
                                            <i class="fa-solid fa-eye text-sm" aria-hidden="true"></i>
                                        </button>
                                        <button type="button"
                                            onclick="bukaHapus(<?= $t['id_transaction'] ?>, '#TRX-<?= str_pad($t['id_transaction'], 4, '0', STR_PAD_LEFT) ?>')"
                                            class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash-can text-sm" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ============ PAGINATION ============ -->
        <?php if ($totalData > 0): ?>
            <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
                <span class="text-gray-500">
                    Menampilkan <strong><?= count($transaksi) ?></strong> dari <strong><?= number_format($totalData) ?></strong> transaksi
                </span>

                <?php if ($totalPage > 1): ?>
                    <div class="flex items-center gap-1">
                        <a href="<?= $page > 1 ? urlWith(['page' => $page - 1]) : '#' ?>"
                            class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-xs font-medium
                          <?= $page <= 1 ? 'pointer-events-none opacity-50' : '' ?>">
                            <i class="fa-solid fa-chevron-left text-[0.6rem]" aria-hidden="true"></i>
                        </a>

                        <?php
                        $start = max(1, $page - 2);
                        $end   = min($totalPage, $page + 2);

                        if ($start > 1) {
                            echo '<a href="' . urlWith(['page' => 1]) . '" class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 text-xs">1</a>';
                            if ($start > 2) echo '<span class="px-2 text-gray-400">…</span>';
                        }
                        for ($i = $start; $i <= $end; $i++):
                        ?>
                            <a href="<?= urlWith(['page' => $i]) ?>"
                                class="px-3 py-1.5 rounded-lg text-xs transition-colors
                              <?= $i === $page
                                    ? 'bg-blue-600 text-white shadow-sm font-semibold'
                                    : 'border border-gray-200 hover:bg-gray-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor;
                        if ($end < $totalPage) {
                            if ($end < $totalPage - 1) echo '<span class="px-2 text-gray-400">…</span>';
                            echo '<a href="' . urlWith(['page' => $totalPage]) . '" class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 text-xs">' . $totalPage . '</a>';
                        }
                        ?>

                        <a href="<?= $page < $totalPage ? urlWith(['page' => $page + 1]) : '#' ?>"
                            class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-xs font-medium
                          <?= $page >= $totalPage ? 'pointer-events-none opacity-50' : '' ?>">
                            <i class="fa-solid fa-chevron-right text-[0.6rem]" aria-hidden="true"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- ============ MODAL DETAIL ============ -->
<div id="modalDetail" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border-0 w-full max-w-2xl shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fas fa-receipt" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Detail Transaksi</h3>
                    <p class="text-xs text-gray-400">Rincian item & pembayaran</p>
                </div>
            </div>
            <button onclick="closeModal('modalDetail')"
                class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                aria-label="Tutup">
                <i class="fas fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <div id="detailContent" class="text-sm">
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-spinner fa-spin text-2xl" aria-hidden="true"></i>
                <p class="mt-2">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL HAPUS ============ -->
<div id="modalHapus" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Hapus Transaksi</h3>
                    <p class="text-xs text-gray-400">Tindakan ini tidak bisa dibatalkan</p>
                </div>
            </div>
            <button onclick="closeModal('modalHapus')"
                class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                aria-label="Tutup">
                <i class="fas fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <p class="text-gray-700 mb-2 text-sm">
            Yakin ingin menghapus transaksi <strong id="hapusLabel" class="text-gray-900"></strong>?
        </p>
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-5">
            <p class="text-xs text-amber-700">
                <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                Semua detail item dan jurnal terkait akan ikut terhapus. Stok <b>tidak</b> dikembalikan otomatis.
            </p>
        </div>
        <form action="hapus.php" method="POST" class="flex justify-end gap-2">
            <input type="hidden" name="id" id="hapusId">
            <button type="button" onclick="closeModal('modalHapus')"
                class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                Batal
            </button>
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow-md shadow-red-600/25 transition-colors">
                Ya, Hapus
            </button>
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
            .then(r => {
                if (!r.ok) throw new Error('Gagal');
                return r.text();
            })
            .then(html => {
                content.innerHTML = html;
            })
            .catch(() => {
                content.innerHTML = `<div class="text-center py-6 text-red-500">
                    <i class="fas fa-triangle-exclamation text-2xl mb-2"></i>
                    <p class="text-sm">Gagal memuat detail transaksi.</p>
                </div>`;
            });
    }

    // ============ HAPUS ============
    function bukaHapus(id, label) {
        document.getElementById('hapusId').value = id;
        document.getElementById('hapusLabel').textContent = label;
        openModal('modalHapus');
    }

    // Tutup modal saat klik backdrop
    window.addEventListener('click', function(e) {
        ['modalDetail', 'modalHapus'].forEach(id => {
            const modal = document.getElementById(id);
            if (e.target === modal) closeModal(id);
        });
    });

    // Tutup modal saat Esc
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['modalDetail', 'modalHapus'].forEach(id => closeModal(id));
        }
    });
</script>

<?php include '../layouts/footer.php'; ?>