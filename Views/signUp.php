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
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
                                        <div class="input-wrapper">
                                            <input type="text" name="name_Person" placeholder="Full Name" class="input-field" required>
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
                                            <input type="num_telefone" name="num_Person" placeholder="num_telefone" class="input-field" required>
                                            <i class="material-symbols-outlined">person</i>
                                        </div>
                                        <form method="post" action="logIn.php" style="display:inline;">
                                            <button type="submit" class="login-button">Sign Up</button>
                                        </form>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-5 py-lg-5">
                                <div class="p-4 p-md-5">
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2">Warning</div>
                                    <div class="h2 fw-bolder">Sign In to participate in the Site</div>
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