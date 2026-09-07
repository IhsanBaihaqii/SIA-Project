<?php
    include '../config/koneksi.php';

    $query = "SELECT COUNT(*) AS total_produk FROM tbl_products;";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $total_produk = $stmt->fetchColumn();

    // Sertakan layout
    include '../layouts/header.php';
    include '../layouts/sidebar.php';
    include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard  <span class="text-red-400 text-sm">(DATABASE YANG BARU AKU HUBUNGKAN BARU TOTAL_PRODUK, JUMLAH PRODUK SUDAH SESUAI DENGAN DATABASE)</span></h1>
        <p class="text-gray-500 text-sm">Selamat datang kembali! Berikut ringkasan bisnis Anda hari ini.</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Pendapatan -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">Rp 0</p>
                    <span class="inline-flex items-center text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full mt-2">
                        <i class="fa-solid fa-arrow-up mr-1"></i> 12.5%
                    </span>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                    <i class="fa-solid fa-coins text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">0</p>
                    <span class="inline-flex items-center text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full mt-2">
                        <i class="fa-solid fa-arrow-up mr-1"></i> 8.2%
                    </span>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-receipt text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo $total_produk; ?></p>
                    <span class="inline-flex items-center text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full mt-2">
                        <i class="fa-solid fa-arrow-up mr-1"></i> 3.1%
                    </span>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="fa-solid fa-box text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pelanggan (Tambahan) -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pelanggan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">0</p>
                    <span class="inline-flex items-center text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded-full mt-2">
                        <i class="fa-solid fa-arrow-up mr-1"></i> 5.7%
                    </span>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik & Aktivitas Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Pendapatan (2 kolom) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Grafik Pendapatan</h2>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white text-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                    <option>3 Bulan</option>
                </select>
            </div>
            <!-- Placeholder Chart -->
            <div class="h-52 flex items-end justify-between gap-2 pt-4">
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-green-500 rounded-t-md" style="height: 40px;"></div>
                    <span class="text-xs text-gray-500 mt-2">Sen</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-green-500 rounded-t-md" style="height: 65px;"></div>
                    <span class="text-xs text-gray-500 mt-2">Sel</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-green-500 rounded-t-md" style="height: 30px;"></div>
                    <span class="text-xs text-gray-500 mt-2">Rab</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-green-500 rounded-t-md" style="height: 80px;"></div>
                    <span class="text-xs text-gray-500 mt-2">Kam</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-green-500 rounded-t-md" style="height: 55px;"></div>
                    <span class="text-xs text-gray-500 mt-2">Jum</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-green-500 rounded-t-md" style="height: 90px;"></div>
                    <span class="text-xs text-gray-500 mt-2">Sab</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-green-500 rounded-t-md" style="height: 70px;"></div>
                    <span class="text-xs text-gray-500 mt-2">Min</span>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 flex-shrink-0">
                        <i class="fa-solid fa-cart-plus text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Transaksi baru</p>
                        <p class="text-xs text-gray-500">2 menit yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Pelanggan baru</p>
                        <p class="text-xs text-gray-500">15 menit yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 flex-shrink-0">
                        <i class="fa-solid fa-box-open text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Stok produk diperbarui</p>
                        <p class="text-xs text-gray-500">1 jam yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 flex-shrink-0">
                        <i class="fa-solid fa-tag text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Promo baru ditambahkan</p>
                        <p class="text-xs text-gray-500">3 jam yang lalu</p>
                    </div>
                </div>
            </div>
            <button class="mt-4 w-full text-center text-sm text-green-600 font-medium hover:text-green-700 transition-colors">
                Lihat Semua Aktivitas →
            </button>
        </div>
    </div>
</main>

<?php
include '../layouts/footer.php';
?>