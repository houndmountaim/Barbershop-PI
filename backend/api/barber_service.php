<?php
// barber_service.php

header("Content-Type: application/json");
require_once __DIR__ . "/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getBarberServiceById($pdo, $_GET['id']);
        } else {
            getAllBarberServices($pdo);
        }
        break;

    case 'POST':
        createBarberService($pdo);
        break;

    case 'PUT':
        updateBarberService($pdo);
        break;

    case 'DELETE':
        deleteBarberService($pdo);
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Metode HTTP tidak didukung"]);
        break;
}

// Ambil semua relasi barber-service
function getAllBarberServices($pdo) {
    $sql = "SELECT bs.*, b.name AS barber_name, s.name AS service_name 
            FROM barber_service bs
            JOIN barber b ON bs.barber_id = b.id
            JOIN services s ON bs.service_id = s.id";
    $stmt = $pdo->query($sql);
    $relations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["barber_services" => $relations]);
}

// Ambil relasi berdasarkan ID
function getBarberServiceById($pdo, $id) {
    $sql = "SELECT bs.*, b.name AS barber_name, s.name AS service_name 
            FROM barber_service bs
            JOIN barber b ON bs.barber_id = b.id
            JOIN services s ON bs.service_id = s.id
            WHERE bs.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $relation = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($relation) {
        echo json_encode(["barber_service" => $relation]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Relasi barber-service tidak ditemukan"]);
    }
}

// Tambah relasi baru
function createBarberService($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['barber_id']) || !isset($data['service_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Barber ID dan Service ID harus disertakan"]);
        return;
    }

    $sql = "INSERT INTO barber_service (barber_id, service_id) VALUES (:barber_id, :service_id)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'barber_id' => $data['barber_id'],
        'service_id' => $data['service_id']
    ]);

    if ($result) {
        http_response_code(201);
        echo json_encode(["message" => "Relasi barber-service berhasil ditambahkan"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menambahkan relasi"]);
    }
}

// Update relasi
function updateBarberService($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID relasi harus disertakan"]);
        return;
    }

    $sql = "UPDATE barber_service SET barber_id = :barber_id, service_id = :service_id WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'id' => $data['id'],
        'barber_id' => $data['barber_id'],
        'service_id' => $data['service_id']
    ]);

    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Relasi barber-service berhasil diperbarui"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal memperbarui relasi"]);
    }
}

// Hapus relasi
function deleteBarberService($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID relasi harus disertakan"]);
        return;
    }

    $sql = "DELETE FROM barber_service WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(['id' => $data['id']]);

    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Relasi barber-service berhasil dihapus"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menghapus relasi"]);
    }
}
?>