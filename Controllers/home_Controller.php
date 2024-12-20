<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // Lógica para a página inicial
        include __DIR__ . '/../Views/home.php';
    }
}

?>