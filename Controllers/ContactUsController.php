<?php

namespace App\Controllers;

class ContactUsController
{
    public function index($request, $response, $args)
    {
        // Lógica para a página inicial
        include __DIR__ . '/../Views/contactUs.php';
        return $response;
    }
}

?>