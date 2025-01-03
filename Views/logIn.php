<?php
include(__DIR__ . '/../BasedeDados.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a SQL statement
    $stmt = $conn->prepare("SELECT p.id_Person, c.password_client FROM Person p JOIN Client c ON p.id_Person = c.Person_id_Person WHERE p.email_Person = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_Person, $hashed_password);
        $stmt->fetch();

        // Verificar a senha
        if (password_verify($password, $hashed_password)) {
            // Senha correta, iniciar sessão
            $_SESSION['id_Person'] = $id_Person;
            header("Location: home.php"); // Redirecionar para a página do dashboard
            exit();
        } else {
            $error = "Senha incorreta.";
        }
    } else {
        $error = "Email não encontrado.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Link para o Arquivo CSS -->
    <link rel="stylesheet" href="/Public/style/style.css"> 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google fonts link -->
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
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique delectus ab doloremque, qui doloribus ea officiis...</p>
                                    <a class="stretched-link text-decoration-none" href="#!">
                                        Read more
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-7">
                                <div class="loginclass">
                                    <img src="Public/Imagens/dashboard-3510327_640.jpg" alt="Imagem About Us">
                                    <h1 class="form-title">Log in with</h1>
                                    <p class="separator"><span></span></p>
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
                                        <div class="input-wrapper">
                                            <input type="email" name="email" placeholder="Email Address" class="input-field" required>
                                            <i class="material-symbols-outlined">mail</i>
                                        </div>
                                        <div class="input-wrapper">
                                            <input type="password" name="password" placeholder="Password" class="input-field" required>
                                            <i class="material-symbols-outlined">lock</i>
                                        </div>
                                        <a href="#" class="forgot-pass-link">Forgot Password?</a>
                                        <button type="submit" class="login-button">Log in</button>
                                    </form>
                                    <p class="signup-text">Don't have an account? <a href="signUp.php">SignUp</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
<?php include(__DIR__ . '/Partials/footer.php'); ?>
</html>