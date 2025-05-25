<?php
namespace App\Controller;

use App\Model\User;
use Twig\Environment;

class UserController {
    private User $userModel;
    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->userModel = new User();
        $this->twig = $twig;
    }

    public function adminForm(): void {
        echo $this->twig->render("/users/admin_form.twig");
    }

    public function table(): void {
        $columnsMap = [
            "user_id" => "ID",
            "user_last_name" => "Фамилия",
            "user_first_name" => "Имя",
            "user_surname" => "Отчество",
            "user_phone" => "Номер телефона",
            "user_role" => "Роль"
        ];
        $users = $this->userModel->getAll();
        echo $this->twig->render("/users/users_table.twig", [
            "users" => $users,
            "columnsMap" => $columnsMap
        ]);
    }

    public function saveAdmin(): void {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $lastName = strip_tags($_POST["last_name"] ?? "");
            $firstName = strip_tags($_POST["first_name"] ?? "");
            $surname = strip_tags($_POST["surname"] ?? "");
            $phone = strip_tags($_POST["phone"] ?? "");
            $password = strip_tags($_POST["password"] ?? "");
            if (empty($lastName) || empty($firstName) || empty($phone) || empty($password) || empty($repeatPassword)) {
                echo json_encode([
                    "success" => false, 
                    "message" => "Заполните обязательные поля ввода"
                ]);
            }
            $phoneFormat = "/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/";
            if (!preg_match($phoneFormat, $phone)) {
                exit(json_encode([
                    "success" => false,
                    "message" => "Введены некорректные данные"
                ]));
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = "admin";
            $birthDate = empty($birthDate) ? null : $birthDate;
            if ($this->userModel->add($lastName, $firstName, $surname, $phone, $hashedPassword, $role)) {
                echo json_encode(["message" => "Администратор успешно добавлен"]);
            } else {
                echo json_encode(["message" => "Ошибка при выполнении запроса"]);
            }
        }
    }
}
?>