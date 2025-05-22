<?php
namespace App\Model;

use App\Core\Database;
use PDO;

class Schedule {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM schedules");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGroup(): array {
        $stmt = $this->pdo->query("
            SELECT 
                schedules.schedule_id AS id,
                schedules.schedule_datetime AS datetime,
                CONCAT(trainers.trainer_last_name, ' ', trainers.trainer_first_name) AS trainer_name,
                trainers.trainer_photo
            FROM schedules
            JOIN trainers ON schedules.trainer_id = trainers.trainer_id
            WHERE schedules.schedule_type = 'group'
            ORDER BY schedules.schedule_datetime
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(string $trainerId, string $datetime, string $type): bool {
        $stmt = $this->pdo->prepare("INSERT INTO schedules (trainer_id, schedule_datetime, schedule_type) VALUES (?, ?, ?)");
        return $stmt->execute([$trainerId, $datetime, $type]);
    }

    public function getType(string $scheduleId) {
        $stmt = $this->pdo->prepare("SELECT schedule_type FROM schedules WHERE schedule_id = ?");
        $stmt->execute([$scheduleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLastId() {
        return $this->pdo->lastInsertId();
    }

    public function delete(string $scheduleId) {
        $stmt = $this->pdo->prepare("DELETE FROM schedules WHERE schedule_id = ?");
        return $stmt->execute([$scheduleId]);
    }
}
?>