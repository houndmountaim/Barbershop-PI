<?php
// api/products.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Products {
    private $conn;
    private $table_name = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, stock) 
                  VALUES (:name, :description, :price, :stock)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":stock", $data['stock']);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Product created successfully',
                'id' => $this->conn->lastInsertId()
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to create product'
        ];
    }

    // READ ALL
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $products = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        
        return [
            'status' => 'success',
            'data' => $products
        ];
    }

    // READ ONE
    public function read_single($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'status' => 'success',
                'data' => $row
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Product not found'
        ];
    }

    // UPDATE
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, description = :description, price = :price, stock = :stock 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":stock", $data['stock']);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Product updated successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to update product'
        ];
    }

    // DELETE
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to delete product'
        ];
    }

    // SEARCH
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE name LIKE :keyword OR description LIKE :keyword 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        
        $products = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        
        return [
            'status' => 'success',
            'data' => $products
        ];
    }

    // UPDATE STOCK
    public function update_stock($id, $stock) {
        $query = "UPDATE " . $this->table_name . " SET stock = :stock WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":stock", $stock);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Stock updated successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to update stock'
        ];
    }
}

// Handle API requests
$database = new Database();
$db = $database->getConnection();
$products = new Products($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['search'])) {
            $result = $products->search($_GET['search']);
        } elseif(isset($_GET['id'])) {
            $result = $products->read_single($_GET['id']);
        } else {
            $result = $products->read();
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $products->create($data);
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $_GET['id'] ?? null;
        if($id) {
            if(isset($data['stock_only'])) {
                $result = $products->update_stock($id, $data['stock']);
            } else {
                $result = $products->update($id, $data);
            }
        } else {
            $result = ['status' => 'error', 'message' => 'ID is required'];
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if($id) {
            $result = $products->delete($id);
        } else {
            $result = ['status' => 'error', 'message' => 'ID is required'];
        }
        break;
        
    default:
        $result = ['status' => 'error', 'message' => 'Method not allowed'];
        break;
}

echo json_encode($result);
?>