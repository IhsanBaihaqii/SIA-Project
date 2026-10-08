<?php
require '../config/koneksi.php';
require '../includes/helpers.php';

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';

// 1. Ambil parameter filter dari URL
$dari    = $_GET['dari']   ?? date('Y-m-01');
$sampai  = $_GET['sampai'] ?? date('Y-m-t');
$cari    = trim($_GET['cari'] ?? '');
$akun_id = $_GET['akun']   ?? '';

// 2. Ambil daftar akun untuk dropdown filter
$stmtAkun  = $pdo->query("SELECT id_akun, kode_akun, nama_akun FROM tbl_akun ORDER BY kode_akun");
$akun_list = $stmtAkun->fetchAll(PDO::FETCH_ASSOC);

// 3. Susun query jurnal dengan filter
$sql = "SELECT j.id_journal, j.no_bukti, j.tanggal, j.keterangan,
               j.debit, j.kredit,
               a.id_akun, a.kode_akun, a.nama_akun
        FROM tbl_journal j
        JOIN tbl_akun a ON a.id_akun = j.id_akun
        WHERE DATE(j.tanggal) BETWEEN :dari AND :sampai";

$params = [
    ':dari'   => $dari,
    ':sampai' => $sampai,
];

if ($cari !== '') {
    $sql .= " AND (j.no_bukti LIKE :c1 OR j.keterangan LIKE :c2 OR a.nama_akun LIKE :c3)";
    $params[':c1'] = '%' . $cari . '%';
    $params[':c2'] = '%' . $cari . '%';
    $params[':c3'] = '%' . $cari . '%';
}

if ($akun_id !== '') {
    $sql .= " AND j.id_akun = :akun";
    $params[':akun'] = $akun_id;
}

$sql .= " ORDER BY j.tanggal ASC, j.no_bukti ASC, j.id_journal ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Kelompokkan baris jurnal berdasarkan no_bukti
$grup = [];
foreach ($rows as $r) {
    $grup[$r['no_bukti']][] = $r;
}

