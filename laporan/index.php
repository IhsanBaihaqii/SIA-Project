<?php
include '../config/koneksi.php';
include '../includes/helpers.php';

// =========
// 1. FILTER PERIODE
// =========
$mode = $_GET['mode'] ?? 'bulan';
if (!in_array($mode, ['7d', 'bulan', 'single', 'range'], true)) {
    $mode = 'bulan';
}

$tanggal_mulai = null;
$tanggal_akhir = null;
$label_periode = '';

if ($mode === '7d') {
    $tanggal_mulai = date('Y-m-d', strtotime('-6 day'));
    $tanggal_akhir = date('Y-m-d');
    $label_periode = '7 Hari Terakhir';
} elseif ($mode === 'bulan') {
    $tanggal_mulai = date('Y-m-01');
    $tanggal_akhir = date('Y-m-t');
    $label_periode = date('F Y');
} elseif ($mode === 'single') {
    $tgl = $_GET['tgl'] ?? date('Y-m-d');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)) $tgl = date('Y-m-d');
    $tanggal_mulai = $tgl;
    $tanggal_akhir = $tgl;
    $label_periode = date('d M Y', strtotime($tgl));
} else { // range
    $dari = $_GET['dari'] ?? date('Y-m-01');
    $ke   = $_GET['ke']   ?? date('Y-m-t');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dari)) $dari = date('Y-m-01');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $ke))   $ke   = date('Y-m-t');
    if (strtotime($dari) > strtotime($ke)) [$dari, $ke] = [$ke, $dari];
    $tanggal_mulai = $dari;
    $tanggal_akhir = $ke;
    $label_periode = date('d M', strtotime($dari)) . ' – ' . date('d M Y', strtotime($ke));
}

$params = [':dari' => $tanggal_mulai, ':ke' => $tanggal_akhir];

// =========
// 2. SUMMARY STATS
// =========
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) AS total_transaksi,
        COALESCE(SUM(total), 0) AS total_pendapatan,
        COALESCE(AVG(total), 0) AS rata_rata,
        COALESCE(MAX(total), 0) AS max_transaksi
    FROM tbl_transaction
    WHERE tanggal BETWEEN :dari AND :ke
");
$stmt->execute($params);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Total item terjual (dari detail)
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(d.qty), 0) AS total_item
    FROM tbl_transaction_details d
    JOIN tbl_transaction t ON t.id_transaction = d.id_transaction
    WHERE t.tanggal BETWEEN :dari AND :ke
");
$stmt->execute($params);
$total_item = (int)$stmt->fetchColumn();

