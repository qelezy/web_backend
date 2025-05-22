<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Controller\HomeController;
use App\Controller\BookingController;
use App\Controller\AuthController;
use App\Controller\ProfileController;
use App\Controller\DashboardController;
use App\Controller\ClientController;
use App\Controller\TrainerController;
use App\Controller\TrainingSessionController;
use App\Core\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$router = new Router();

$loader = new FilesystemLoader(__DIR__ . '/../src/views');
$twig = new Environment($loader);

$homeController = new HomeController($twig);
$bookingController = new BookingController($twig);
$authController = new AuthController($twig);
$profileController = new ProfileController($twig);
$dashboardController = new DashboardController($twig);
$clientController = new ClientController($twig);
$trainerController = new TrainerController($twig);
$trainingSessionController = new TrainingSessionController($twig);

$router->get("/", [$homeController, "index"]);

$router->get("/booking", [$bookingController, "index"]);
$router->get("/book-redirect", [$bookingController, "redirectToBooking"]);
$router->post("/booking/existing", [$bookingController, "addToExisting"]);
$router->post("/booking/individual", [$bookingController, "addToIndividual"]);
$router->post("/booking/cancel", [$bookingController, "cancelBook"]);

$router->get("/profile", [$profileController, "index"]);
$router->get("/profile-redirect", [$profileController, "redirectToProfile"]);

$router->get("/login", [$authController, "loginForm"]);
$router->get("/signup", [$authController, "signupForm"]);
$router->post("/auth/check", [$authController, "authCheck"]);
$router->post("/auth/signup", [$authController, "signup"]);
$router->post("/auth/logout", [$authController, "logout"]);

$router->get("/dashboard", [$dashboardController, "index"]);

$router->get("/clients", [$clientController, "table"]);
$router->get("/clients/add", [$clientController, "form"]);
$router->post("/clients/add", [$clientController, "save"]);

$router->get("/trainers", [$trainerController, "table"]);
$router->get("/trainers/add", [$trainerController, "form"]);
$router->post("/trainers/add", [$trainerController, "save"]);

$router->get("/schedules", [$trainingSessionController, "table"]);
$router->get("/schedules/add", [$trainingSessionController, "form"]);
$router->post("/schedules/add", [$trainingSessionController, "save"]);

$router->resolve();

?>