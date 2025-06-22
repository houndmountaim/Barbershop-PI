<?php
require_once '../config/db.php';

$sql = "SELECT id, name, description, phone, email, picture FROM barber";
$result = $conn->query($sql);

$barber = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $barber[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($barber);

$conn->close();
?>