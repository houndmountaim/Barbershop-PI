<?php
// api/barber_service.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class BarberService {
    private $conn;
    private $table_name = "barber_service";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ASSIGN SERVICE TO BARBER
    public function assign_service($barber_id, $service_id) {
        // Check if relation already exists
        $check_query = "SELECT * FROM " . $this->table_name . " 
                        WHERE barber_id = :barber_id AND service_id = :service_id";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":barber_id", $barber_id);
        $check_stmt->bindParam(":service_id", $service_id);
        $check_stmt->execute();
        
        if($check_stmt->rowCount() > 0) {
            return [
                'status' => 'error',
                'message' => 'Service already assigned to this barber'
            ];
        }
        
        $query = "INSERT INTO " . $this->table_name . " (barber_id, service_id) 
                  VALUES (:barber_id, :service_id)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":barber_id", $barber_id);
        $stmt->bindParam(":service_id", $service_id);
        
        if($stmt->execute()) {
            return [
                'status' => 'success',
                'message' => 'Service assigned to barber successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to assign service to barber'
        ];
    }

    // GET BARBER'S SERVICES
    public function get_barber_services($barber_id) {
        $query = "SELECT bs.*, s.name, s.description, s.price, s.duration_minutes 
                  FROM " . $this->table_name . " bs
                  INNER JOIN services s ON bs.service_id = s.id
                  WHERE bs.barber_id = :barber_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":barber_id", $barber_id);
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

    // GET SERVICE'S BARBERS
    public function get_service_barbers($service_id) {
        $query = "SELECT bs.*, b.name, b.description, b.phone, b.email, b.picture 
                  FROM " . $this->table_name . " bs
                  INNER JOIN barber b ON bs.barber_id = b.id
                  WHERE bs.service_id = :service_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":service_id", $service_id);
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

    // REMOVE SERVICE FROM BARBER
    public function remove_service($barber_id, $service_id) {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE barber_id = :barber_id AND service_id = :service_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":barber_id", $barber_id);
        $stmt->bindParam(":service_id", $service_id);
        
        if($stmt->execute() && $stmt->rowCount() > 0) {
            return [
                'status' => 'success',
                'message' => 'Service removed from barber successfully'
            ];
        }
        
        return [
            'status' => 'error',
            'message' => 'Unable to remove service from barber or relation not found'
        ];
    }

    // GET ALL RELATIONS
    public function get_all_relations() {
        $query = "SELECT bs.barber_id, bs.service_id, 
                         b.name as barber_name, s.name as service_name,
                         s.price, s.duration_minutes
                  FROM " . $this->table_name . " bs
                  INNER JOIN barber b ON bs.barber_id = b.id
                  INNER JOIN services s ON bs.service_id = s.id
                  ORDER BY b.name, s.name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $relations = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $relations[] = $row;
        }
        
        return [
            'status' => 'success',
            'data' => $relations
        ];
    }
}

// Handle API requests
$database = new Database();
$db = $database->getConnection();
$barber_service = new BarberService($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['barber_id'])) {
            $result = $barber_service->get_barber_services($_GET['barber_id']);
        } elseif(isset($_GET['service_id'])) {
            $result = $barber_service->get_service_barbers($_GET['service_id']);
        } else {
            $result = $barber_service->get_all_relations();
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if(isset($data['barber_id']) && isset($data['service_id'])) {
            $result = $barber_service->assign_service($data['barber_id'], $data['service_id']);
        } else {
            $result = ['status' => 'error', 'message' => 'Barber ID and Service ID are required'];
        }
        break;
        
    case 'DELETE':
        if(isset($_GET['barber_id']) && isset($_GET['service_id'])) {
            $result = $barber_service->remove_service($_GET['barber_id'], $_GET['service_id']);
        } else {
            $result = ['status' => 'error', 'message' => 'Barber ID and Service ID are required'];
        }
        break;
        
    default:
        $result = ['status' => 'error', 'message' => 'Method not allowed'];
        break;
}

echo json_encode($result);
?>