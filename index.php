<?php
include 'config/koneksi.php';
 session_start();
  if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
  } else {
    header("Location: dashboard/");
    exit;
  }
?>