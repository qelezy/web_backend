<?php
namespace App\Controller;

use App\Model\Schedule;
use App\Model\Trainer;
use Twig\Environment;

class ScheduleController {
    private Schedule $scheduleModel;
    private Trainer $trainerModel;
    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->scheduleModel = new Schedule();
        $this->trainerModel = new Trainer();
        $this->twig = $twig;
    }

    public function form(): void {
        $trainers = $this->trainerModel->getAll();
        echo $this->twig->render("/schedules/form.twig", [
            "trainers" => $trainers
        ]);
    }

    public function table(): void {
        $columnsMap = [
            "schedule_id" => "ID",
            "trainer_id" => "ID тренера",
            "schedule_datetime" => "Дата и время",
            "schedule_type" => "Тип"
        ];
        $schedules = $this->scheduleModel->getAll();
        echo $this->twig->render("/schedules/table.twig", [
            "schedules" => $schedules,
            "columnsMap" => $columnsMap
        ]);
    }

    public function save(): void {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $trainerId = strip_tags($_POST["trainer_id"] ?? "");
            $datetime = strip_tags($_POST["datetime"] ?? "");
            $type = strip_tags($_POST["type"] ?? "");
            if (empty($trainerId) || empty($datetime) || empty($type)) {
                exit(json_encode(["message" => "Заполните обязательные поля ввода"]));
            }
            $trainerIdInput = intval($trainerId);
            $datetimeInput = date_create($datetime);
            if (!$trainerIdInput || !$datetimeInput || !in_array($type, ["individual", "group"])) {
                exit(json_encode(["message" => "Введены некорректные данные"]));
            }
            if ($trainerId <= 0) {
                exit(json_encode(["message" => "Некорректный ID"]));
            }
            if ($this->scheduleModel->add($trainerId, $datetime, $type)) {
                echo json_encode(["message" => "Тренировка успешно добавлена"]);
            } else {
                echo json_encode(["message" => "Ошибка при выполнении запроса"]);
            }
        }
    }
}
?>