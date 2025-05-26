<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Controller\HomeController;
use App\Controller\BookingController;
use App\Controller\AuthController;
use App\Controller\ProfileController;
use App\Controller\UserController;
use App\Controller\TrainerController;
use App\Controller\ScheduleController;
use App\Controller\ReportController;
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
$userController = new UserController($twig);
$trainerController = new TrainerController($twig);
$scheduleController = new ScheduleController($twig);
$reportController = new ReportController($twig);

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

$router->get("/users", [$userController, "table"]);
$router->get("/admins/add", [$userController, "adminForm"]);
$router->post("/admins/add", [$userController, "saveAdmin"]);

$router->get("/trainers", [$trainerController, "table"]);
$router->get("/trainers/add", [$trainerController, "form"]);
$router->post("/trainers/add", [$trainerController, "save"]);

$router->get("/schedules", [$scheduleController, "table"]);
$router->get("/schedules/add", [$scheduleController, "form"]);
$router->post("/schedules/add", [$scheduleController, "save"]);

$router->post("/report", [$reportController, "generateReport"]);

$router->resolve();

?>