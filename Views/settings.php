<?php
// Inclui o arquivo de conexão com a base de dados
include(__DIR__ . '/../BasedeDados.php');
// Inicia a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id_Person'])) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("Location: logIn.php");
    exit();
}

// Obtém o ID do usuário da sessão
$userId = $_SESSION['id_Person'];

// Recuperar os dados do usuário logado
$stmt = $conn->prepare("SELECT p.name_Person, p.email_Person, p.age_Person, p.num_Person FROM Person p JOIN Client c ON p.id_Person = c.Person_id_Person WHERE p.id_Person = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($name, $email, $age, $contact);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
</head>
<!-- Inclui o cabeçalho -->
<?php include(__DIR__ . '/Partials/header.php'); ?>
<body class="body">
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">Settings</h4>
        <div class="card overflow-hidden">
          <div class="row no-gutters row-bordered row-border-light">
            <div class="col-md-3 pt-0">
              <div class="list-group list-group-flush account-settings-links">
                <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-general">General</a>
                <a class="list-group-item list-group-item-action" data-toggle="list" href="logIn.php">Log Out</a>
              </div>
            </div>
            <div class="col-md-9">
              <div class="tab-content">
                <div class="tab-pane fade active show" id="account-general">
                  <hr class="border-light m-0">
                  <div class="card-body">
                    <div class="form-group">
                      <label class="form-label">Name</label>
                      <input type="text" class="form-control" value="<?php echo htmlspecialchars($name); ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label class="form-label">E-mail</label>
                      <input type="email" class="form-control mb-1" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Age</label>
                      <input type="number" class="form-control" value="<?php echo htmlspecialchars($age); ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Contacts</label>
                      <input type="text" class="form-control" value="<?php echo htmlspecialchars($contact); ?>" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</body>
<!-- Inclui o rodapé -->
<?php include(__DIR__ . '/Partials/footer.php'); ?>
</html>