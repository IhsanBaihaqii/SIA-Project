<?php
if (!function_exists('rupiah')) {
    function rupiah($angka) {
        return 'Rp ' . number_format((int)$angka, 0, ',', '.');
    }
}

if (!function_exists('bersih')) {
    function bersih($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>