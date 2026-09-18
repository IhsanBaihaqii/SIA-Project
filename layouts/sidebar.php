<aside class="w-64 bg-[#111828] shadow-md h-screen fixed top-0 left-0 z-30 transition-transform -translate-x-full md:translate-x-0 flex flex-col" id="sidebar">
    <!-- Header -->
    <div class="p-4 border-b border-gray-700 shrink-0">
        <h1 class="text-xl font-semibold text-white">
            <i class="fas fa-store text-blue-500 mr-2"></i>SIA
        </h1>
    </div>

    <!-- Menu Navigasi (flex-1 agar mendorong logout ke bawah) -->
    <nav class="flex-1 overflow-y-auto p-4">
        <ul class="space-y-2">
            <li>
                <a href="../dashboard/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'bg-blue-500 text-blue-600' : '' ?>">
                    <i class="fas fa-chart-pie w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../kasir/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'kasir') !== false) ? 'bg-blue-500 text-blue-600' : '' ?>">
                    <i class="fas fa-cash-register w-5 h-5 mr-3"></i>
                    <span>Kasir</span>
                </a>
            </li>
            <li>
                <a href="../pelanggan/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'pelanggan') !== false) ? 'bg-blue-500 text-blue-600' : '' ?>">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span>Pelanggan</span>
                </a>
            </li>
            <li>
                <a href="../produk/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'produk') !== false) ? 'bg-blue-500 text-blue-600' : '' ?>">
                    <i class="fas fa-box w-5 h-5 mr-3"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="../transaksi/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'transaksi') !== false) ? 'bg-blue-500 text-blue-600' : '' ?>">
                    <i class="fas fa-receipt w-5 h-5 mr-3"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li>
                <a href="../laporan/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-400 text-white <?= (strpos($_SERVER['REQUEST_URI'], 'laporan') !== false) ? 'bg-blue-500 text-blue-600' : '' ?>">
                    <i class="fas fa-newspaper w-5 h-5 mr-3"></i>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout (selalu di bawah, dengan warna merah) -->
    <div class="p-4 border-t border-gray-700 shrink-0">
        <a href="../logout.php" class="flex items-center p-2 rounded-lg hover:bg-red-900/30 text-red-500 transition-colors">
            <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</aside>