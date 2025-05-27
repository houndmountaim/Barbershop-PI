<?php
// barber.php

// Header JSON
header("Content-Type: application/json");

// Memuat file koneksi database
require_once __DIR__ . "/db.php";

// Mendapatkan metode HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Mendapatkan semua barber atau satu barber berdasarkan ID
        if (isset($_GET['id'])) {
            getBarberById($pdo, $_GET['id']);
        } else {
            getAllBarbers($pdo);
        }
        break;

    case 'POST':
        // Menambahkan barber baru
        createBarber($pdo);
        break;

    case 'PUT':
        // Mengupdate barber berdasarkan ID
        updateBarber($pdo);
        break;

    case 'DELETE':
        // Menghapus barber berdasarkan ID
        deleteBarber($pdo);
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Metode HTTP tidak didukung"]);
        break;
}

// Fungsi untuk mendapatkan semua barber
function getAllBarbers($pdo) {
    $sql = "SELECT * FROM barber";
    $stmt = $pdo->query($sql);
    $barbers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["barbers" => $barbers]);
}

// Fungsi untuk mendapatkan barber berdasarkan ID
function getBarberById($pdo, $id) {
    $sql = "SELECT * FROM barber WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $barber = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($barber) {
        echo json_encode(["barber" => $barber]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Barber tidak ditemukan"]);
    }
}

// Fungsi untuk menambahkan barber baru
function createBarber($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['email']) || !isset($data['phone'])) {
        http_response_code(400);
        echo json_encode(["error" => "Data tidak lengkap"]);
        return;
    }

    $sql = "INSERT INTO barber (name, email, phone) VALUES (:name, :email, :phone)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone']
    ]);

    if ($result) {
        http_response_code(201);
        echo json_encode(["message" => "Barber berhasil ditambahkan"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menambahkan barber"]);
    }
}

// Fungsi untuk mengupdate barber berdasarkan ID
function updateBarber($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID barber harus disertakan"]);
        return;
    }

    $sql = "UPDATE barber SET name = :name, email = :email, phone = :phone WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'id' => $data['id'],
        'name' => $data['name'] ?? null,
        'email' => $data['email'] ?? null,
        'phone' => $data['phone'] ?? null
    ]);

    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Barber berhasil diperbarui"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal memperbarui barber"]);
    }
}

// Fungsi untuk menghapus barber berdasarkan ID
function deleteBarber($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID barber harus disertakan"]);
        return;
    }

    $sql = "DELETE FROM barber WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(['id' => $data['id']]);

    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Barber berhasil dihapus"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menghapus barber"]);
    }
}
?>