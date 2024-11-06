<?php

    $servername = "localhost";  // Servidor do MySQL (localhost quando se usa o XAMPP)
    $username = "root";         // Nome de usuário padrão do MySQL no XAMPP
    $password = "";             // Senha padrão do MySQL no XAMPP é vazia
    $dbname = "project_dws"; // Nome da base de dados que você criou

    // Criar a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
    echo "Conexão bem-sucedida!";
    
?>
