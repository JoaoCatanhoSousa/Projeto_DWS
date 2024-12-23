<?php

// Função para lidar com as rotas
function route($method, $path, $callback) {
    if ($_SERVER['REQUEST_METHOD'] === strtoupper($method) && $_SERVER['REQUEST_URI'] === $path) {
        $callback();
        exit;
    }
}

// Definir rotas
route('GET', '/', function() {
    echo "Hello, world!";
});

route('GET', '/login', function() {
    echo "Login Page";
});

route('GET', '/terms', function() {
    echo "Terms and Conditions";
});

route('GET', '/about', function() {
    echo "About Us";
});

route('GET', '/contact', function() {
    echo "Contact Us";
});

// Se nenhuma rota corresponder, mostrar erro 404
http_response_code(404);
echo "404 Not Found";