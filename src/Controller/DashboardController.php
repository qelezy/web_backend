<?php
namespace App\Controller;

use App\Model\User;
use Twig\Environment;

class DashboardController {
    private User $userModel;
    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->userModel = new User();
        $this->twig = $twig;
    }

    public function index(): void {
        session_start();
        if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
            header("Location: /login");
            exit;
        }
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        echo $this->twig->render("/dashboard.twig", [
            "user" => $user
        ]);
    }
}
?>