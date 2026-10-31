<?php
// === Deteksi halaman aktif berbasis folder (konsisten dengan sidebar) ===
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
$current_file = basename($_SERVER['PHP_SELF']);

$page_map = [
    'dashboard' => 'Dashboard',
    'kasir'     => 'Kasir',
    'pelanggan' => 'Pelanggan',
    'produk'    => 'Produk',
    'transaksi' => 'Transaksi',
    'laporan'   => 'Laporan',
];

$page = $page_map[$current_dir] ?? 'Dashboard';

// Subjudul khusus halaman laporan
if ($current_dir === 'laporan') {
    $sub_map = [
        'index.php'         => 'Laporan',
        'journal_umum.php'  => 'Jurnal Umum',
        'buku_besar.php'    => 'Buku Besar',
        'neraca_saldo.php'  => 'Neraca Saldo',
    ];
    $page = $sub_map[$current_file] ?? 'Laporan';
}

// Info user dari session
$username = $_SESSION['username'] ?? 'Admin';
$role     = $_SESSION['role'] ?? 'admin';
$initial  = strtoupper(mb_substr($username, 0, 1));
?>
<header class="bg-white border-b border-gray-200 shadow-sm fixed top-0 right-0 left-0 md:left-64 z-20">
    <div class="flex items-center justify-between px-4 py-3">

        <!-- Kiri: hamburger + judul halaman -->
        <div class="flex items-center gap-3">
            <button id="sidebarToggle" type="button" aria-label="Buka menu"
                class="md:hidden w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                <i class="fas fa-bars text-lg" aria-hidden="true"></i>
            </button>

            <div>
                <h2 class="text-lg font-semibold text-gray-800 leading-tight"><?= htmlspecialchars($page) ?></h2>
                <p class="text-xs text-gray-400 hidden sm:block">SIA KASIR</p>
            </div>
        </div>

        <!-- Kanan: notifikasi + user -->
        <div class="flex items-center gap-2 sm:gap-4">

            <!-- Notifikasi -->
            <button type="button" aria-label="Notifikasi"
                class="relative w-9 h-9 rounded-lg flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                <i class="fas fa-bell text-lg" aria-hidden="true"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
            </button>

            <!-- Divider -->
            <span class="hidden sm:block w-px h-6 bg-gray-200"></span>

            <!-- User -->
            <div class="flex items-center gap-2.5 pl-1">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-600/25">
                    <?= htmlspecialchars($initial) ?>
                </div>
                <div class="hidden sm:block leading-tight">
                    <p class="text-sm font-medium text-gray-800 truncate max-w-[140px]"><?= htmlspecialchars($username) ?></p>
                    <p class="text-xs text-gray-400 capitalize"><?= htmlspecialchars($role) ?></p>
                </div>
            </div>

        </div>
    </div>
</header>