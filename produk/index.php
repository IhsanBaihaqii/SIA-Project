<?php
include '../config/koneksi.php';

$query = "SELECT * FROM tbl_products";
$stmt = $pdo->prepare($query);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
include '../layouts/header.php';
include '../layouts/sidebar.php';
include '../layouts/navbar.php';
?>
<main class="md:ml-64 pt-16 min-h-screen">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800">Ini halaman Produk</h1>
    </div>
</main>
<?php include '../layouts/footer.php'; ?>