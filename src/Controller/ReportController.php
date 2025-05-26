<?php
namespace App\Controller;

use App\Model\User;
use App\Model\Schedule;
use App\Model\Trainer;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class ReportController {
    private User $userModel;
    private Schedule $scheduleModel;
    private Trainer $trainerModel;
    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->userModel = new User();
        $this->scheduleModel = new Schedule();
        $this->trainerModel = new Trainer();
        $this->twig = $twig;
    }

    public function generateReport(): void {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            if (!in_array($name, ["schedules", "trainers", "users"])) {
                exit;
            }
            if ($name == "schedules") {
                $reportName = "Тренировки";
                $data = $this->scheduleModel->getAll();
                $columnsMap = [
                    "schedule_id" => "ID",
                    "trainer_id" => "ID тренера",
                    "schedule_datetime" => "Дата и время",
                    "schedule_type" => "Тип"
                ];
            } else if ($name == "trainers") {
                $reportName = "Тренеры";
                $data = $this->trainerModel->getWithoutPhoto();
                $columnsMap = [
                    "trainer_id" => "ID",
                    "trainer_last_name" => "Фамилия",
                    "trainer_first_name" => "Имя",
                    "trainer_surname" => "Отчество",
                    "trainer_phone" => "Номер телефона",
                    "trainer_specialization" => "Специализация"
                ];
            } else if ($name == "users") {
                $reportName = "Пользователи";
                $data = $this->userModel->getAll();
                $columnsMap = [
                    "user_id" => "ID",
                    "user_last_name" => "Фамилия",
                    "user_first_name" => "Имя",
                    "user_surname" => "Отчество",
                    "user_phone" => "Номер телефона",
                    "user_role" => "Роль"
                ];
            }
            $html = $this->twig->render('pdf_template.twig', [
                "name" => $reportName,
                "data" => $data,
                "columnsMap" => $columnsMap
            ]);
            $options = new Options();
            $options->set("defaultFont", "DejaVu Sans");
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->render();
            echo $dompdf->output();
        }
    }
}
?>