<?php
// Proses login, tidak lengkap
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses autentikasi
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek kredensial

    // Bila berhasil, redirect
    header("Location: dashboard.php");
    exit();
}
?>
