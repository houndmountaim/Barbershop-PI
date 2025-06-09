<?php
// api/barber.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Barber {
    private $conn;
    private $table_name = "barber";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (name, description, phone, email, picture) 
                  VALUES (:name, :description, :phone, :email, :picture)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":picture", $data['picture']);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Barber created successfully',
                'id' => $this->conn->lastInsertId()
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to create barber'
        ];
    }

    // READ ALL
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $barbers = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $barbers[] = $row;
        }
        
        return [
            'status' => 'success',
            'data' => $barbers
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
            'message' => 'Barber not found'
        ];
    }

    // UPDATE
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, description = :description, phone = :phone, 
                      email = :email, picture = :picture 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":picture", $data['picture']);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Barber updated successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to update barber'
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
                'message' => 'Barber deleted successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to delete barber'
        ];
    }
}

// Handle API requests
$database = new Database();
$db = $database->getConnection();
$barber = new Barber($db);

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = explode('/', $path);

switch($method) {
    case 'GET':
        if(isset($_GET['id'])) {
            $result = $barber->read_single($_GET['id']);
        } else {
            $result = $barber->read();
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $barber->create($data);
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $_GET['id'] ?? null;
        if($id) {
            $result = $barber->update($id, $data);
        } else {
            $result = ['status' => 'error', 'message' => 'ID is required'];
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if($id) {
            $result = $barber->delete($id);
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