<?php
include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <!-- Header & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi <span class="text-red-400 text-sm">(MASIH TAMPILAN, BELUM AKU HUBUNGKAN KE DATABASE)</span></h1>
            <p class="text-gray-500 text-sm">Kelola semua data transaksi Anda di sini.</p>
        </div>
        <button class="mt-3 sm:mt-0 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Transaksi
        </button>
    </div>

    <!-- Filter / Pencarian (opsional) -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari transaksi..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none text-sm">
            </div>
        </div>
        <div class="flex gap-2">
            <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-green-500 outline-none">
                <option>Semua Status</option>
                <option>Selesai</option>
                <option>Pending</option>
                <option>Batal</option>
            </select>
            <button class="border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                        <th class="px-6 py-4 font-semibold">Nama Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Tanggal Transaksi</th>
                        <th class="px-6 py-4 font-semibold text-right">Total Harga</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Contoh data 1 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">#TRX-001</td>
                        <td class="px-6 py-4">Andi Pratama</td>
                        <td class="px-6 py-4">12 Sep 2026, 14:30</td>
                        <td class="px-6 py-4 text-right font-semibold text-green-600">Rp 450.000</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Contoh data 2 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">#TRX-002</td>
                        <td class="px-6 py-4">Budi Santoso</td>
                        <td class="px-6 py-4">12 Sep 2026, 11:15</td>
                        <td class="px-6 py-4 text-right font-semibold text-green-600">Rp 275.000</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Contoh data 3 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">#TRX-003</td>
                        <td class="px-6 py-4">Cindy Wijaya</td>
                        <td class="px-6 py-4">11 Sep 2026, 09:45</td>
                        <td class="px-6 py-4 text-right font-semibold text-green-600">Rp 1.200.000</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Contoh data 4 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">#TRX-004</td>
                        <td class="px-6 py-4">Dewi Lestari</td>
                        <td class="px-6 py-4">10 Sep 2026, 16:20</td>
                        <td class="px-6 py-4 text-right font-semibold text-green-600">Rp 85.000</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Footer tabel: info & paginasi -->
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
            <span class="text-gray-500">Menampilkan 4 dari 24 transaksi</span>
            <div class="flex items-center gap-1">
                <button class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>Sebelumnya</button>
                <button class="px-3 py-1 bg-green-600 text-white rounded-lg">1</button>
                <button class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50">2</button>
                <button class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50">3</button>
                <span class="px-2 text-gray-400">...</span>
                <button class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50">8</button>
                <button class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50">Selanjutnya</button>
            </div>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>