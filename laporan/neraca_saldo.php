<?php
// halaman neraca saldo, agregasi dari tbl_journal
require '../config/koneksi.php';
require '../includes/helpers.php';

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';

// filter periode, default bulan berjalan
$dari   = $_GET['dari']   ?? date('Y-m-01');
$sampai = $_GET['sampai'] ?? date('Y-m-t');

// ambil total debit dan kredit per akun dalam periode
$stmt = $pdo->prepare("
    SELECT a.id_akun, a.kode_akun, a.nama_akun, a.tipe, a.saldo_normal,
           COALESCE(SUM(j.debit),0)  AS total_debit,
           COALESCE(SUM(j.kredit),0) AS total_kredit
    FROM tbl_akun a
    LEFT JOIN tbl_journal j
        ON j.id_akun = a.id_akun
        AND DATE(j.tanggal) BETWEEN :dari AND :sampai
    GROUP BY a.id_akun, a.kode_akun, a.nama_akun, a.tipe, a.saldo_normal
    ORDER BY a.kode_akun ASC
");
$stmt->execute([':dari' => $dari, ':sampai' => $sampai]);
$hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);

// hitung saldo per akun, skip akun yang tidak punya mutasi
$data    = [];
$total_d = 0;
$total_k = 0;

foreach ($hasil as $r) {
    $selisih = (int)$r['total_debit'] - (int)$r['total_kredit'];

    // tentukan posisi saldo, positif ke debit, negatif ke kredit
    if ($selisih > 0) {
        $d = $selisih;
        $k = 0;
    } elseif ($selisih < 0) {
        $d = 0;
        $k = abs($selisih);
    } else {
        // akun tanpa saldo, skip
        continue;
    }

    $r['saldo_debit']  = $d;
    $r['saldo_kredit'] = $k;

    $data[]   = $r;
    $total_d += $d;
    $total_k += $k;
}

