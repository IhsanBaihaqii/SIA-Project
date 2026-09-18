<?php
include '../config/koneksi.php';
include '../includes/helpers.php';

$id = (int)($_GET['id'] ?? 0);
if ($id < 1) {
    echo '<p class="text-red-500 text-center py-4">ID tidak valid.</p>';
    exit;
}

// Header transaksi
$stmt = $pdo->prepare("
    SELECT t.*, COALESCE(p.nama, 'Umum') AS nama_pelanggan,
           p.no_hp, p.alamat
    FROM tbl_transaction t
    LEFT JOIN tbl_pelanggan p ON p.id_pelanggan = t.id_pelanggan
    WHERE t.id_transaction = :id
");
$stmt->execute([':id' => $id]);
$trx = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trx) {
    echo '<p class="text-red-500 text-center py-4">Transaksi tidak ditemukan.</p>';
    exit;
}

// Detail item
$stmt = $pdo->prepare("
    SELECT d.*, pr.nama AS nama_produk, pr.kategori
    FROM tbl_transaction_details d
    JOIN tbl_products pr ON pr.id_product = d.id_product
    WHERE d.id_transaction = :id
");
$stmt->execute([':id' => $id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Info Transaksi -->
<div class="bg-gray-50 rounded-lg p-4 mb-4">
    <div class="grid grid-cols-2 gap-3 text-sm">
        <div>
            <p class="text-gray-500">No. Transaksi</p>
            <p class="font-semibold text-gray-800">
                #TRX-<?= str_pad($trx['id_transaction'], 4, '0', STR_PAD_LEFT) ?>
            </p>
        </div>
        <div>
            <p class="text-gray-500">Tanggal</p>
            <p class="font-semibold text-gray-800">
                <?= date('d M Y, H:i', strtotime($trx['tanggal'])) ?>
            </p>
        </div>
        <div>
            <p class="text-gray-500">Pelanggan</p>
            <p class="font-semibold text-gray-800"><?= bersih($trx['nama_pelanggan']) ?></p>
        </div>
        <div>
            <p class="text-gray-500">No. HP</p>
            <p class="font-semibold text-gray-800"><?= bersih($trx['no_hp'] ?: '-') ?></p>
        </div>
    </div>
</div>

<!-- Tabel Item -->
<div class="overflow-x-auto rounded-lg border border-gray-100 mb-4">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left font-semibold">Produk</th>
                <th class="px-4 py-3 text-center font-semibold">Qty</th>
                <th class="px-4 py-3 text-right font-semibold">Harga</th>
                <th class="px-4 py-3 text-right font-semibold">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach ($items as $it): ?>
            <tr>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800"><?= bersih($it['nama_produk']) ?></p>
                    <p class="text-xs text-gray-500"><?= bersih($it['kategori']) ?></p>
                </td>
                <td class="px-4 py-3 text-center"><?= (int)$it['qty'] ?></td>
                <td class="px-4 py-3 text-right"><?= rupiah($it['harga']) ?></td>
                <td class="px-4 py-3 text-right font-medium text-blue-600"><?= rupiah($it['subtotal']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="bg-gray-50 border-t border-gray-200">
            <tr>
                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-800">Total</td>
                <td class="px-4 py-3 text-right font-bold text-blue-600 text-base">
                    <?= rupiah($trx['total']) ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>