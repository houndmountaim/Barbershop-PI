<?php

$host = 'localhost';        
$user = 'root';         
$password = '';         
$dbname = 'barbershop'; 

try {
    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Koneksi gagal: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die(json_encode(['error' => $e->getMessage()]));
}

$conn->set_charset("utf8mb4");

return $conn;
?>