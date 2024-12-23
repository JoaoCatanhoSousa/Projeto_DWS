<?php

namespace App\Controllers;

class LogInController
{
    public function index($request, $response, $args)
    {
        // Lógica para a página inicial
        include __DIR__ . '/../Views/logIn.php';
        return $response;
    }
}

?>