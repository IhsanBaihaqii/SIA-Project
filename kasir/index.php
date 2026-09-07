<?php
include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kasir</h1>
        <p class="text-gray-500 text-sm"><b class="text-red-400 text-sm">(MASIH TAMPILANNYA DOANG, BELUM BISA DI APAKAN, MASIH PAKAI DATA DUMMY)</b></p>
    </div>

    <!-- Grid dua kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Kolom Kiri: Tambah Produk (3/5) -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Form Tambah Produk -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah ke Keranjang</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk</label>
                        <select class="w-full border border-gray-200 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-green-500 outline-none text-sm">
                            <option>-- Pilih Produk --</option>
                            <option>Beras Premium 5kg</option>
                            <option>Minyak Goreng 2L</option>
                            <option>Gula Pasir 1kg</option>
                            <option>Telur Ayam 1 tray</option>
                            <option>Susu UHT 1L</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" value="1" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-green-500 outline-none text-sm">
                    </div>
                    <div class="flex items-end">
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> Tambah
                        </button>
                    </div>
                </div>
                <!-- Daftar produk cepat (opsional) -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-2">Produk populer:</p>
                    <div class="flex flex-wrap gap-2">
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-xs text-gray-700 transition-colors">Beras 5kg</button>
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-xs text-gray-700 transition-colors">Minyak 2L</button>
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-xs text-gray-700 transition-colors">Gula 1kg</button>
                        <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-xs text-gray-700 transition-colors">Telur 1 tray</button>
                    </div>
                </div>
            </div>

            <!-- Tabel Keranjang (di kiri bawah) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Keranjang Belanja</h2>
                    <span class="text-sm text-gray-500">3 item</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nama Produk</th>
                                <th class="px-6 py-3 font-semibold text-center">Jumlah</th>
                                <th class="px-6 py-3 font-semibold text-right">Subtotal</th>
                                <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-6 py-4">Beras Premium 5kg</td>
                                <td class="px-6 py-4 text-center">2</td>
                                <td class="px-6 py-4 text-right font-medium text-green-600">Rp 130.000</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-red-500 hover:text-red-700 transition-colors">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4">Minyak Goreng 2L</td>
                                <td class="px-6 py-4 text-center">1</td>
                                <td class="px-6 py-4 text-right font-medium text-green-600">Rp 45.000</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-red-500 hover:text-red-700 transition-colors">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4">Gula Pasir 1kg</td>
                                <td class="px-6 py-4 text-center">3</td>
                                <td class="px-6 py-4 text-right font-medium text-green-600">Rp 51.000</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-red-500 hover:text-red-700 transition-colors">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan & Pembayaran (2/5) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ringkasan Total -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rp 226.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Diskon</span>
                        <span class="font-medium text-red-500">-Rp 0</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                        <span class="text-base font-bold text-gray-800">Grand Total</span>
                        <span class="text-lg font-bold text-green-600">Rp 226.000</span>
                    </div>
                </div>
            </div>

            <!-- Pembayaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h2>
                <div class="space-y-4">
                    <!-- Pilih Pelanggan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                        <select class="w-full border border-gray-200 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-green-500 outline-none text-sm">
                            <option>-- Pilih Pelanggan --</option>
                            <option>Andi Pratama</option>
                            <option>Budi Santoso</option>
                            <option>Cindy Wijaya</option>
                            <option>Dewi Lestari</option>
                            <option>Umum (Non-member)</option>
                        </select>
                    </div>

                    <!-- Uang Bayar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Uang Dibayar</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" placeholder="0" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 outline-none text-sm">
                        </div>
                    </div>

                    <!-- Kembalian -->
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Kembalian</span>
                            <span class="text-xl font-bold text-green-700">Rp 0</span>
                        </div>
                    </div>

                    <!-- Tombol Proses -->
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold shadow-sm transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>