<?php
require_once('../config/db.php');
require_once('../models/Booking.php');

class BookingController {
    private $booking;

    public function __construct($pdo) {
        $this->booking = new Booking($pdo);
    }

    public function createBooking($data) {
        return $this->booking->createBooking($data);
    }

    public function showBookings() {
        return $this->booking->getBookings();
    }
}
?>
