<?php
namespace App\Model;

use App\Core\Database;
use PDO;

class User {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT user_id, user_last_name, user_first_name, user_surname, user_phone, user_role FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(string $lastName, string $firstName, ?string $surname, string $phone, string $password, string $role): bool {
        $stmt = $this->pdo->prepare("INSERT INTO users (user_last_name, user_first_name, user_surname, user_phone, user_password, user_role) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$lastName, $firstName, $surname, $phone, $password, $role]);
    }

    public function findByPhone(string $phone) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(string $id) {
        $stmt = $this->pdo->prepare("SELECT user_last_name AS last_name, user_first_name AS first_name, user_surname AS surname, user_phone AS phone, user_role AS role FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookings(string $id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                b.booking_id AS id,
                s.schedule_datetime AS datetime,
                t.trainer_photo,
                CONCAT(t.trainer_last_name, ' ', t.trainer_first_name) AS trainer_name,
                t.trainer_specialization AS specialization
            FROM bookings b
            JOIN schedules s ON b.schedule_id = s.schedule_id
            JOIN trainers t ON s.trainer_id = t.trainer_id
            WHERE b.user_id = ?
            ORDER BY s.schedule_datetime
        ");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>