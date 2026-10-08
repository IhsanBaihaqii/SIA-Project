<?php
// halaman buku besar per akun
require '../config/koneksi.php';
require '../includes/helpers.php';

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';

// filter: akun, periode
$akun_id = $_GET['akun'] ?? '';
$dari    = $_GET['dari'] ?? date('Y-m-01');
$sampai  = $_GET['sampai'] ?? date('Y-m-t');

// daftar akun untuk dropdown
$akun_list = $pdo->query("SELECT id_akun, kode_akun, nama_akun FROM tbl_akun ORDER BY kode_akun")->fetchAll(PDO::FETCH_ASSOC);

// kalau belum pilih akun, pakai akun pertama
if ($akun_id === '' && !empty($akun_list)) {
    $akun_id = $akun_list[0]['id_akun'];
}

// ambil detail akun terpilih
$akun_aktif = null;
if ($akun_id !== '') {
    $stmt = $pdo->prepare("SELECT * FROM tbl_akun WHERE id_akun = :id");
    $stmt->execute([':id' => $akun_id]);
    $akun_aktif = $stmt->fetch(PDO::FETCH_ASSOC);
}

// hitung saldo awal sebelum periode
$saldo_awal = 0;
if ($akun_aktif) {
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(debit),0) AS d, COALESCE(SUM(kredit),0) AS k
        FROM tbl_journal
        WHERE id_akun = :id AND DATE(tanggal) < :dari
    ");
    $stmt->execute([':id' => $akun_id, ':dari' => $dari]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);

    // saldo awal pakai konvensi saldo normal akun
    if ($akun_aktif['saldo_normal'] === 'debit') {
        $saldo_awal = (int)$r['d'] - (int)$r['k'];
    } else {
        $saldo_awal = (int)$r['k'] - (int)$r['d'];
    }
}

