<?php
include '../config/koneksi.php';
include '../includes/helpers.php';

// Load produk yang stoknya masih ada
$produk = $pdo->query("SELECT * FROM tbl_products WHERE stok > 0 ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);

// Load pelanggan
$pelanggan = $pdo->query("SELECT * FROM tbl_pelanggan ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);

$success = $_GET['success'] ?? '';
$error   = $_GET['error']   ?? '';

include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>

<main class="md:ml-64 pt-16 min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kasir</h1>
        <p class="text-gray-500 text-sm">Input transaksi penjualan & auto-generate jurnal akuntansi.</p>
    </div>

    <!-- Notifikasi -->
    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-check-circle mr-2"></i> Transaksi berhasil. Stok & jurnal telah diperbarui.
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i> <?= bersih($error) ?>
        </div>
    <?php endif; ?>

    <!-- Grid dua kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Kolom Kiri -->
        <div class="lg:col-span-3 space-y-6">

            <!-- Tambah ke Keranjang -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah ke Keranjang</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk</label>
                        <select id="selectProduk" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($produk as $p): ?>
                                <option value="<?= $p['id_product'] ?>"
                                        data-nama="<?= bersih($p['nama']) ?>"
                                        data-harga="<?= (int)$p['harga'] ?>"
                                        data-stok="<?= (int)$p['stok'] ?>">
                                    <?= bersih($p['nama']) ?> — <?= rupiah($p['harga']) ?> (stok: <?= $p['stok'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" id="inputQty" value="1" min="1"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="tambahKeKeranjang()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <!-- Produk populer / cepat -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-2">Pilih cepat:</p>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (array_slice($produk, 0, 5) as $p): ?>
                            <button type="button"
                                    onclick="pilihCepat(<?= $p['id_product'] ?>)"
                                    class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-xs text-gray-700 transition-colors">
                                <?= bersih($p['nama']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Keranjang Belanja -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Keranjang Belanja</h2>
                    <span id="cartCount" class="text-sm text-gray-500">0 item</span>
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
                        <tbody id="cartBody" class="divide-y divide-gray-100">
                            <tr id="cartEmpty">
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                    <i class="fas fa-shopping-cart text-2xl mb-2 block"></i>
                                    Keranjang masih kosong
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Ringkasan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="ringkasanSubtotal" class="font-medium">Rp 0</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                        <span class="text-base font-bold text-gray-800">Grand Total</span>
                        <span id="ringkasanTotal" class="text-lg font-bold text-blue-600">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <form action="proses.php" method="POST" id="formTransaksi" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h2>
                <input type="hidden" name="cart" id="inputCart">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan (opsional)</label>
                        <select name="id_pelanggan" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            <option value="">-- Umum (Non-member) --</option>
                            <?php foreach ($pelanggan as $p): ?>
                                <option value="<?= $p['id_pelanggan'] ?>"><?= bersih($p['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Uang Dibayar</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" name="bayar" id="inputBayar" min="0" placeholder="0"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                   oninput="hitungKembalian()">
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Kembalian</span>
                            <span id="infoKembalian" class="text-xl font-bold text-blue-700">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" id="btnProses"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-3 rounded-xl font-semibold shadow-sm transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Proses Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// ============ STATE CART ============
let cart = [];  // { id_product, nama, harga, stok, qty, subtotal }

// ============ HELPERS ============
function formatRupiah(angka) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
}

function tambahKeKeranjang() {
    const select = document.getElementById('selectProduk');
    const qty    = parseInt(document.getElementById('inputQty').value) || 0;

    if (!select.value) { alert('Pilih produk dulu.'); return; }
    if (qty < 1)       { alert('Jumlah minimal 1.'); return; }

    const opt     = select.options[select.selectedIndex];
    const id      = parseInt(select.value);
    const nama    = opt.dataset.nama;
    const harga   = parseInt(opt.dataset.harga);
    const stok    = parseInt(opt.dataset.stok);

    // Cek jika sudah ada di cart
    const existing = cart.find(i => i.id_product === id);
    const totalQty = (existing ? existing.qty : 0) + qty;

    if (totalQty > stok) {
        alert('Stok tidak mencukupi. Stok tersedia: ' + stok);
        return;
    }

    if (existing) {
        existing.qty = totalQty;
        existing.subtotal = existing.qty * existing.harga;
    } else {
        cart.push({ id_product: id, nama, harga, stok, qty, subtotal: harga * qty });
    }

    // Reset input
    select.value = '';
    document.getElementById('inputQty').value = 1;

    renderCart();
}

function pilihCepat(id) {
    document.getElementById('selectProduk').value = id;
    document.getElementById('inputQty').focus();
}

function hapusItem(id) {
    cart = cart.filter(i => i.id_product !== id);
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cartBody');

    if (cart.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                    <i class="fas fa-shopping-cart text-2xl mb-2 block"></i>
                    Keranjang masih kosong
                </td>
            </tr>`;
    } else {
        tbody.innerHTML = cart.map(item => `
            <tr>
                <td class="px-6 py-4">${item.nama}</td>
                <td class="px-6 py-4 text-center">${item.qty}</td>
                <td class="px-6 py-4 text-right font-medium text-blue-600">${formatRupiah(item.subtotal)}</td>
                <td class="px-6 py-4 text-center">
                    <button type="button" onclick="hapusItem(${item.id_product})"
                            class="text-red-500 hover:text-red-700 transition-colors">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    const total     = cart.reduce((sum, i) => sum + i.subtotal, 0);
    const itemCount = cart.reduce((sum, i) => sum + i.qty, 0);

    document.getElementById('cartCount').textContent         = itemCount + ' item';
    document.getElementById('ringkasanSubtotal').textContent = formatRupiah(total);
    document.getElementById('ringkasanTotal').textContent    = formatRupiah(total);
    document.getElementById('inputCart').value               = JSON.stringify(cart);

    hitungKembalian();
}

function hitungKembalian() {
    const total = cart.reduce((sum, i) => sum + i.subtotal, 0);
    const bayar = parseInt(document.getElementById('inputBayar').value) || 0;
    const kembali = bayar - total;

    document.getElementById('infoKembalian').textContent =
        formatRupiah(kembali >= 0 ? kembali : 0);
}

// ============ SUBMIT ============
document.getElementById('formTransaksi').addEventListener('submit', function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert('Keranjang masih kosong.');
        return;
    }
    const total = cart.reduce((sum, i) => sum + i.subtotal, 0);
    const bayar = parseInt(document.getElementById('inputBayar').value) || 0;
    if (bayar < total) {
        e.preventDefault();
        alert('Uang dibayar kurang dari total.');
        return;
    }
});

// Init
renderCart();
</script>

<?php include '../layouts/footer.php'; ?>