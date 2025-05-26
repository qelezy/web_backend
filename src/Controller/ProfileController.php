<?php
namespace App\Controller;

use App\Model\User;
use Twig\Environment;

class ProfileController {
    private User $userModel;
    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->userModel = new User();
        $this->twig = $twig;
    }

    public function index(): void {
        session_start();
        if (!isset($_SESSION["user_id"])) {
            header("Location: /login");
            exit;
        }
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        if ($user["role"] == "client") {
            $bookings = $this->userModel->getBookings($userId);
            echo $this->twig->render("profile.twig", [
                "user" => $user,
                "bookings" => $bookings
            ]);
        } else if ($user["role"] == "admin") {
            echo $this->twig->render("profile.twig", [
                "user" => $user
            ]);
        }
    }

    public function redirectToProfile(): void {
        session_start();
        if (isset($_SESSION["user_id"])) {
            header("Location: /profile");
        } else {
            header("Location: /login");
        }
    }
}
?>