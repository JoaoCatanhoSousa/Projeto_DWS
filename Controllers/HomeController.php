<?php

namespace App\Controllers;

class HomeController
{
    public function index($request, $response, $args)
    {
        // Lógica para a página inicial
        include __DIR__ . '/../Views/home.php';
        return $response;
    }
}

?>