// cek keseimbangan neraca
$balance = ($total_d === $total_k);
$selisih = $total_d - $total_k;
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #area-cetak,
            #area-cetak * {
                visibility: visible;
            }

            #area-cetak {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                background: #ffffff !important;
            }

            #area-cetak .tabel-neraca {
                border-collapse: collapse !important;
                font-size: 11px !important;
                width: 100% !important;
            }

            #area-cetak .tabel-neraca th,
            #area-cetak .tabel-neraca td {
                border: 1px solid #000000 !important;
                padding: 4px 6px !important;
                color: #000000 !important;
            }

            #area-cetak .tabel-neraca thead th,
            #area-cetak .tabel-neraca tfoot td {
                background: #e5e7eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            #area-cetak .tabel-neraca tr {
                page-break-inside: avoid;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 1cm;
            }
        }
    </style>

    <!-- Judul halaman dan tombol aksi, tidak dicetak -->
    <div class="no-print flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Neraca Saldo</h1>
            <p class="text-gray-500 text-sm">
                Daftar saldo akun periode
                <?= bersih(date('d/m/Y', strtotime($dari))) ?> s/d
                <?= bersih(date('d/m/Y', strtotime($sampai))) ?>
            </p>
        </div>
        <div class="mt-3 sm:mt-0 flex gap-2">
            <button onclick="window.print()"
                class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
            <a href="buku_besar.php"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-book-open"></i> Buku Besar
            </a>
        </div>
    </div>

    <!-- Kartu ringkasan, tidak dicetak -->
    <div class="no-print grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <!-- Total saldo debit semua akun -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">Total Saldo Debit</p>
                    <p class="text-lg font-bold text-gray-800 mt-1"><?= rupiah($total_d) ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-down text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total saldo kredit semua akun -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">Total Saldo Kredit</p>
                    <p class="text-lg font-bold text-gray-800 mt-1"><?= rupiah($total_k) ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-up text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Status keseimbangan neraca -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">Status</p>
                    <p class="text-lg font-bold mt-1 <?= $balance ? 'text-green-700' : 'text-red-600' ?>">
                        <?= $balance ? 'Balance' : 'Tidak Balance' ?>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl <?= $balance ? 'bg-green-50' : 'bg-red-50' ?> flex items-center justify-center">
                    <i class="fa-solid <?= $balance ? 'fa-circle-check text-green-600' : 'fa-triangle-exclamation text-red-600' ?>"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter periode, tidak dicetak -->
    <div class="no-print bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form method="get" class="flex flex-wrap items-center gap-3">

            <!-- Tanggal awal periode -->
            <input type="date" name="dari" value="<?= bersih($dari) ?>"
                class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">

            <!-- Tanggal akhir periode -->
            <input type="date" name="sampai" value="<?= bersih($sampai) ?>"
                class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">

            <!-- Tombol tampilkan -->
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Tampilkan
            </button>
        </form>
    </div>

    <!-- Area yang dicetak -->
    <div id="area-cetak" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Header cetak, hanya saat print -->
        <div class="hidden print:block mb-4 text-center">
            <p class="text-base font-bold uppercase">Nama Perusahaan Anda</p>
            <p class="text-xs">Alamat perusahaan, telepon, email</p>
            <hr class="my-2 border-black">
            <p class="text-sm font-bold uppercase tracking-wider">Neraca Saldo</p>
            <p class="text-xs">
                Periode
                <?= bersih(date('d/m/Y', strtotime($dari))) ?> s/d
                <?= bersih(date('d/m/Y', strtotime($sampai))) ?>
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="tabel-neraca w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Kode</th>
                        <th class="px-6 py-4 font-semibold">Nama Akun</th>
                        <th class="px-6 py-4 font-semibold">Tipe</th>
                        <th class="px-6 py-4 font-semibold text-right">Debit</th>
                        <th class="px-6 py-4 font-semibold text-right">Kredit</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($data)): ?>
                        <!-- Info kalau belum ada saldo pada periode -->
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <i class="fa-solid fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                Belum ada saldo akun pada periode ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data as $b): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 text-gray-700 font-medium whitespace-nowrap">
                                    <?= bersih($b['kode_akun']) ?>
                                </td>
                                <td class="px-6 py-3 text-gray-800">
                                    <?= bersih($b['nama_akun']) ?>
                                </td>
                                <td class="px-6 py-3 text-gray-600 capitalize">
                                    <?= bersih($b['tipe']) ?>
                                </td>
                                <td class="px-6 py-3 text-right tabular-nums <?= ($b['saldo_debit'] > 0) ? 'text-gray-800 font-medium' : 'text-gray-300' ?>">
                                    <?= ($b['saldo_debit'] > 0) ? rupiah($b['saldo_debit']) : '-' ?>
                                </td>
                                <td class="px-6 py-3 text-right tabular-nums <?= ($b['saldo_kredit'] > 0) ? 'text-gray-800 font-medium' : 'text-gray-300' ?>">
                                    <?= ($b['saldo_kredit'] > 0) ? rupiah($b['saldo_kredit']) : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

                <?php if (!empty($data)): ?>
                    <tfoot class="bg-gray-50 border-t border-gray-200">

                        <!-- Baris total debit dan kredit -->
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700 uppercase text-xs tracking-wide">
                                Total
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800 tabular-nums"><?= rupiah($total_d) ?></td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800 tabular-nums"><?= rupiah($total_k) ?></td>
                        </tr>

                        <!-- Baris status keseimbangan -->
                        <tr>
                            <td colspan="5" class="px-6 py-3 text-right text-xs border-t border-gray-100">
                                <?php if ($balance): ?>
                                    <span class="inline-flex items-center gap-1 text-green-700 font-medium">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>Neraca saldo balance</span>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-red-700 font-medium">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        <span>Neraca saldo tidak balance, selisih <?= rupiah(abs($selisih)) ?></span>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>

        <!-- Area tanda tangan, hanya saat print -->
        <div class="hidden print:flex justify-between mt-10 px-4 text-xs">
            <div class="text-center">
                <p>Dibuat oleh,</p>
                <div class="h-16"></div>
                <p class="border-t border-black pt-1 w-40 mx-auto">Bagian Akuntansi</p>
            </div>
            <div class="text-center">
                <p>Diperiksa oleh,</p>
                <div class="h-16"></div>
                <p class="border-t border-black pt-1 w-40 mx-auto">Manajer</p>
            </div>
        </div>

        <!-- Info jumlah akun, tidak dicetak -->
        <div class="no-print px-6 py-4 border-t border-gray-100 text-sm text-gray-500">
            Menampilkan <?= count($data) ?> akun dengan saldo.
        </div>
    </div>
</main>