<?php

namespace App\Controllers;

class TermsController
{
    public function index($request, $response, $args)
    {
        // Lógica para a página inicial
        include __DIR__ . '/../Views/terms.php';
        return $response;
    }
}

?>