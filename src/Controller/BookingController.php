<?php
namespace App\Controller;

use App\Model\Booking;
use App\Model\Schedule;
use App\Model\Trainer;
use Twig\Environment;

class BookingController {
    private Booking $bookingModel;
    private Trainer $trainerModel;
    private Schedule $scheduleModel;
    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->bookingModel = new Booking();
        $this->scheduleModel = new Schedule();
        $this->trainerModel = new Trainer();
        $this->twig = $twig;
    }

    public function index(): void {
        session_start();
        if (!isset($_SESSION["user_id"])) {
            header("Location: /login");
            exit;
        }
        $schedules = $this->scheduleModel->getAllGroup();
        $trainers = $this->trainerModel->getAll();
        echo $this->twig->render("/booking.twig", [
            "schedules" => $schedules,
            "trainers" => $trainers
        ]);
    }

    public function addToExisting() {
        session_start();

        if (!isset($_SESSION["user_id"])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION["user_id"];

        $scheduleId = strip_tags($_POST["schedule_id"] ?? "");

        if ($this->bookingModel->exists($userId, $scheduleId)) {
            echo json_encode([
                "success" => false,
                "message" => "Вы уже записаны на эту тренировку"
            ]);
            exit;
        }

        $success = $this->bookingModel->add($userId, $scheduleId);

        if ($success) {
            echo json_encode([
                "success" => true,
                "message" => "Вы успешно записались на тренировку"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Ошибка при записи"
            ]);
        }
    }

    public function addToIndividual() {
        session_start();

        if (!isset($_SESSION["user_id"])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION["user_id"];

        $datetime = strip_tags($_POST["datetime"] ?? "");
        $trainerId = strip_tags($_POST["trainer_id"] ?? "");

        if (empty($datetime) || empty($trainerId)) {
            echo json_encode([
                "success" => false,
                "message" => "Заполните обязательные поля ввода"
            ]);
            exit;
        }
        $datetimeInput = date_create($datetime);
        if (!$datetimeInput) {
            echo json_encode([
                "success" => false,
                "message" => "Введены некорректные данные"
            ]);
            exit;
        }
        $this->scheduleModel->add($trainerId, $datetime, "individual");
        $scheduleId = $this->scheduleModel->getLastId();
        $this->bookingModel->add($userId, $scheduleId);
        echo json_encode([
            "success" => true,
            "message" => "Вы успешно записались на тренировку"
        ]);
    }

    public function cancelBook() {
        $bookingId = strip_tags($_POST["booking_id"] ?? "");

        $scheduleId = $this->bookingModel->getScheduleId($bookingId);

        $type = $this->scheduleModel->getType($scheduleId);

        if ($type["schedule_type"] == "group") {
            $this->bookingModel->delete($bookingId);
        }
        if ($type["schedule_type"] == "individual") {
            $this->scheduleModel->delete($scheduleId);
        }
        echo json_encode([
            "success" => true,
            "message" => "Ваша запись успешно отменена"
        ]);
    }

    public function redirectToBooking(): void {
        session_start();
        if (isset($_SESSION["user_id"])) {
            header("Location: /booking");
        } else {
            header("Location: /login");
        }
    }
}
?>