// ambil baris jurnal dalam periode
$rows = [];
if ($akun_aktif) {
    $stmt = $pdo->prepare("
        SELECT no_bukti, tanggal, keterangan, debit, kredit
        FROM tbl_journal
        WHERE id_akun = :id AND DATE(tanggal) BETWEEN :dari AND :sampai
        ORDER BY tanggal ASC, id_journal ASC
    ");
    $stmt->execute([':id' => $akun_id, ':dari' => $dari, ':sampai' => $sampai]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// hitung running saldo per baris
$running = $saldo_awal;
$total_debit = 0;
$total_kredit = 0;

foreach ($rows as $i => $row) {
    $total_debit  += (int)$row['debit'];
    $total_kredit += (int)$row['kredit'];

    if ($akun_aktif['saldo_normal'] === 'debit') {
        $running += (int)$row['debit'] - (int)$row['kredit'];
    } else {
        $running += (int)$row['kredit'] - (int)$row['debit'];
    }

    $rows[$i]['saldo'] = $running;
}

$saldo_akhir = $running;
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

            #area-cetak .tabel-buku {
                border-collapse: collapse !important;
                font-size: 11px !important;
                width: 100% !important;
            }

            #area-cetak .tabel-buku th,
            #area-cetak .tabel-buku td {
                border: 1px solid #000000 !important;
                padding: 4px 6px !important;
                color: #000000 !important;
            }

            #area-cetak .tabel-buku thead th,
            #area-cetak .tabel-buku tfoot td {
                background: #e5e7eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            #area-cetak .tabel-buku tr {
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
            <h1 class="text-2xl font-bold text-gray-800">Buku Besar</h1>
            <p class="text-gray-500 text-sm">
                Rincian mutasi per akun periode
                <?= bersih(date('d/m/Y', strtotime($dari))) ?> s/d
                <?= bersih(date('d/m/Y', strtotime($sampai))) ?>
            </p>
        </div>
        <div class="mt-3 sm:mt-0 flex gap-2">
            <button onclick="window.print()"
                class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
            <a href="jurnal.php"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-book"></i> Lihat Jurnal
            </a>
        </div>
    </div>

    <!-- Filter akun dan periode, tidak dicetak -->
    <div class="no-print bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form method="get" class="flex flex-wrap items-center gap-3">

            <!-- Dropdown pilih akun -->
            <select name="akun"
                class="px-3 py-2 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm min-w-[220px]">
                <?php foreach ($akun_list as $a): ?>
                    <option value="<?= (int)$a['id_akun'] ?>"
                        <?= ((int)$akun_id === (int)$a['id_akun']) ? 'selected' : '' ?>>
                        <?= bersih($a['kode_akun']) ?> - <?= bersih($a['nama_akun']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

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

        <!-- Header cetak, hanya tampil saat print -->
        <div class="hidden print:block mb-4 text-center">
            <p class="text-base font-bold uppercase">Nama Perusahaan Anda</p>
            <p class="text-xs">Alamat perusahaan, telepon, email</p>
            <hr class="my-2 border-black">
            <p class="text-sm font-bold uppercase tracking-wider">Buku Besar</p>
            <p class="text-xs">
                Periode
                <?= bersih(date('d/m/Y', strtotime($dari))) ?> s/d
                <?= bersih(date('d/m/Y', strtotime($sampai))) ?>
            </p>
        </div>

        <?php if (!$akun_aktif): ?>
            <!-- Pesan kalau belum ada akun sama sekali -->
            <div class="px-6 py-10 text-center text-gray-500">
                <i class="fa-solid fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                Belum ada akun pada tabel akun.
            </div>
        <?php else: ?>

            <!-- Info akun yang sedang dilihat -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <p class="text-xs uppercase text-gray-500">Akun</p>
                <p class="text-lg font-bold text-gray-800">
                    <?= bersih($akun_aktif['kode_akun']) ?> - <?= bersih($akun_aktif['nama_akun']) ?>
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Tipe <?= bersih(ucfirst($akun_aktif['tipe'])) ?>
                    | Saldo normal <?= bersih(ucfirst($akun_aktif['saldo_normal'])) ?>
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="tabel-buku w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">No. Bukti</th>
                            <th class="px-6 py-4 font-semibold">Keterangan</th>
                            <th class="px-6 py-4 font-semibold text-right">Debit</th>
                            <th class="px-6 py-4 font-semibold text-right">Kredit</th>
                            <th class="px-6 py-4 font-semibold text-right">Saldo D</th>
                            <th class="px-6 py-4 font-semibold text-right">Saldo K</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        <!-- Baris saldo awal sebelum periode -->
                        <tr class="bg-blue-50">
                            <td class="px-6 py-3 text-gray-600 whitespace-nowrap">-</td>
                            <td class="px-6 py-3 text-gray-600 whitespace-nowrap">-</td>
                            <td class="px-6 py-3 text-gray-700 font-semibold italic">Saldo Awal</td>
                            <td class="px-6 py-3 text-right text-gray-300 tabular-nums">-</td>
                            <td class="px-6 py-3 text-right text-gray-300 tabular-nums">-</td>
                            <td class="px-6 py-3 text-right tabular-nums font-semibold <?= ($saldo_awal >= 0) ? 'text-gray-800' : 'text-gray-300' ?>">
                                <?= ($saldo_awal >= 0) ? rupiah($saldo_awal) : '-' ?>
                            </td>
                            <td class="px-6 py-3 text-right tabular-nums font-semibold <?= ($saldo_awal < 0) ? 'text-gray-800' : 'text-gray-300' ?>">
                                <?= ($saldo_awal < 0) ? rupiah(abs($saldo_awal)) : '-' ?>
                            </td>
                        </tr>

                        <?php if (empty($rows)): ?>
                            <!-- Info kalau belum ada mutasi di periode ini -->
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    <i class="fa-solid fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                    Belum ada mutasi pada periode ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rows as $b): ?>
                                <?php
                                // tentukan apakah saldo baris ini ditaruh di kolom debit atau kredit
                                $saldo_pos = ((int)$b['saldo'] >= 0);
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-gray-600 whitespace-nowrap">
                                        <?= bersih(date('d/m/Y', strtotime($b['tanggal']))) ?>
                                    </td>
                                    <td class="px-6 py-3 text-gray-700 font-medium whitespace-nowrap">
                                        <?= bersih($b['no_bukti']) ?>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">
                                        <?= bersih($b['keterangan']) ?>
                                    </td>
                                    <td class="px-6 py-3 text-right tabular-nums <?= ((int)$b['debit'] > 0) ? 'text-gray-800 font-medium' : 'text-gray-300' ?>">
                                        <?= ((int)$b['debit'] > 0) ? rupiah($b['debit']) : '-' ?>
                                    </td>
                                    <td class="px-6 py-3 text-right tabular-nums <?= ((int)$b['kredit'] > 0) ? 'text-gray-800 font-medium' : 'text-gray-300' ?>">
                                        <?= ((int)$b['kredit'] > 0) ? rupiah($b['kredit']) : '-' ?>
                                    </td>
                                    <td class="px-6 py-3 text-right tabular-nums <?= $saldo_pos ? 'text-gray-800' : 'text-gray-300' ?>">
                                        <?= $saldo_pos ? rupiah($b['saldo']) : '-' ?>
                                    </td>
                                    <td class="px-6 py-3 text-right tabular-nums <?= !$saldo_pos ? 'text-gray-800' : 'text-gray-300' ?>">
                                        <?= !$saldo_pos ? rupiah(abs($b['saldo'])) : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>

                    <?php if (!empty($rows)): ?>
                        <tfoot class="bg-gray-50 border-t border-gray-200">

                            <!-- Total mutasi periode ini -->
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700 uppercase text-xs tracking-wide">
                                    Total Mutasi
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-800 tabular-nums"><?= rupiah($total_debit) ?></td>
                                <td class="px-6 py-4 text-right font-bold text-gray-800 tabular-nums"><?= rupiah($total_kredit) ?></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"></td>
                            </tr>

                            <!-- Saldo akhir setelah mutasi -->
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700 uppercase text-xs tracking-wide">
                                    Saldo Akhir
                                </td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 tabular-nums">
                                    <?= ($saldo_akhir >= 0) ? rupiah($saldo_akhir) : '-' ?>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 tabular-nums">
                                    <?= ($saldo_akhir < 0) ? rupiah(abs($saldo_akhir)) : '-' ?>
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

            <!-- Info jumlah baris, tidak dicetak -->
            <div class="no-print px-6 py-4 border-t border-gray-100 text-sm text-gray-500">
                Menampilkan <?= count($rows) ?> baris mutasi.
            </div>

        <?php endif; ?>
    </div>
</main>