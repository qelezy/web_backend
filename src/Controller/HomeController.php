<?php
namespace App\Controller;

use Twig\Environment;

class HomeController {
    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function index(): void {
        echo $this->twig->render("home.twig");
    }
}
?>