<?php
    include '../config/koneksi.php';

    // Contoh untuk mengambil data
    $query = "SELECT * FROM tbl_user WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($query);

    // mengubah username menjadi admin
    // username didapatkan dari query diatas (:username) yg ada titik 2 nya
    // Berarti query berubah menjadi
    // SELECT * FROM tbl_user WHERE username = 'admin' LIMIT 1
    $stmt->execute([
        "username" => "admin"
    ]);

    // var_dump berfungsi sebagai pengecekan, seperti echo
    var_dump($stmt->fetch());

?>
<p>Halaman Dashboard</p>