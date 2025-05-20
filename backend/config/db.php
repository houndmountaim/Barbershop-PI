<?php
$host = 'localhost';
$db = 'database_name'; // Ganti dengan nama database Anda
$user = 'username';     // Ganti dengan pengguna database Anda
$pass = 'password';     // Ganti dengan password pengguna database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
