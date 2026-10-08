<aside class="w-64 bg-[#111828] shadow-md h-screen fixed top-0 left-0 z-30 transition-transform -translate-x-full md:translate-x-0 flex flex-col" id="sidebar">

    <!-- Header -->
    <div class="p-4 border-b border-gray-700 shrink-0">
        <h1 class="text-xl font-semibold text-white">
            <i class="fas fa-store text-blue-500 mr-2"></i>SIA
        </h1>
    </div>

    <?php
    // deteksi halaman aktif untuk penanda menu
    $halaman_sekarang = basename($_SERVER['PHP_SELF']);
    $di_folder_laporan = strpos($_SERVER['REQUEST_URI'], 'laporan') !== false;

    // daftar file yang termasuk di dalam menu Laporan
    $file_laporan = ['index.php', 'journal_umum.php', 'buku_besar.php', 'neraca_saldo.php'];

    // cek apakah halaman ini bagian dari menu Laporan
    $aktif_laporan = $di_folder_laporan && in_array($halaman_sekarang, $file_laporan);

    // cek apakah salah satu submenu laporan sedang dibuka
    $sub_aktif = in_array($halaman_sekarang, ['journal_umum.php', 'buku_besar.php', 'neraca_saldo.php'])
        && $di_folder_laporan;
    ?>

    <!-- Menu Navigasi -->
    <nav class="flex-1 overflow-y-auto p-4">
        <ul class="space-y-2">

            <!-- Menu Dashboard -->
            <li>
                <a href="../dashboard/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'bg-blue-500' : '' ?>">
                    <i class="fas fa-chart-pie w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Menu Kasir -->
            <li>
                <a href="../kasir/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'kasir') !== false) ? 'bg-blue-500' : '' ?>">
                    <i class="fas fa-cash-register w-5 h-5 mr-3"></i>
                    <span>Kasir</span>
                </a>
            </li>

            <!-- Menu Pelanggan -->
            <li>
                <a href="../pelanggan/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'pelanggan') !== false) ? 'bg-blue-500' : '' ?>">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span>Pelanggan</span>
                </a>
            </li>

            <!-- Menu Produk -->
            <li>
                <a href="../produk/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'produk') !== false) ? 'bg-blue-500' : '' ?>">
                    <i class="fas fa-box w-5 h-5 mr-3"></i>
                    <span>Produk</span>
                </a>
            </li>

            <!-- Menu Transaksi -->
            <li>
                <a href="../transaksi/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'transaksi') !== false) ? 'bg-blue-500' : '' ?>">
                    <i class="fas fa-receipt w-5 h-5 mr-3"></i>
                    <span>Transaksi</span>
                </a>
            </li>

            <!-- Menu Laporan dengan dropdown -->
            <li>

                <!-- Tombol induk untuk buka/tutup dropdown -->
                <button type="button" id="btn-laporan"
                    class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-blue-400 text-white <?= $aktif_laporan ? 'bg-blue-500' : '' ?>">
                    <span class="flex items-center">
                        <i class="fas fa-newspaper w-5 h-5 mr-3"></i>
                        <span>Laporan</span>
                    </span>
                    <!-- Ikon chevron berputar saat dropdown terbuka -->
                    <i id="chevron-laporan" class="fas fa-chevron-down text-xs transition-transform duration-200 <?= $sub_aktif ? 'rotate-180' : '' ?>"></i>
                </button>

                <!-- Isi dropdown submenu laporan -->
                <ul id="menu-laporan" class="mt-1 ml-4 space-y-1 border-l border-gray-700 pl-3 <?= $sub_aktif ? '' : 'hidden' ?>">

                    <!-- Submenu Jurnal Umum -->
                    <li>
                        <a href="../laporan/journal_umum.php"
                            class="flex items-center p-2 rounded-lg text-sm text-gray-300 hover:bg-blue-400 hover:text-white <?= $sub_aktif && $halaman_sekarang === 'journal_umum.php' ? 'bg-blue-500 text-white' : '' ?>">
                            <i class="fas fa-book w-4 h-4 mr-3"></i>
                            <span>Jurnal Umum</span>
                        </a>
                    </li>

                    <!-- Submenu Buku Besar -->
                    <li>
                        <a href="../laporan/buku_besar.php"
                            class="flex items-center p-2 rounded-lg text-sm text-gray-300 hover:bg-blue-400 hover:text-white <?= $sub_aktif && $halaman_sekarang === 'buku_besar.php' ? 'bg-blue-500 text-white' : '' ?>">
                            <i class="fas fa-book-open w-4 h-4 mr-3"></i>
                            <span>Buku Besar</span>
                        </a>
                    </li>

                    <!-- Submenu Neraca Saldo -->
                    <li>
                        <a href="../laporan/neraca_saldo.php"
                            class="flex items-center p-2 rounded-lg text-sm text-gray-300 hover:bg-blue-400 hover:text-white <?= $sub_aktif && $halaman_sekarang === 'neraca_saldo.php' ? 'bg-blue-500 text-white' : '' ?>">
                            <i class="fas fa-scale-balanced w-4 h-4 mr-3"></i>
                            <span>Neraca Saldo</span>
                        </a>
                    </li>

                </ul>
            </li>

        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-700 shrink-0">
        <a href="../logout.php" class="flex items-center p-2 rounded-lg hover:bg-red-900/30 text-red-500 transition-colors">
            <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</aside>

<script>
    // buka atau tutup dropdown menu laporan saat tombol diklik
    document.getElementById('btn-laporan').addEventListener('click', function() {
        var menu = document.getElementById('menu-laporan');
        var chevron = document.getElementById('chevron-laporan');

        // toggle tampil atau sembunyi
        menu.classList.toggle('hidden');

        // putar ikon chevron sebagai penanda status
        chevron.classList.toggle('rotate-180');
    });
</script>