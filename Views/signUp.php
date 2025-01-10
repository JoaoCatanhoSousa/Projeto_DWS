<?php
include(__DIR__ . '/../BasedeDados.php');
session_start();

// Indicar que o usuário está na página de registro
$_SESSION['registering'] = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['name_Person'];
    $email = $_POST['email_Person'];
    $age = $_POST['age_Person'];
    $num = $_POST['num_Person'];
    $password = password_hash($_POST['password_client'], PASSWORD_DEFAULT); // Hash da senha

    // Verificar se o nome contém apenas letras e acentos
    if (!preg_match('/^[\p{L} ]+$/u', $fullName)) {
        $error = "O nome deve conter apenas letras e acentos.";
    }
    // Verificar se o número de telefone tem exatamente 9 dígitos
    elseif (!preg_match('/^\d{9}$/', $num)) {
        $error = "O número de telefone deve ter exatamente 9 dígitos.";
    } else {
        // Verificar se o email já existe
        $stmt = $conn->prepare("SELECT id_Person FROM Person WHERE email_Person = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "O email já está em uso.";
        }
        $stmt->close();

        // Verificar se o número de telefone já existe
        $stmt = $conn->prepare("SELECT id_Person FROM Person WHERE num_Person = ?");
        $stmt->bind_param("s", $num);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "O número de telefone já está em uso.";
        }
        $stmt->close();

        if (!isset($error)) {
            // Iniciar transação
            $conn->begin_transaction();

            try {
                // Prepare a SQL statement para a tabela Person
                $stmt1 = $conn->prepare("INSERT INTO Person (name_Person, email_Person, age_Person, num_Person) VALUES (?, ?, ?, ?)");
                $stmt1->bind_param("ssis", $fullName, $email, $age, $num);

                // Execute a instrução para a tabela Person
                if (!$stmt1->execute()) {
                    throw new Exception($stmt1->error);
                }

                // Obter o ID da pessoa inserida
                $personId = $conn->insert_id;

                // Prepare a SQL statement para a tabela Client
                $stmt2 = $conn->prepare("INSERT INTO Client (Person_id_Person, password_client) VALUES (?, ?)");
                $stmt2->bind_param("is", $personId, $password);

                // Execute a instrução para a tabela Client
                if (!$stmt2->execute()) {
                    throw new Exception($stmt2->error);
                }

                // Commit da transação
                $conn->commit();
                echo "Registro bem-sucedido!";

                // Definir a sessão para indicar que o usuário está registrado
                $_SESSION['registered'] = true;

                // Redirecionar para logIn.php após o registro bem-sucedido
                header("Location: logIn.php");
                exit();

            } catch (Exception $e) {
                // Rollback da transação em caso de erro
                $conn->rollback();
                echo "Erro: " . $e->getMessage();
            }

            // Close the statements and connection
            $stmt1->close();
            $stmt2->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Link para o Arquivo CSS -->
    <link rel="stylesheet" href="/Public/style/style.css"> 
    <!-- Google fonts link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<style>
    /* Inclua o CSS aqui se não estiver usando um arquivo CSS separado */
    /* Estilos para a classe de inscrição */
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

    /* Estilos para o botão de inscrição */
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

<?php include(__DIR__ . '/Partials/header.php'); ?>
<body class="d-flex flex-column">
    <main class="flex-shrink-0">
        <!-- Page Content-->
        <section class="py-5">
            <div class="container px-5">
                <h1 class="fw-bolder fs-5 mb-4">Welcome, to the <strong>Sign Up Page</strong></h1>
                <div class="card border-0 shadow rounded-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="row gx-0">
                            <div class="col-xl-7" id="imagemaboutus">
                                <div class="loginclass">
                                    <h1 class="form-title">Sign Up with</h1>
                                    <p class="separator"><span></span></p>
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
                                        <div class="input-wrapper">
                                            <input type="text" name="name_Person" placeholder="Full Name" class="input-field" required pattern="^[\p{L} ]+$" title="O nome deve conter apenas letras e acentos.">
                                            <i class="material-symbols-outlined">person</i>
                                        </div>
                                        <div class="input-wrapper">
                                            <input type="email" name="email_Person" placeholder="Email Address" class="input-field" required>
                                            <i class="material-symbols-outlined">mail</i>
                                        </div>
                                        <div class="input-wrapper">
                                            <input type="age" name="age_Person" placeholder="Age" class="input-field" required>
                                            <i class="material-symbols-outlined">person</i>
                                        </div>
                                        <div class="input-wrapper">
                                            <input type="password" name="password_client" placeholder="Password" class="input-field" required>
                                            <i class="material-symbols-outlined">person</i>
                                        </div>
                                        <div class="input-wrapper">
                                            <input type="text" name="num_Person" placeholder="Phone Number" class="input-field" required pattern="\d{9}" title="O número de telefone deve ter exatamente 9 dígitos">
                                            <i class="material-symbols-outlined">phone</i>
                                        </div>
                                        <button type="submit" class="login-button">Sign Up</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-5 py-lg-5">
                                <div class="p-4 p-md-5">
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2">Warning</div>
                                    <div class="h2 fw-bolder">Sign In to participate in the Site</div>
                                </div>
                                <div class="text-center">
                                    <a href="logIn.php" >Back to Login</a>
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