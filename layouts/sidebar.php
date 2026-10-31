<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// === Deteksi halaman aktif berbasis folder ===
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
$current_file = basename($_SERVER['PHP_SELF']);

$laporan_files  = ['journal_umum.php', 'buku_besar.php', 'neraca_saldo.php'];
$is_laporan     = $current_dir === 'laporan' && ($current_file === 'index.php' || in_array($current_file, $laporan_files));
$is_laporan_sub = $current_dir === 'laporan' && in_array($current_file, $laporan_files);

$is = fn($dir) => $current_dir === $dir;

$username = $_SESSION['username'] ?? 'User';
$role     = $_SESSION['role'] ?? 'guest';
$initial  = strtoupper(mb_substr($username, 0, 1));
?>

<!-- Overlay mobile -->
<div id="sidebar-overlay"
    class="fixed inset-0 bg-black/40 z-20 hidden md:hidden transition-opacity duration-200"></div>

<aside id="sidebar"
    aria-label="Sidebar navigasi utama"
    class="w-64 bg-white border-r border-gray-200 shadow-sm h-screen fixed top-0 left-0 z-30 transition-transform duration-300 -translate-x-full md:translate-x-0 flex flex-col">

    <!-- Tombol close (mobile) -->
    <button id="sidebar-close" type="button" aria-label="Tutup sidebar"
        class="md:hidden absolute top-3.5 right-3 w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
        <i class="fas fa-times" aria-hidden="true"></i>
    </button>

    <!-- Header / Logo -->
    <div class="p-4 border-b border-gray-200 shrink-0">
        <h1 class="text-lg font-bold text-gray-800 flex items-center tracking-wide">
            <span class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center mr-2.5 shadow-md shadow-blue-600/25">
                <i class="fas fa-store text-white text-sm" aria-hidden="true"></i>
            </span>
            <span>SIA KASIR</span>
        </h1>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-3">
        <ul class="space-y-1">

            <!-- Dashboard -->
            <li>
                <a href="../dashboard/index.php" title="Dashboard"
                    class="relative flex items-center px-3 py-2.5 rounded-lg transition-colors duration-150
                   <?= $is('dashboard')
                        ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/25'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                    <?php if ($is('dashboard')): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r"></span>
                    <?php endif; ?>
                    <i class="fas fa-chart-pie w-5 h-5 mr-3 text-center" aria-hidden="true"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Kasir -->
            <li>
                <a href="../kasir/index.php" title="Kasir"
                    class="relative flex items-center px-3 py-2.5 rounded-lg transition-colors duration-150
                   <?= $is('kasir')
                        ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/25'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                    <?php if ($is('kasir')): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r"></span>
                    <?php endif; ?>
                    <i class="fas fa-cash-register w-5 h-5 mr-3 text-center" aria-hidden="true"></i>
                    <span>Kasir</span>
                </a>
            </li>

            <!-- Pelanggan -->
            <li>
                <a href="../pelanggan/index.php" title="Pelanggan"
                    class="relative flex items-center px-3 py-2.5 rounded-lg transition-colors duration-150
                   <?= $is('pelanggan')
                        ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/25'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                    <?php if ($is('pelanggan')): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r"></span>
                    <?php endif; ?>
                    <i class="fas fa-users w-5 h-5 mr-3 text-center" aria-hidden="true"></i>
                    <span>Pelanggan</span>
                </a>
            </li>

            <!-- Produk -->
            <li>
                <a href="../produk/index.php" title="Produk"
                    class="relative flex items-center px-3 py-2.5 rounded-lg transition-colors duration-150
                   <?= $is('produk')
                        ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/25'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                    <?php if ($is('produk')): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r"></span>
                    <?php endif; ?>
                    <i class="fas fa-box w-5 h-5 mr-3 text-center" aria-hidden="true"></i>
                    <span>Produk</span>
                </a>
            </li>

            <!-- Transaksi -->
            <li>
                <a href="../transaksi/index.php" title="Transaksi"
                    class="relative flex items-center px-3 py-2.5 rounded-lg transition-colors duration-150
                   <?= $is('transaksi')
                        ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/25'
                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                    <?php if ($is('transaksi')): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r"></span>
                    <?php endif; ?>
                    <i class="fas fa-receipt w-5 h-5 mr-3 text-center" aria-hidden="true"></i>
                    <span>Transaksi</span>
                </a>
            </li>

            <!-- Laporan (dropdown) -->
            <li>
                <button type="button" id="btn-laporan"
                    aria-expanded="<?= $is_laporan_sub ? 'true' : 'false' ?>"
                    aria-controls="menu-laporan"
                    title="Laporan"
                    class="relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors duration-150
                        <?= $is_laporan
                            ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/25'
                            : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' ?>">
                    <?php if ($is_laporan): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r"></span>
                    <?php endif; ?>
                    <span class="flex items-center">
                        <i class="fas fa-newspaper w-5 h-5 mr-3 text-center" aria-hidden="true"></i>
                        <span>Laporan</span>
                    </span>
                    <i id="chevron-laporan"
                        class="fas fa-chevron-down text-xs transition-transform duration-300 <?= $is_laporan_sub ? 'rotate-180' : '' ?>"
                        aria-hidden="true"></i>
                </button>

                <!-- Submenu -->
                <div id="menu-laporan" class="submenu <?= $is_laporan_sub ? 'open' : '' ?>">
                    <div class="overflow-hidden">
                        <ul class="mt-1 ml-3 space-y-1 border-l border-gray-200 pl-3">

                            <li>
                                <a href="../laporan/journal_umum.php" title="Jurnal Umum"
                                    class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-150
                                   <?= $current_file === 'journal_umum.php'
                                        ? 'bg-blue-50 text-blue-700 font-medium border-l-2 border-blue-600'
                                        : 'text-gray-500 hover:bg-blue-50 hover:text-blue-700' ?>">
                                    <i class="fas fa-book w-4 h-4 mr-3 text-center" aria-hidden="true"></i>
                                    <span>Jurnal Umum</span>
                                </a>
                            </li>

                            <li>
                                <a href="../laporan/buku_besar.php" title="Buku Besar"
                                    class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-150
                                   <?= $current_file === 'buku_besar.php'
                                        ? 'bg-blue-50 text-blue-700 font-medium border-l-2 border-blue-600'
                                        : 'text-gray-500 hover:bg-blue-50 hover:text-blue-700' ?>">
                                    <i class="fas fa-book-open w-4 h-4 mr-3 text-center" aria-hidden="true"></i>
                                    <span>Buku Besar</span>
                                </a>
                            </li>

                            <li>
                                <a href="../laporan/neraca_saldo.php" title="Neraca Saldo"
                                    class="flex items-center px-3 py-2 rounded-lg text-sm transition-colors duration-150
                                   <?= $current_file === 'neraca_saldo.php'
                                        ? 'bg-blue-50 text-blue-700 font-medium border-l-2 border-blue-600'
                                        : 'text-gray-500 hover:bg-blue-50 hover:text-blue-700' ?>">
                                    <i class="fas fa-scale-balanced w-4 h-4 mr-3 text-center" aria-hidden="true"></i>
                                    <span>Neraca Saldo</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </li>

        </ul>
    </nav>

    <!-- Info user -->
    <div class="px-3 py-3 border-t border-gray-200 shrink-0">
        <div class="flex items-center gap-3 px-2 py-1.5">
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                <?= htmlspecialchars($initial) ?>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-gray-800 font-medium truncate"><?= htmlspecialchars($username) ?></p>
                <p class="text-xs text-gray-500 capitalize"><?= htmlspecialchars($role) ?></p>
            </div>
        </div>
    </div>

    <!-- Logout -->
    <div class="p-3 pt-0 shrink-0">
        <a href="../logout.php" title="Logout"
            class="flex items-center px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-150">
            <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-center" aria-hidden="true"></i>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</aside>

<style>
    .sidebar-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    .submenu {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 0.25s ease;
    }

    .submenu.open {
        grid-template-rows: 1fr;
    }
</style>

<script>
    (function() {
        const btn = document.getElementById('btn-laporan');
        const menu = document.getElementById('menu-laporan');
        const chevron = document.getElementById('chevron-laporan');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeBtn = document.getElementById('sidebar-close');

        // Toggle dropdown Laporan
        if (btn && menu) {
            btn.addEventListener('click', function() {
                const isOpen = menu.classList.toggle('open');
                chevron.classList.toggle('rotate-180', isOpen);
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                localStorage.setItem('laporan-open', isOpen ? '1' : '0');
            });
        }

        // Restore state dropdown
        const isLaporanPage = <?= $is_laporan_sub ? 'true' : 'false' ?>;
        if (!isLaporanPage && localStorage.getItem('laporan-open') === '1' && menu) {
            menu.classList.add('open');
            chevron.classList.add('rotate-180');
            btn?.setAttribute('aria-expanded', 'true');
        }

        // Mobile sidebar
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        closeBtn?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        window.toggleSidebar = function() {
            if (sidebar.classList.contains('-translate-x-full')) openSidebar();
            else closeSidebar();
        };
    })();
</script>