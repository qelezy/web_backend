<?php
namespace App\Model;

use App\Core\Database;
use PDO;

class Booking {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM bookings");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScheduleId(string $bookingId) {
        $stmt = $this->pdo->prepare("SELECT schedule_id FROM bookings WHERE booking_id = ?");
        return $stmt->execute([$bookingId]);
    }

    public function add(string $userId, string $scheduleId): bool {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (user_id, schedule_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $scheduleId]);
    }

    public function exists(string $userId, string $scheduleId): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM bookings 
            WHERE booking_id = ? AND schedule_id = ?
        ");
        $stmt->execute([$userId, $scheduleId]);
        return intval($stmt->fetchColumn()) > 0;
    }

    public function delete(string $bookingId): bool {
        $stmt = $this->pdo->prepare("
            DELETE FROM bookings 
            WHERE booking_id = ?
        ");
        return $stmt->execute([$bookingId]);
    }
}
?>