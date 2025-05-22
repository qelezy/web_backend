<?php
namespace App\Controller;

use App\Model\User;
use Twig\Environment;

class AuthController {
    private User $userModel;
    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->userModel = new User();
        $this->twig = $twig;
    }

    public function signupForm(): void {
        echo $this->twig->render("/auth/signup.twig");
    }

    public function loginForm(): void {
        echo $this->twig->render("/auth/login.twig");
    }

    public function authCheck(): void {
        session_start();
        $phone = strip_tags($_POST["phone"] ?? "");
        $password = strip_tags($_POST["password"] ?? "");
        if (empty($phone) || empty($password)) {
            exit(json_encode([
                "success" => false,
                "message" => "Заполните обязательные поля ввода"
            ]));
        }
        $phoneFormat = "/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/";
        if (!preg_match($phoneFormat, $phone)) {
            exit(json_encode([
                "success" => false,
                "message" => "Введены некорректные данные"
            ]));
        }
        $user = $this->userModel->findByPhone($phone);
        if ($user && password_verify($password, $user["user_password"])) {
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["role"] = $user["user_role"];
            if ($user["user_role"] === "admin") {
                $redirect = "/dashboard";
            } else {
                $redirect = "/profile";
            }
            echo json_encode([
                "success" => true,
                "redirect" => $redirect
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Неверный номер телефона или пароль"
            ]);
        }
    }

    public function signup(): void {
        session_start();
        $lastName = strip_tags($_POST["last_name"] ?? "");
        $firstName = strip_tags($_POST["first_name"] ?? "");
        $surname = strip_tags($_POST["surname"] ?? "");
        $phone = strip_tags($_POST["phone"] ?? "");
        $password = strip_tags($_POST["password"] ?? "");
        $repeatPassword = strip_tags($_POST["repeat_password"] ?? "");
        if (empty($lastName) || empty($firstName) || empty($phone) || empty($password) || empty($repeatPassword)) {
            echo json_encode([
                "success" => false, 
                "message" => "Заполните обязательные поля ввода"
            ]);
        }
        if ($password !== $repeatPassword) {
            echo json_encode([
                "success" => false, 
                "message" => "Пароли не совпадают"
            ]);
        }
        $phoneFormat = "/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/";
        if (!preg_match($phoneFormat, $phone)) {
            exit(json_encode([
                "success" => false,
                "message" => "Введены некорректные данные"
            ]));
        }
        $user = $this->userModel->findByPhone($phone);
        if ($user) {
            echo json_encode([
                "success" => false, 
                "message" => "Номер телефона уже зарегистрирован"
            ]);
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = "client";
        if ($this->userModel->add($lastName, $firstName, $surname, $phone, $hashedPassword, $role)) {
            $newUser = $this->userModel->findByPhone($phone);
            $_SESSION["user_id"] = $newUser["user_id"];
            $_SESSION["role"] = $role;
            echo json_encode([
                "success" => true,
                "redirect" => "/profile"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Ошибка при выполнении запроса"
            ]);
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        echo json_encode(["success" => true]);
    }
}
?>