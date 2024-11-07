<?php

/*
 *Importante information 
 !Deprecated method, do not use
 TODO: refactor this code
 ?should this method be used?
*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a viewport para garantir que o layout seja adequado em dispositivos móveis -->
    <title>Header</title>
    <link rel="stylesheet" href="/Public/style.css"> <!-- Link para o arquivo CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap CSS. Link para a biblioteca do bootstrap -->
     <!-- *Goocle fonts link -->
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-5">
            <a class="navbar-brand" href="/home">Catanho Car Shop</a> <!-- Link para a home -->
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="homePage.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutUs.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contactUs.php">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="terms.php">Terms</a></li>
                </ul>
            </div>
            <button class="custom-button1" title="Settings">
                <span class="material-symbols-outlined">account_circle</span>
            </button>
        </div>
        
    </nav>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>