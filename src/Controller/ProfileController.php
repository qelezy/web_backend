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
        if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "client") {
            header("Location: /login");
            exit;
        }
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        $bookings = $this->userModel->getBookings($userId);

        echo $this->twig->render("/auth/profile.twig", [
            "user" => $user,
            "bookings" => $bookings
        ]);
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