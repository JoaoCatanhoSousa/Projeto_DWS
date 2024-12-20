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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- *Link para o Arquivo CSS -->
    <link rel="stylesheet" href="/Public/style/style.css"> 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap CSS. Link para a biblioteca do bootstrap -->
    <!-- *Goocle fonts link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<?php include(__DIR__ . '/Partials/header.php'); ?>
<body class="d-flex flex-column">
    <main class="flex-shrink-0">
        <!-- Page Content-->
        <section class="py-5">
            <div class="container px-5">
                <h1 class="fw-bolder fs-5 mb-4">Welcome, to the <strong>Login Page</strong></h1>
                <div class="card border-0 shadow rounded-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="row gx-0">
                            <div class="col-lg-6 col-xl-5 py-lg-5">
                                <div class="p-4 p-md-5">
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2">Warning</div>
                                    <div class="h2 fw-bolder">LogIn to participate in the Site</div>
                                    <!--!We will use an API to show diferent senteces for a determined subject-->
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique delectus ab doloremque, qui doloribus ea officiis...</p>
                                    <a class="stretched-link text-decoration-none" href="#!">
                                        Read more
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-7" >
                                <div class="loginclass">
                                    <!-- Adicionando a imagem diretamente aqui -->
                                    <img src="Public/Imagens/dashboard-3510327_640.jpg" alt="Imagem About Us";>
                                    <h1 class="form-title">Log in with</h1>
                                    <p class="separator"><span></span></p>
                                    <form action="#" class="login-form">
                                        <div class="input-wrapper">
                                            <input type="email" placeholder="Email Adress" class="input-field" required>
                                            <i class="material-symbols-outlined">mail</i>
                                        </div>
                                        <div class="input-wrapper">
                                            <input type="password" placeholder="Password" class="input-field" required>
                                            <i class="material-symbols-outlined">lock</i>
                                        </div>
                                        <a href="#" class="forgot-pass-link">Forgot Password?</a>
                                        <button class="login-button">Log in</button>
                                    </form>
                                    <p class="singup-text">Don't have an account? <a href="#">SignUp</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</body>
<?php include(__DIR__ . '/Partials/footer.php'); ?>
</html>