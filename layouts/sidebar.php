<aside class="w-64 bg-white shadow-md h-screen fixed top-0 left-0 z-30 transition-transform -translate-x-full md:translate-x-0" id="sidebar">
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-800"><i class="fas fa-store text-blue-600 mr-2"></i>SIA</h1>
    </div>
    <nav class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="../dashboard/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-50 text-gray-700 <?= (basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'bg-blue-50 text-blue-600' : '' ?>">
                    <i class="fas fa-chart-pie w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../kasir/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-50 text-gray-700 <?= (strpos($_SERVER['REQUEST_URI'], 'kasir') !== false) ? 'bg-blue-50 text-blue-600' : '' ?>">
                    <i class="fas fa-cash-register w-5 h-5 mr-3"></i>
                    <span>Kasir</span>
                </a>
            </li>
            <li>
                <a href="../produk/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-50 text-gray-700 <?= (strpos($_SERVER['REQUEST_URI'], 'produk') !== false) ? 'bg-blue-50 text-blue-600' : '' ?>">
                    <i class="fas fa-box w-5 h-5 mr-3"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="../transaksi/index.php" class="flex items-center p-2 rounded-lg hover:bg-blue-50 text-gray-700 <?= (strpos($_SERVER['REQUEST_URI'], 'transaksi') !== false) ? 'bg-blue-50 text-blue-600' : '' ?>">
                    <i class="fas fa-receipt w-5 h-5 mr-3"></i>
                    <span>Transaksi</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>