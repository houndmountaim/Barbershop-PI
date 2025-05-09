<?php
require_once('../config/db.php');
require_once('../controllers/BookingController.php');

header('Content-Type: application/json');

$bookingController = new BookingController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $bookingController->createBooking($data);
    echo json_encode(['id' => $id]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $bookings = $bookingController->showBookings();
    echo json_encode($bookings);
} else {
    echo json_encode(['message' => 'Method not allowed']);
}
?>
