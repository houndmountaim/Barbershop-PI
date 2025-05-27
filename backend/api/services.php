<?php
// services.php

header("Content-Type: application/json");
require_once __DIR__ . "/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getServiceById($pdo, $_GET['id']);
        } else {
            getAllServices($pdo);
        }
        break;

    case 'POST':
        createService($pdo);
        break;

    case 'PUT':
        updateService($pdo);
        break;

    case 'DELETE':
        deleteService($pdo);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Metode HTTP tidak didukung"]);
        break;
}

function getAllServices($pdo) {
    $sql = "SELECT * FROM services";
    $stmt = $pdo->query($sql);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["services" => $services]);
}

function getServiceById($pdo, $id) {
    $sql = "SELECT * FROM services WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($service) {
        echo json_encode(["service" => $service]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Layanan tidak ditemukan"]);
    }
}

function createService($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(["error" => "Nama dan harga layanan harus disertakan"]);
        return;
    }

    $sql = "INSERT INTO services (name, description, price, duration_minutes) VALUES (:name, :description, :price, :duration_minutes)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'name' => $data['name'],
        'description' => $data['description'] ?? null,
        'price' => $data['price'],
        'duration_minutes' => $data['duration_minutes'] ?? null
    ]);

    if ($result) {
        http_response_code(201);
        echo json_encode(["message" => "Layanan berhasil ditambahkan"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menambahkan layanan"]);
    }
}

function updateService($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID layanan harus disertakan"]);
        return;
    }

    $sql = "UPDATE services SET name = :name, description = :description, price = :price, duration_minutes = :duration_minutes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'id' => $data['id'],
        'name' => $data['name'] ?? null,
        'description' => $data['description'] ?? null,
        'price' => $data['price'] ?? null,
        'duration_minutes' => $data['duration_minutes'] ?? null
    ]);

    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Layanan berhasil diperbarui"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal memperbarui layanan"]);
    }
}

function deleteService($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID layanan harus disertakan"]);
        return;
    }

    $sql = "DELETE FROM services WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(['id' => $data['id']]);

    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Layanan berhasil dihapus"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menghapus layanan"]);
    }
}
?>