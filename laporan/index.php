<?php
include '../config/koneksi.php';
include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <!-- Container utama halaman jurnal umum -->
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">

        <!-- Header halaman: judul dan tombol aksi -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Jurnal Umum</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Catatan transaksi akuntansi periode berjalan</p>
            </div>
            <div class="flex gap-2">
                <!-- Tombol export jurnal -->
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-medium">
                    <i class="fa-solid fa-file-export"></i>
                    <span>Export</span>
                </button>
                <!-- Tombol tambah transaksi baru -->
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium">
                    <i class="fa-solid fa-plus"></i>
                    <span>Transaksi Baru</span>
                </button>
            </div>
        </div>

        <!-- Kartu ringkasan: total debit, total kredit, selisih, jumlah transaksi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Kartu total debit -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Debit</p>
                        <p class="text-xl font-semibold text-gray-900 mt-1">Rp 12.450.000</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fa-solid fa-arrow-down text-blue-600"></i>
                    </div>
                </div>
            </div>
            <!-- Kartu total kredit -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Kredit</p>
                        <p class="text-xl font-semibold text-gray-900 mt-1">Rp 12.450.000</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class="fa-solid fa-arrow-up text-green-600"></i>
                    </div>
                </div>
            </div>
            <!-- Kartu selisih debit kredit -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Selisih</p>
                        <p class="text-xl font-semibold text-gray-900 mt-1">Rp 0</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <i class="fa-solid fa-scale-balanced text-purple-600"></i>
                    </div>
                </div>
            </div>
            <!-- Kartu jumlah transaksi -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Transaksi</p>
                        <p class="text-xl font-semibold text-gray-900 mt-1">24</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter bar: pencarian, tanggal, akun -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Input pencarian keterangan atau nomor jurnal -->
                <div class="md:col-span-2 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Cari keterangan atau no. jurnal..." class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <!-- Input filter tanggal -->
                <div>
                    <input type="date" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <!-- Dropdown filter akun -->
                <div>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>Semua Akun</option>
                        <option>Kas</option>
                        <option>Piutang Usaha</option>
                        <option>Utang Usaha</option>
                        <option>Pendapatan</option>
                        <option>Beban</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabel jurnal umum -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <!-- Header tabel -->
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold">No. Jurnal</th>
                            <th class="px-4 py-3 font-semibold">Kode</th>
                            <th class="px-4 py-3 font-semibold">Nama Akun</th>
                            <th class="px-4 py-3 font-semibold">Keterangan</th>
                            <th class="px-4 py-3 font-semibold text-right">Debit</th>
                            <th class="px-4 py-3 font-semibold text-right">Kredit</th>
                            <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <!-- Isi tabel: daftar transaksi jurnal umum -->
                    <tbody class="divide-y divide-gray-200">

                        <!-- Baris transaksi 1: pencatatan kas dari pendapatan jasa -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">01/01/2025</td>
                            <td class="px-4 py-3 text-gray-700 font-medium">JU-001</td>
                            <td class="px-4 py-3 text-gray-700">1-1000</td>
                            <td class="px-4 py-3 text-gray-900 font-medium">Kas</td>
                            <td class="px-4 py-3 text-gray-600">Penerimaan jasa konsultasi</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 5.000.000</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                        <!-- Baris akun lawan untuk transaksi 1 -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-400">01/01/2025</td>
                            <td class="px-4 py-3 text-gray-400">JU-001</td>
                            <td class="px-4 py-3 text-gray-700">4-1000</td>
                            <td class="px-4 py-3 text-gray-700 pl-8"><i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-gray-400 mr-2"></i>Pendapatan Jasa</td>
                            <td class="px-4 py-3 text-gray-600">Penerimaan jasa konsultasi</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 5.000.000</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>

                        <!-- Baris transaksi 2: pembelian perlengkapan secara kredit -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">03/01/2025</td>
                            <td class="px-4 py-3 text-gray-700 font-medium">JU-002</td>
                            <td class="px-4 py-3 text-gray-700">1-1200</td>
                            <td class="px-4 py-3 text-gray-900 font-medium">Perlengkapan</td>
                            <td class="px-4 py-3 text-gray-600">Pembelian perlengkapan kantor</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 1.500.000</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                        <!-- Baris akun lawan untuk transaksi 2 -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-400">03/01/2025</td>
                            <td class="px-4 py-3 text-gray-400">JU-002</td>
                            <td class="px-4 py-3 text-gray-700">2-1000</td>
                            <td class="px-4 py-3 text-gray-700 pl-8"><i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-gray-400 mr-2"></i>Utang Usaha</td>
                            <td class="px-4 py-3 text-gray-600">Pembelian perlengkapan kantor</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 1.500.000</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>

                        <!-- Baris transaksi 3: pembayaran beban listrik -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">05/01/2025</td>
                            <td class="px-4 py-3 text-gray-700 font-medium">JU-003</td>
                            <td class="px-4 py-3 text-gray-700">5-1000</td>
                            <td class="px-4 py-3 text-gray-900 font-medium">Beban Listrik</td>
                            <td class="px-4 py-3 text-gray-600">Pembayaran tagihan listrik</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 450.000</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                        <!-- Baris akun lawan untuk transaksi 3 -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-400">05/01/2025</td>
                            <td class="px-4 py-3 text-gray-400">JU-003</td>
                            <td class="px-4 py-3 text-gray-700">1-1000</td>
                            <td class="px-4 py-3 text-gray-700 pl-8"><i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-gray-400 mr-2"></i>Kas</td>
                            <td class="px-4 py-3 text-gray-600">Pembayaran tagihan listrik</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 450.000</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>

                        <!-- Baris transaksi 4: pelunasan piutang usaha -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">08/01/2025</td>
                            <td class="px-4 py-3 text-gray-700 font-medium">JU-004</td>
                            <td class="px-4 py-3 text-gray-700">1-1000</td>
                            <td class="px-4 py-3 text-gray-900 font-medium">Kas</td>
                            <td class="px-4 py-3 text-gray-600">Pelunasan piutang pelanggan</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 2.500.000</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                        <!-- Baris akun lawan untuk transaksi 4 -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-400">08/01/2025</td>
                            <td class="px-4 py-3 text-gray-400">JU-004</td>
                            <td class="px-4 py-3 text-gray-700">1-1100</td>
                            <td class="px-4 py-3 text-gray-700 pl-8"><i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-gray-400 mr-2"></i>Piutang Usaha</td>
                            <td class="px-4 py-3 text-gray-600">Pelunasan piutang pelanggan</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 2.500.000</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>

                        <!-- Baris transaksi 5: pembelian peralatan tunai -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">10/01/2025</td>
                            <td class="px-4 py-3 text-gray-700 font-medium">JU-005</td>
                            <td class="px-4 py-3 text-gray-700">1-1300</td>
                            <td class="px-4 py-3 text-gray-900 font-medium">Peralatan</td>
                            <td class="px-4 py-3 text-gray-600">Pembelian peralatan kantor</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 3.000.000</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>
                        <!-- Baris akun lawan untuk transaksi 5 -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-400">10/01/2025</td>
                            <td class="px-4 py-3 text-gray-400">JU-005</td>
                            <td class="px-4 py-3 text-gray-700">1-1000</td>
                            <td class="px-4 py-3 text-gray-700 pl-8"><i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-gray-400 mr-2"></i>Kas</td>
                            <td class="px-4 py-3 text-gray-600">Pembelian peralatan kantor</td>
                            <td class="px-4 py-3 text-right text-gray-400">-</td>
                            <td class="px-4 py-3 text-right text-gray-900 font-medium">Rp 3.000.000</td>
                            <td class="px-4 py-3 text-center">
                                <button class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-pen"></i></button>
                            </td>
                        </tr>

                    </tbody>

                    <!-- Footer tabel: baris total debit dan kredit -->
                    <tfoot class="bg-gray-100 border-t-2 border-gray-300">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right font-semibold text-gray-800">Total</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">Rp 12.450.000</td>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">Rp 12.450.000</td>
                            <td class="px-4 py-3"></td>
                        </tr>
                        <!-- Baris status keseimbangan jurnal -->
                        <tr>
                            <td colspan="8" class="px-4 py-2 text-right text-xs">
                                <span class="inline-flex items-center gap-1 text-green-700 font-medium">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Jurnal balance</span>
                                </span>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>

        <!-- Pagination dan info jumlah data -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mt-4">
            <p class="text-sm text-gray-600">Menampilkan 1 - 10 dari 24 transaksi</p>
            <div class="flex items-center gap-1">
                <!-- Tombol halaman sebelumnya -->
                <button class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-600 hover:bg-gray-100">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <!-- Tombol halaman 1 (aktif) -->
                <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-600 text-white font-medium text-sm">1</button>
                <!-- Tombol halaman 2 -->
                <button class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 text-sm">2</button>
                <!-- Tombol halaman 3 -->
                <button class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 text-sm">3</button>
                <!-- Tombol halaman selanjutnya -->
                <button class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-600 hover:bg-gray-100">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>

    </div>
</main>

<?php
include '../layouts/footer.php';
?>