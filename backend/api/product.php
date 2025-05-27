<?php
require_once '../config/db.php';

$sql = "SELECT id, name, description, price, stock, picture FROM products";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
?><?php
// products.php

header("Content-Type: application/json");
require_once __DIR__ . "/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getProductById($pdo, $_GET['id']);
        } else {
            getAllProducts($pdo);
        }
        break;

    case 'POST':
        createProduct($pdo);
        break;

    case 'PUT':
        updateProduct($pdo);
        break;

    case 'DELETE':
        deleteProduct($pdo);
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Metode HTTP tidak didukung"]);
        break;
}

// Ambil semua produk
function getAllProducts($pdo) {
    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["products" => $products]);
}

// Ambil produk berdasarkan ID
function getProductById($pdo, $id) {
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        echo json_encode(["product" => $product]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Produk tidak ditemukan"]);
    }
}

// Tambah produk baru
function createProduct($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['price']) || !isset($data['stock'])) {
        http_response_code(400);
        echo json_encode(["error" => "Nama, harga, dan stok harus disertakan"]);
        return;
    }

    $sql = "INSERT INTO products (name, description, price, stock) VALUES (:name, :description, :price, :stock)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'name' => $data['name'],
        'description' => $data['description'] ?? null,
        'price' => $data['price'],
        'stock' => $data['stock']
    ]);

    if ($result) {
        http_response_code(201);
        echo json_encode(["message" => "Produk berhasil ditambahkan"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menambahkan produk"]);
    }
}

// Update produk berdasarkan ID
function updateProduct($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID produk harus disertakan"]);
        return;
    }

    $sql = "UPDATE products SET name = :name, description = :description, price = :price, stock = :stock WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'id' => $data['id'],
        'name' => $data['name'] ?? null,
        'description' => $data['description'] ?? null,
        'price' => $data['price'] ?? null,
        'stock' => $data['stock'] ?? null
    ]);

    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Produk berhasil diperbarui"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal memperbarui produk"]);
    }
}

// Hapus produk berdasarkan ID
function deleteProduct($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID produk harus disertakan"]);
        return;
    }

    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(['id' => $data['id']]);

    if ($result) {
        http_response_code(200);
        echo json_encode(["message" => "Produk berhasil dihapus"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menghapus produk"]);
    }
}
?>