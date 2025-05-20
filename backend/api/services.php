<?php
require_once '../config/db.php';

$sql = "SELECT id, name, description, price, duration_minutes FROM services";
$result = $conn->query($sql);

$services = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($services);

$conn->close();
?>