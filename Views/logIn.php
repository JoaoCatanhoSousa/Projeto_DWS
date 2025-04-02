<?php
// Inclui o arquivo de conexão com a base de dados
include(__DIR__ . '/../BasedeDados.php');
// Inicia a sessão
session_start();

// Indicar que o usuário está na página de login
$_SESSION['logging_in'] = true;

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o email e a senha do formulário
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepara a consulta SQL
    $stmt = $conn->prepare("SELECT p.id_Person, c.password_client FROM Person p JOIN Client c ON p.id_Person = c.Person_id_Person WHERE p.email_Person = ?");
    $stmt->bind_param("s", $email);

    // Executa a consulta
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o email existe na base de dados
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_Person, $hashed_password);
        $stmt->fetch();

        // Verifica a senha
        if (password_verify($password, $hashed_password)) {
            // Senha correta, iniciar sessão
            $_SESSION['id_Person'] = $id_Person;
            $_SESSION['email_Person'] = $email;
            $_SESSION['logged_in'] = true; // Definir a sessão logged_in

            // Verifica se o usuário é um trabalhador
            if (strpos($email, 'gmail.com.si') !== false) {
                $_SESSION['is_worker'] = true;
                header("Location: workerTemplate.php"); // Redirecionar para a página do trabalhador
            } else {
                $_SESSION['is_worker'] = false;
                header("Location: home.php"); // Redirecionar para a página normal
            }

            // Remover a sessão logging_in após o login bem-sucedido
            unset($_SESSION['logging_in']);
            exit();
        } else {
            // Senha incorreta
            $error = "Incorrect Password.";
        }
    } else {
        // Email não encontrado
        $error = "Email not found.";
    }

    // Fecha a declaração e a conexão
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
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google fonts link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<style>

/* Estilos para a classe de login */
.loginclass {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Estilos para o título do formulário */
.loginclass .form-title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

/* Estilos para o separador */
.loginclass .separator {
    text-align: center;
    margin: 20px 0;
}

/* Estilos para os campos de entrada */
.loginclass .input-wrapper {
    position: relative;
    margin-bottom: 20px;
}

.loginclass .input-field {
    width: 100%;
    padding: 10px 40px 10px 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.loginclass .material-symbols-outlined {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
}

/* Estilos para o botão de login */
.loginclass .login-button {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.loginclass .login-button:hover {
    background-color: #0056b3;
}

/* Estilos para o texto de inscrição */
.loginclass .signup-text {
    text-align: center;
    margin: 1.75rem 0 0.31rem;
    font-weight: 500;
}

/* Estilos para os links dentro de loginclass */
.loginclass a {
    text-decoration: none;
    color: #007bff;
    font-weight: 500;
}

.loginclass a:hover {
    text-decoration: underline;
}

</style>

<!-- Inclui o cabeçalho -->
<?php include(__DIR__ . '/Partials/header.php'); ?>
<body class="d-flex flex-column">
    <main class="flex-shrink-0">
        <!-- Conteúdo da página -->
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
                                </div>
                            </div>
                            <div class="col-xl-7">
                                <div class="loginclass">
                                    <h1 class="form-title">Log in with</h1>
                                    <p class="separator"><span></span></p>
                                    <!-- Exibe mensagem de erro, se houver -->
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <!-- Formulário de login -->
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
                                        <div class="input-wrapper">
                                            <input type="email" name="email" placeholder="Email Address" class="input-field" required>
                                            <i class="material-symbols-outlined">mail</i>
                                        </div>
                                        <div class="input-wrapper">
                                            <input type="password" name="password" placeholder="Password" class="input-field" required>
                                            <i class="material-symbols-outlined">lock</i>
                                        </div>
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
<!-- Inclui o rodapé -->
<?php include(__DIR__ . '/Partials/footer.php'); ?>
</html>