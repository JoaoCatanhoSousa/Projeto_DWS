<?php

namespace App\Controllers;

class AboutUsController
{
    public function index($request, $response, $args)
    {
        // Lógica para a página inicial
        include __DIR__ . '/../Views/changePassword.php';
        return $response;
    }
}

?>