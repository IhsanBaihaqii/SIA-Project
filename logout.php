<?php

session_start();

// Hapus semua data session
$_SESSION = [];

// hapus session
session_destroy();

// Kembali ke halaman login
header("Location: login.php");
exit;