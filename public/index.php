<?php
require __DIR__ . "/../vendor/autoload.php";

use App\Controller\MainController;
use App\Controller\TrainingSessionController;
use App\Core\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$router = new Router();

$loader = new FilesystemLoader(__DIR__ . '/../src/views');
$twig = new Environment($loader);

$mainController = new MainController($twig);
$trainingSessionController = new TrainingSessionController($twig);

$router->get("/", [$mainController, "index"]);

$router->get("/training_sessions", [$trainingSessionController, "table"]);
$router->get("/training_sessions/add", [$trainingSessionController, "form"]);
$router->post("/training_sessions/add", [$trainingSessionController, "save"]);
$router->post("/training_sessions/upload", [$trainingSessionController, "upload"]);

$router->resolve();

?>