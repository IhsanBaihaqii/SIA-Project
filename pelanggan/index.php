<?php
include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <!-- Header & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Pelanggan <span class="text-red-400 text-sm">(MASIH TAMPILAN, BELUM AKU HUBUNGKAN KE DATABASE)</span></h1>
            <p class="text-gray-500 text-sm">Kelola semua data pelanggan Anda di sini.</p>
        </div>
        <button class="mt-3 sm:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Pelanggan
        </button>
    </div>

    <!-- Filter / Pencarian -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari nama, no HP, atau alamat..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
            </div>
        </div>
        <div class="flex gap-2">
            <button class="border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Nomor HP</th>
                        <th class="px-6 py-4 font-semibold">Alamat</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Data dummy 1 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">1</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shrink-0">A</div>
                                <span class="font-medium text-gray-800">Andi Pratama</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <i class="fa-solid fa-phone text-gray-400 text-xs mr-2"></i>0812-3456-7890
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="Jl. Merdeka No. 12, Jakarta Pusat">
                            <i class="fa-solid fa-location-dot text-gray-400 text-xs mr-2"></i>Jl. Merdeka No. 12, Jakarta Pusat
                        </td>
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

                    <!-- Data dummy 2 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">2</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shrink-0">B</div>
                                <span class="font-medium text-gray-800">Budi Santoso</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <i class="fa-solid fa-phone text-gray-400 text-xs mr-2"></i>0857-9876-5432
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="Jl. Sudirman No. 45, Bandung">
                            <i class="fa-solid fa-location-dot text-gray-400 text-xs mr-2"></i>Jl. Sudirman No. 45, Bandung
                        </td>
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
            <span class="text-gray-500">Menampilkan 2 dari 2 pelanggan</span>
            <div class="flex items-center gap-1">
                <button class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>Sebelumnya</button>
                <button class="px-3 py-1 bg-blue-600 text-white rounded-lg">1</button>
                <button class="px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>Selanjutnya</button>
            </div>
        </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>