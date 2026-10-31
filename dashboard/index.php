<?php
include '../config/koneksi.php';
include '../includes/helpers.php';

// =========================================================
// 1. STATISTIK CARD
// =========================================================
$total_produk     = $pdo->query("SELECT COUNT(*) FROM tbl_products")->fetchColumn();
$total_pelanggan  = $pdo->query("SELECT COUNT(*) FROM tbl_pelanggan")->fetchColumn();
$total_transaksi  = $pdo->query("SELECT COUNT(*) FROM tbl_transaction")->fetchColumn();
$total_pendapatan = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM tbl_transaction")->fetchColumn();

// =========================================================
// 2. GRAFIK PENDAPATAN 7 HARI TERAKHIR
// =========================================================
$sql_grafik = "
    SELECT DATE(tanggal) AS tgl, COALESCE(SUM(total), 0) AS total
    FROM tbl_transaction
    WHERE tanggal >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(tanggal)
";
$grafik_rows = $pdo->query($sql_grafik)->fetchAll(PDO::FETCH_KEY_PAIR);

// Bangun array 7 hari (isi 0 jika tidak ada transaksi)
$hari_id = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
$grafik  = [];
for ($i = 6; $i >= 0; $i--) {
    $tgl = date('Y-m-d', strtotime("-$i day"));
    $grafik[] = [
        'label' => $hari_id[(int)date('w', strtotime($tgl))],
        'tgl'   => date('d/m', strtotime($tgl)),
        'total' => (float)($grafik_rows[$tgl] ?? 0),
    ];
}
$max_total = max(array_column($grafik, 'total')) ?: 1;

// =========================================================
// 3. STOK MENIPIS & HABIS
// =========================================================
// tbl_products TIDAK punya kolom stok_minimal → pakai ambang tetap
$STOK_MINIMAL = 10;

// Stok habis (stok = 0)
$stok_habis = $pdo->query("
    SELECT kode_produk, nama, stok
    FROM tbl_products
    WHERE stok = 0
    ORDER BY nama ASC
    LIMIT 5
")->fetchAll();

// Stok menipis (0 < stok <= 10)
$stmt = $pdo->prepare("
    SELECT kode_produk, nama, stok
    FROM tbl_products
    WHERE stok > 0 AND stok <= :minimal
    ORDER BY stok ASC
    LIMIT 5
");
$stmt->execute(['minimal' => $STOK_MINIMAL]);
$stok_menipis = $stmt->fetchAll();

// Hitung TOTAL (bukan cuma yang ditampilkan di limit 5)
$count_habis   = (int)$pdo->query("SELECT COUNT(*) FROM tbl_products WHERE stok = 0")->fetchColumn();
$count_menipis = (int)$pdo->query("SELECT COUNT(*) FROM tbl_products WHERE stok > 0 AND stok <= {$STOK_MINIMAL}")->fetchColumn();

// Gabung jadi daftar alert (habis dulu, lalu menipis)
$stok_alerts = [];
foreach ($stok_habis as $s) {
    $stok_alerts[] = [
        'nama' => $s['nama'],
        'info' => 'Stok habis',
        'tipe' => 'habis',
    ];
}
foreach ($stok_menipis as $s) {
    $stok_alerts[] = [
        'nama' => $s['nama'],
        'info' => 'Sisa ' . $s['stok'] . ' (min. ' . $STOK_MINIMAL . ')',
        'tipe' => 'menipis',
    ];
}

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-[#eef2ff] p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500 text-sm">Selamat datang kembali! Berikut ringkasan bisnis Anda.</p>
    </div>

    <!-- ========================================================= -->
    <!-- STATISTIK CARDS -->
    <!-- ========================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1 truncate"><?= rupiah($total_pendapatan) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 shrink-0">
                    <i class="fa-solid fa-coins text-xl" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($total_transaksi) ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fa-solid fa-receipt text-xl" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-500">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($total_produk) ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 shrink-0">
                    <i class="fa-solid fa-box text-xl" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($total_pelanggan) ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 shrink-0">
                    <i class="fa-solid fa-users text-xl" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- GRAFIK + ALERT STOK -->
    <!-- ========================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Grafik Pendapatan 7 Hari -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Grafik Pendapatan</h2>
                    <p class="text-xs text-gray-400">7 hari terakhir</p>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-chart-column text-[0.7rem]" aria-hidden="true"></i>
                    <?= rupiah(array_sum(array_column($grafik, 'total'))) ?>
                </span>
            </div>

            <div class="flex items-end justify-between gap-2 sm:gap-3 h-56 pt-4">
                <?php foreach ($grafik as $g):
                    $tinggi   = $g['total'] > 0 ? max(6, round(($g['total'] / $max_total) * 180)) : 4;
                    $is_today = $g['tgl'] === date('d/m');
                ?>
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="text-[0.6rem] font-semibold text-gray-600 mb-1 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            <?= $g['total'] > 0 ? rupiah($g['total']) : '-' ?>
                        </div>
                        <div class="w-full rounded-t-md transition-all duration-300
                        <?= $is_today ? 'bg-blue-600' : 'bg-blue-400 group-hover:bg-blue-500' ?>"
                            style="height: <?= $tinggi ?>px;">
                        </div>
                        <span class="text-xs font-medium <?= $is_today ? 'text-blue-700' : 'text-gray-500' ?> mt-2">
                            <?= $g['label'] ?>
                        </span>
                        <span class="text-[0.6rem] <?= $is_today ? 'text-blue-500' : 'text-gray-400' ?>">
                            <?= $g['tgl'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Alert Stok -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Peringatan Stok</h2>
                <?php if ($count_habis + $count_menipis > 0): ?>
                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">
                        <?= $count_habis + $count_menipis ?> item
                    </span>
                <?php endif; ?>
            </div>

            <!-- Ringkasan count -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-red-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-red-600"><?= $count_habis ?></p>
                    <p class="text-xs text-red-500 font-medium mt-0.5">Habis</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-amber-600"><?= $count_menipis ?></p>
                    <p class="text-xs text-amber-500 font-medium mt-0.5">Menipis</p>
                </div>
            </div>

            <!-- Daftar alert -->
            <div class="space-y-2 flex-1 overflow-y-auto max-h-60 sidebar-scroll">
                <?php if (empty($stok_alerts)): ?>
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500 mb-3">
                            <i class="fa-solid fa-check text-lg" aria-hidden="true"></i>
                        </div>
                        <p class="text-sm text-gray-500">Semua stok aman 👍</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($stok_alerts as $a):
                        $is_habis = $a['tipe'] === 'habis';
                    ?>
                        <div class="flex items-start gap-3 p-2.5 rounded-lg <?= $is_habis ? 'bg-red-50/60 hover:bg-red-50' : 'bg-amber-50/60 hover:bg-amber-50' ?> transition-colors">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                            <?= $is_habis ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600' ?>">
                                <i class="fa-solid <?= $is_habis ? 'fa-circle-xmark' : 'fa-triangle-exclamation' ?> text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($a['nama']) ?></p>
                                <p class="text-xs <?= $is_habis ? 'text-red-600' : 'text-amber-600' ?>"><?= htmlspecialchars($a['info']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <a href="../produk/index.php"
                class="mt-4 block text-center text-sm text-blue-600 font-medium hover:text-blue-700 transition-colors">
                Kelola Produk →
            </a>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>