<?php
// api/services.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Services {
    private $conn;
    private $table_name = "services";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, duration_minutes) 
                  VALUES (:name, :description, :price, :duration_minutes)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":duration_minutes", $data['duration_minutes']);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Service created successfully',
                'id' => $this->conn->lastInsertId()
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to create service'
        ];
    }

    // READ ALL
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $services = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $services[] = $row;
        }
        
        return [
            'status' => 'success',
            'data' => $services
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
            'message' => 'Service not found'
        ];
    }

    // UPDATE
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, description = :description, price = :price, 
                      duration_minutes = :duration_minutes 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":duration_minutes", $data['duration_minutes']);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Service updated successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to update service'
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
                'message' => 'Service deleted successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to delete service'
        ];
    }
}

// Handle API requests
$database = new Database();
$db = $database->getConnection();
$services = new Services($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            $result = $services->read_single($_GET['id']);
        } else {
            $result = $services->read();
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $services->create($data);
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $_GET['id'] ?? null;
        if($id) {
            $result = $services->update($id, $data);
        } else {
            $result = ['status' => 'error', 'message' => 'ID is required'];
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if($id) {
            $result = $services->delete($id);
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