// 5. Hitung ringkasan
$total_debit  = 0;
$total_kredit = 0;
foreach ($rows as $r) {
    $total_debit  += (int)$r['debit'];
    $total_kredit += (int)$r['kredit'];
}
$selisih          = $total_debit - $total_kredit;
$jumlah_transaksi = count($grup);
$balance          = ($selisih === 0);
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

            #area-cetak .tabel-jurnal {
                border-collapse: collapse !important;
                font-size: 11px !important;
                width: 100% !important;
            }

            #area-cetak .tabel-jurnal th,
            #area-cetak .tabel-jurnal td {
                border: 1px solid #000000 !important;
                padding: 4px 6px !important;
                color: #000000 !important;
            }

            #area-cetak .tabel-jurnal thead th {
                background: #e5e7eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            #area-cetak .tabel-jurnal tr {
                page-break-inside: avoid;
            }

            #area-cetak .tabel-jurnal tfoot td {
                background: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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

    <!-- Bagian atas: judul halaman + tombol aksi (tidak dicetak) -->
    <div class="no-print flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jurnal Umum</h1>
            <p class="text-gray-500 text-sm">
                Catatan transaksi akuntansi periode
                <?= bersih(date('d/m/Y', strtotime($dari))) ?> s/d
                <?= bersih(date('d/m/Y', strtotime($sampai))) ?>
            </p>
        </div>
        <div class="mt-3 sm:mt-0 flex gap-2">
            <button onclick="window.print()"
                class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
            <a href="../kasir/index.php"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Transaksi Baru
            </a>
        </div>
    </div>

    <!-- Kartu ringkasan (tidak dicetak) -->
    <div class="no-print grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Total debit periode -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">Total Debit</p>
                    <p class="text-lg font-bold text-gray-800 mt-1"><?= rupiah($total_debit) ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-down text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total kredit periode -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">Total Kredit</p>
                    <p class="text-lg font-bold text-gray-800 mt-1"><?= rupiah($total_kredit) ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-up text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Selisih debit - kredit -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">Selisih</p>
                    <p class="text-lg font-bold <?= $balance ? 'text-gray-800' : 'text-red-600' ?> mt-1">
                        <?= rupiah($selisih) ?>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                    <i class="fa-solid fa-scale-balanced text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Jumlah transaksi unik dalam periode -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-500">Jumlah Transaksi</p>
                    <p class="text-lg font-bold text-gray-800 mt-1"><?= $jumlah_transaksi ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris filter dan pencarian (tidak dicetak) -->
    <div class="no-print bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form method="get" class="flex flex-wrap items-center gap-3">

            <!-- Input pencarian teks -->
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="cari" value="<?= bersih($cari) ?>"
                    placeholder="Cari no. bukti, keterangan, atau nama akun..."
                    class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
            </div>

            <!-- Batas awal periode -->
            <input type="date" name="dari" value="<?= bersih($dari) ?>"
                class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">

            <!-- Batas akhir periode -->
            <input type="date" name="sampai" value="<?= bersih($sampai) ?>"
                class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">

            <!-- Filter akun -->
            <select name="akun"
                class="px-3 py-2 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                <option value="">Semua Akun</option>
                <?php foreach ($akun_list as $a): ?>
                    <option value="<?= (int)$a['id_akun'] ?>"
                        <?= ($akun_id !== '' && (int)$akun_id === (int)$a['id_akun']) ? 'selected' : '' ?>>
                        <?= bersih($a['kode_akun']) ?> - <?= bersih($a['nama_akun']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Tombol jalankan filter -->
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-search"></i> Cari
            </button>

            <!-- Tombol reset filter, hanya muncul jika filter aktif -->
            <?php if ($cari !== '' || $akun_id !== ''): ?>
                <a href="jurnal.php" class="border border-gray-200 rounded-lg px-4 py-2 text-sm hover:bg-gray-50 transition-colors">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Area yang dicetak, berisi header cetak, tabel, dan tanda tangan -->
    <div id="area-cetak" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Header cetak, hanya tampil saat print -->
        <div class="hidden print:block mb-4 text-center">
            <p class="text-base font-bold uppercase">Nama Perusahaan</p>
            <p class="text-xs">Alamat perusahaan, telepon, email</p>
            <hr class="my-2 border-black">
            <p class="text-sm font-bold uppercase tracking-wider">Jurnal Umum</p>
            <p class="text-xs">
                Periode
                <?= bersih(date('d/m/Y', strtotime($dari))) ?> s/d
                <?= bersih(date('d/m/Y', strtotime($sampai))) ?>
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="tabel-jurnal w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">No. Bukti</th>
                        <th class="px-6 py-4 font-semibold">Kode</th>
                        <th class="px-6 py-4 font-semibold">Nama Akun</th>
                        <th class="px-6 py-4 font-semibold">Keterangan</th>
                        <th class="px-6 py-4 font-semibold text-right">Debit</th>
                        <th class="px-6 py-4 font-semibold text-right">Kredit</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($grup)): ?>
                        <!-- Baris kosong jika tidak ada data pada periode -->
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <i class="fa-solid fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                Belum ada data jurnal pada periode ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($grup as $no_bukti => $baris_list): ?>
                            <?php
                            // Ambil baris pertama untuk tanggal dan keterangan grup
                            $first = $baris_list[0];
                            $tgl_fmt = date('d/m/Y', strtotime($first['tanggal']));
                            ?>
                            <?php foreach ($baris_list as $i => $b): ?>
                                <?php
                                // Tandai baris kredit untuk indentasi dan baris pertama untuk info grup
                                $is_kredit  = ((int)$b['kredit'] > 0);
                                $is_pertama = ($i === 0);
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-gray-600 whitespace-nowrap">
                                        <?= $is_pertama ? $tgl_fmt : '' ?>
                                    </td>
                                    <td class="px-6 py-3 text-gray-700 font-medium whitespace-nowrap">
                                        <?= $is_pertama ? bersih($no_bukti) : '' ?>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600 whitespace-nowrap">
                                        <?= bersih($b['kode_akun']) ?>
                                    </td>
                                    <td class="px-6 py-3 <?= $is_kredit ? 'pl-12 italic text-gray-600' : 'font-medium text-gray-800' ?>">
                                        <?php if ($is_kredit): ?>
                                            <i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-gray-400 mr-2"></i>
                                        <?php endif; ?>
                                        <?= bersih($b['nama_akun']) ?>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">
                                        <?= $is_pertama ? bersih($b['keterangan']) : '' ?>
                                    </td>
                                    <td class="px-6 py-3 text-right tabular-nums <?= ((int)$b['debit'] > 0) ? 'text-gray-800 font-medium' : 'text-gray-300' ?>">
                                        <?= ((int)$b['debit'] > 0) ? rupiah($b['debit']) : '-' ?>
                                    </td>
                                    <td class="px-6 py-3 text-right tabular-nums <?= ((int)$b['kredit'] > 0) ? 'text-gray-800 font-medium' : 'text-gray-300' ?>">
                                        <?= ((int)$b['kredit'] > 0) ? rupiah($b['kredit']) : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

                <?php if (!empty($grup)): ?>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <!-- Baris total debit dan kredit -->
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right font-semibold text-gray-700 uppercase text-xs tracking-wide">Total</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800 tabular-nums"><?= rupiah($total_debit) ?></td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800 tabular-nums"><?= rupiah($total_kredit) ?></td>
                        </tr>

                        <!-- Baris status keseimbangan jurnal -->
                        <tr>
                            <td colspan="7" class="px-6 py-3 text-right text-xs border-t border-gray-100">
                                <?php if ($balance): ?>
                                    <span class="inline-flex items-center gap-1 text-green-700 font-medium">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>Jurnal balance</span>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-red-700 font-medium">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        <span>Jurnal tidak balance, selisih <?= rupiah(abs($selisih)) ?></span>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>

        <!-- Area tanda tangan, hanya tampil saat print -->
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

        <!-- Footer info jumlah baris, tidak tampil saat print -->
        <div class="no-print px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
            <span class="text-gray-500">
                Menampilkan <?= count($rows) ?> baris jurnal dari <?= $jumlah_transaksi ?> transaksi
            </span>
        </div>
    </div>
</main>

<?php
include '../layouts/footer.php';
?>