// =========
// 3. PRODUK TERJUAL (TOP 10)
// =========
$stmt = $pdo->prepare("
    SELECT 
        p.kode_produk,
        p.nama,
        p.kategori,
        p.satuan,
        SUM(d.qty) AS total_qty,
        SUM(d.subtotal) AS total_pendapatan,
        COUNT(DISTINCT d.id_transaction) AS jumlah_transaksi
    FROM tbl_transaction_details d
    JOIN tbl_transaction t ON t.id_transaction = d.id_transaction
    JOIN tbl_products p ON p.kode_produk = d.kode_produk
    WHERE t.tanggal BETWEEN :dari AND :ke
    GROUP BY p.kode_produk, p.nama, p.kategori, p.satuan
    ORDER BY total_qty DESC
    LIMIT 10
");
$stmt->execute($params);
$produk_terjual = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Produk terjual untuk grafik (top 5)
$grafik_produk = array_slice($produk_terjual, 0, 5);
$max_qty = !empty($grafik_produk) ? max(array_column($grafik_produk, 'total_qty')) : 1;

// =========
// 4. TRANSAKSI TERBARU (10)
// =========
$stmt = $pdo->prepare("
    SELECT 
        t.id_transaction, 
        t.tanggal, 
        t.total,
        COALESCE(p.nama, 'Umum') AS pelanggan,
        (SELECT COUNT(*) FROM tbl_transaction_details d WHERE d.id_transaction = t.id_transaction) AS jml_item
    FROM tbl_transaction t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    WHERE t.tanggal BETWEEN :dari AND :ke
    ORDER BY t.id_transaction DESC
    LIMIT 10
");
$stmt->execute($params);
$transaksi_terbaru = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =========
// 5. GRAFIK PENDAPATAN HARIAN
// =========
$stmt = $pdo->prepare("
    SELECT DATE(tanggal) AS tgl, COALESCE(SUM(total), 0) AS total
    FROM tbl_transaction
    WHERE tanggal BETWEEN :dari AND :ke
    GROUP BY DATE(tanggal)
    ORDER BY tgl ASC
");
$stmt->execute($params);
$grafik_rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Bangun array tanggal
$hari_id = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
$grafik  = [];
$cur = strtotime($tanggal_mulai);
$end = strtotime($tanggal_akhir);

// Batasi 31 titik agar grafik tidak terlalu padat
$days = (int)round(($end - $cur) / 86400) + 1;
if ($days > 31) {
    $cur = strtotime('-30 day', $end);
}

while ($cur <= $end) {
    $tgl = date('Y-m-d', $cur);
    $grafik[] = [
        'label' => $hari_id[(int)date('w', $cur)],
        'tgl'   => date('d/m', $cur),
        'total' => (float)($grafik_rows[$tgl] ?? 0),
    ];
    $cur = strtotime('+1 day', $cur);
}
$max_total = max(array_column($grafik, 'total')) ?: 1;

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-[#eef2ff] p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h1>
        <p class="text-gray-500 text-sm">Ringkasan pendapatan, produk terjual, dan transaksi.</p>
    </div>

    <!-- FILTER PERIODE -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mr-2">Periode:</span>

            <?php
            $tabs = [
                '7d'    => ['label' => '7 Hari',    'icon' => 'fa-calendar-day'],
                'bulan' => ['label' => 'Bulan Ini', 'icon' => 'fa-calendar'],
            ];
            foreach ($tabs as $key => $t):
            ?>
                <a href="?mode=<?= $key ?>"
                    class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all
                   <?= $mode === $key
                        ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                    <i class="fa-solid <?= $t['icon'] ?> text-xs mr-1" aria-hidden="true"></i>
                    <?= $t['label'] ?>
                </a>
            <?php endforeach; ?>

            <a href="?mode=single&tgl=<?= date('Y-m-d') ?>"
                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all
               <?= $mode === 'single'
                    ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25'
                    : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                <i class="fa-solid fa-calendar-check text-xs mr-1" aria-hidden="true"></i> 1 Tanggal
            </a>

            <a href="?mode=range&dari=<?= date('Y-m-01') ?>&ke=<?= date('Y-m-t') ?>"
                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all
               <?= $mode === 'range'
                    ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25'
                    : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                <i class="fa-solid fa-calendar-days text-xs mr-1" aria-hidden="true"></i> Rentang
            </a>
        </div>

        <?php if ($mode === 'single'): ?>
            <form method="GET" class="flex flex-wrap items-end gap-3 pt-3 border-t border-gray-100">
                <input type="hidden" name="mode" value="single">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Pilih Tanggal</label>
                    <input type="date" name="tgl" value="<?= htmlspecialchars($tanggal_mulai) ?>"
                        class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-magnifying-glass mr-1" aria-hidden="true"></i> Tampilkan
                </button>
                <span class="text-sm text-gray-500 ml-auto">
                    <i class="fa-solid fa-info-circle text-blue-400 mr-1" aria-hidden="true"></i>
                    Menampilkan: <strong class="text-gray-700"><?= $label_periode ?></strong>
                </span>
            </form>

        <?php elseif ($mode === 'range'): ?>
            <form method="GET" class="flex flex-wrap items-end gap-3 pt-3 border-t border-gray-100">
                <input type="hidden" name="mode" value="range">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="dari" value="<?= htmlspecialchars($tanggal_mulai) ?>"
                        class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="ke" value="<?= htmlspecialchars($tanggal_akhir) ?>"
                        class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <i class="fa-solid fa-magnifying-glass mr-1" aria-hidden="true"></i> Tampilkan
                </button>
                <span class="text-sm text-gray-500 ml-auto">
                    <i class="fa-solid fa-info-circle text-blue-400 mr-1" aria-hidden="true"></i>
                    Menampilkan: <strong class="text-gray-700"><?= $label_periode ?></strong>
                </span>
            </form>

        <?php else: ?>
            <p class="text-sm text-gray-500 pt-3 border-t border-gray-100">
                <i class="fa-solid fa-info-circle text-blue-400 mr-1" aria-hidden="true"></i>
                Menampilkan: <strong class="text-gray-700"><?= $label_periode ?></strong>
            </p>
        <?php endif; ?>
    </div>

    <!-- SUMMARY CARDS (4) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Total Pendapatan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-600 mt-1 truncate" title="<?= rupiah($stats['total_pendapatan']) ?>">
                        <?= rupiah($stats['total_pendapatan']) ?>
                    </p>
                    <p class="text-xs text-gray-400 mt-1"><?= $label_periode ?></p>
                </div>
                <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center text-green-600 shrink-0">
                    <i class="fa-solid fa-coins" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1"><?= number_format($stats['total_transaksi']) ?></p>
                    <p class="text-xs text-gray-400 mt-1"><?= $label_periode ?></p>
                </div>
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <!-- Produk Terjual (item) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500">Item Terjual</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1"><?= number_format($total_item) ?></p>
                    <p class="text-xs text-gray-400 mt-1">Total unit terjual</p>
                </div>
                <div class="w-11 h-11 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 shrink-0">
                    <i class="fa-solid fa-box" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <!-- Rata-rata Transaksi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-500">Rata-rata</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1 truncate" title="<?= rupiah($stats['rata_rata']) ?>">
                        <?= rupiah($stats['rata_rata']) ?>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Per transaksi</p>
                </div>
                <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 shrink-0">
                    <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAFIK PENDAPATAN HARIAN + PRODUK TERJUAL -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        <!-- Grafik Pendapatan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Pendapatan Harian</h2>
                    <p class="text-xs text-gray-400"><?= $label_periode ?></p>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">
                    <?= rupiah(array_sum(array_column($grafik, 'total'))) ?>
                </span>
            </div>

            <?php if (count($grafik) === 1): ?>
                <?php $g = $grafik[0];
                $tinggi = $g['total'] > 0 ? min(180, max(20, round(($g['total'] / $max_total) * 150))) : 8; ?>
                <div class="flex flex-col items-center justify-center h-48">
                    <p class="text-xl font-bold text-blue-600 mb-2"><?= rupiah($g['total']) ?></p>
                    <div class="w-24 bg-blue-500 rounded-t-lg" style="height: <?= $tinggi ?>px;"></div>
                    <p class="text-xs text-gray-500 mt-2"><?= $g['label'] ?> · <?= $g['tgl'] ?></p>
                </div>
            <?php else: ?>
                <div class="flex items-end justify-between gap-1 h-48 pt-4 overflow-x-auto">
                    <?php foreach ($grafik as $g):
                        $tinggi = $g['total'] > 0 ? max(6, round(($g['total'] / $max_total) * 150)) : 4;
                        $is_today = $g['tgl'] === date('d/m');
                    ?>
                        <div class="flex-1 min-w-[14px] flex flex-col items-center group">
                            <div class="text-[0.55rem] font-semibold text-gray-600 mb-0.5 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                <?= $g['total'] > 0 ? rupiah($g['total']) : '-' ?>
                            </div>
                            <div class="w-full rounded-t-md transition-all
                                <?= $is_today ? 'bg-blue-600' : 'bg-blue-400 group-hover:bg-blue-500' ?>"
                                style="height: <?= $tinggi ?>px;">
                            </div>
                            <span class="text-[0.55rem] font-medium <?= $is_today ? 'text-blue-700' : 'text-gray-500' ?> mt-1">
                                <?= $g['tgl'] ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Produk Terjual (Top 5) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Produk Terlaris</h2>
                    <p class="text-xs text-gray-400">Top 5 berdasarkan qty</p>
                </div>
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">
                    <?= count($produk_terjual) ?> produk
                </span>
            </div>

            <?php if (empty($grafik_produk)): ?>
                <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                    <i class="fa-solid fa-box-open text-3xl mb-2" aria-hidden="true"></i>
                    <p class="text-sm">Belum ada produk terjual</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($grafik_produk as $i => $p):
                        $persen = $max_qty > 0 ? round(($p['total_qty'] / $max_qty) * 100) : 0;
                    ?>
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <span class="w-5 h-5 bg-blue-100 text-blue-700 rounded-full text-[0.65rem] font-bold flex items-center justify-center shrink-0">
                                        <?= $i + 1 ?>
                                    </span>
                                    <span class="font-medium text-gray-800 truncate"><?= htmlspecialchars($p['nama']) ?></span>
                                </div>
                                <span class="text-xs font-semibold text-blue-600 ml-2 shrink-0">
                                    <?= number_format($p['total_qty']) ?> <?= htmlspecialchars($p['satuan']) ?>
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-500 h-2 rounded-full transition-all duration-500"
                                    style="width: <?= $persen ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TABEL PRODUK TERJUAL (LENGKAP) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Detail Produk Terjual</h2>
                <p class="text-xs text-gray-400"><?= $label_periode ?></p>
            </div>
            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full">
                <?= count($produk_terjual) ?> produk
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Kode</th>
                        <th class="px-6 py-3 font-semibold">Produk</th>
                        <th class="px-6 py-3 font-semibold">Kategori</th>
                        <th class="px-6 py-3 font-semibold text-center">Qty Terjual</th>
                        <th class="px-6 py-3 font-semibold text-center">Transaksi</th>
                        <th class="px-6 py-3 font-semibold text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($produk_terjual)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <i class="fa-solid fa-box-open text-3xl" aria-hidden="true"></i>
                                    <p class="text-sm">Belum ada produk terjual di periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produk_terjual as $p): ?>
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-xs font-mono font-medium">
                                        <?= htmlspecialchars($p['kode_produk']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 font-medium text-gray-800"><?= htmlspecialchars($p['nama']) ?></td>
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                        <?= htmlspecialchars($p['kategori']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-center font-semibold text-gray-800">
                                    <?= number_format($p['total_qty']) ?> <?= htmlspecialchars($p['satuan']) ?>
                                </td>
                                <td class="px-6 py-3.5 text-center text-gray-600">
                                    <?= number_format($p['jumlah_transaksi']) ?>x
                                </td>
                                <td class="px-6 py-3.5 text-right font-semibold text-green-600 whitespace-nowrap">
                                    <?= rupiah($p['total_pendapatan']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($produk_terjual)): ?>
                    <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-semibold text-gray-700">Total:</td>
                            <td class="px-6 py-3 text-center font-bold text-gray-800">
                                <?= number_format(array_sum(array_column($produk_terjual, 'total_qty'))) ?>
                            </td>
                            <td class="px-6 py-3 text-center font-bold text-gray-800">
                                <?= number_format(array_sum(array_column($produk_terjual, 'jumlah_transaksi'))) ?>x
                            </td>
                            <td class="px-6 py-3 text-right font-bold text-green-600 whitespace-nowrap">
                                <?= rupiah(array_sum(array_column($produk_terjual, 'total_pendapatan'))) ?>
                            </td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <!-- TRANSAKSI TERBARU -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h2>
                <p class="text-xs text-gray-400">10 transaksi terakhir di periode ini</p>
            </div>
            <a href="../transaksi/index.php?periode=range"
                class="text-xs font-medium text-blue-600 hover:text-blue-700 inline-flex items-center gap-1">
                Lihat Semua <i class="fa-solid fa-arrow-right text-[0.6rem]" aria-hidden="true"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-semibold">ID</th>
                        <th class="px-6 py-3 font-semibold">Pelanggan</th>
                        <th class="px-6 py-3 font-semibold">Tanggal</th>
                        <th class="px-6 py-3 font-semibold text-center">Item</th>
                        <th class="px-6 py-3 font-semibold text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($transaksi_terbaru)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                <i class="fa-solid fa-receipt text-2xl mb-2 block" aria-hidden="true"></i>
                                <p class="text-sm">Belum ada transaksi.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transaksi_terbaru as $t): ?>
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-xs font-mono font-medium">
                                        #TRX-<?= str_pad($t['id_transaction'], 4, '0', STR_PAD_LEFT) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 font-medium text-gray-800"><?= htmlspecialchars($t['pelanggan']) ?></td>
                                <td class="px-6 py-3.5 text-gray-600 whitespace-nowrap">
                                    <?= date('d M Y', strtotime($t['tanggal'])) ?>
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                        <?= (int)$t['jml_item'] ?> item
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right font-semibold text-blue-600 whitespace-nowrap">
                                    <?= rupiah($t['total']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>