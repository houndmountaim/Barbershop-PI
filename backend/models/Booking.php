<?php
class Booking {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createBooking($data) {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (field1, field2) VALUES (:field1, :field2)");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function getBookings() {
        $stmt = $this->pdo->query("SELECT * FROM bookings");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
