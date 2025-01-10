<?php
include(__DIR__ . '/../BasedeDados.php');
session_start();

// Recuperar os IDs dos pisos da base de dados
$stmt = $conn->prepare("SELECT id_Floor, num_rooms FROM Floor");
$stmt->execute();
$stmt->bind_result($floorId, $numRooms);
$floors = [];
while ($stmt->fetch()) {
    $floors[] = ['id' => $floorId, 'num_rooms' => $numRooms];
}
$stmt->close();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'add_room') {
        $roomType = $_POST['room_type'];
        $roomCapacity = $_POST['room_capacity'];
        $roomPrice = $_POST['room_price'];
        $roomDescription = $_POST['room_description'];
        $floorId = $_POST['floor_id'];
        $statusRoom = $_POST['status_Room'];
        $roomImage = $_POST['room_image'];

        // Verificar o número atual de quartos no piso selecionado
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Room WHERE Floor_id_Floor = ?");
        $stmt->bind_param("i", $floorId);
        $stmt->execute();
        $stmt->bind_result($currentRoomCount);
        $stmt->fetch();
        $stmt->close();

        // Recuperar o número máximo de quartos para o piso selecionado
        $maxRooms = 0;
        foreach ($floors as $floor) {
            if ($floor['id'] == $floorId) {
                $maxRooms = $floor['num_rooms'];
                break;
            }
        }

        // Verificar se o número atual de quartos excede o limite
        if ($currentRoomCount >= $maxRooms) {
            $_SESSION['message'] = "Erro: O número máximo de quartos para este piso foi atingido.";
        } else {
            $stmt = $conn->prepare("INSERT INTO Room (type_Room, capacity_Room, price_Room, description_Room, Floor_id_Floor, status_Room, imgURL) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sidsiss", $roomType, $roomCapacity, $roomPrice, $roomDescription, $floorId, $statusRoom, $roomImage);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Quarto adicionado com sucesso!";
            } else {
                $_SESSION['message'] = "Erro: " . $stmt->error;
            }

            $stmt->close();
        }
    } elseif ($action == 'add_floor') {
        $floorNumber = $_POST['num_floor'];
        $numRooms = $_POST['num_rooms'];

        $stmt = $conn->prepare("INSERT INTO Floor (num_floor, num_rooms) VALUES (?, ?)");
        $stmt->bind_param("ii", $floorNumber, $numRooms);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Piso adicionado com sucesso!";
        } else {
            $_SESSION['message'] = "Erro: " . $stmt->error;
        }

        $stmt->close();
    } elseif ($action == 'add_worker') {
        $workerName = $_POST['worker_name'];
        $workerEmail = $_POST['worker_email'];
        $workerPhone = $_POST['worker_phone'];
        $workerAge = $_POST['worker_age'];
        $floorId = $_POST['floor_id'];
        $startDate = $_POST['start_date'];

        // Verificar se o nome do trabalhador é válido
        if (!preg_match("/^[\p{Lu}][\p{L} ]+$/u", $workerName)) {
            $error = "Erro: O nome deve começar com uma letra maiúscula e conter apenas letras e acentos.";
        } elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com\.si$/", $workerEmail)) {
            // Verificar se o email termina com '@gmail.com.si' usando regex
            $error = "Erro: O email deve terminar com '@gmail.com.si' para ser um trabalhador.";
        } elseif (!preg_match("/^\d{9}$/", $workerPhone)) {
            // Verificar se o número de telefone tem exatamente 9 dígitos
            $error = "Erro: O número de telefone deve ter exatamente 9 dígitos.";
        } else {
            // Primeiro, insira o trabalhador na tabela Person
            $stmt = $conn->prepare("INSERT INTO Person (name_Person, email_Person, num_Person, age_Person) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $workerName, $workerEmail, $workerPhone, $workerAge);

            if ($stmt->execute()) {
                $personId = $stmt->insert_id;

                // Em seguida, insira o trabalhador na tabela Worker
                $stmt = $conn->prepare("INSERT INTO Worker (Person_id_Person, Floor_id_Floor, start_date) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $personId, $floorId, $startDate);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Trabalhador adicionado com sucesso!";
                } else {
                    $_SESSION['message'] = "Erro: " . $stmt->error;
                }
            } else {
                $_SESSION['message'] = "Erro: " . $stmt->error;
            }

            $stmt->close();
        }

        // Redirecionar para evitar reenvio do formulário
        if (empty($error)) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Hotel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-bottom: 30px;
        }
        input, button, select {
            margin: 5px 0;
            padding: 10px;
            width: 100%;
            max-width: 300px;
        }
        button {
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #45a049;
        }
        .alert {
            color: red;
        }
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <script>
        function validateEmail() {
            const emailField = document.querySelector('input[name="worker_email"]');
            const email = emailField.value;
            const errorDiv = document.getElementById('emailError');
            const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com\.si$/;

            if (!emailPattern.test(email)) {
                errorDiv.textContent = "Erro: O email deve terminar com '@gmail.com.si' para ser um trabalhador.";
                return false;
            } else {
                errorDiv.textContent = "";
                return true;
            }
        }

        function validateForm() {
            return validateEmail();
        }
    </script>
</head>
<body>
    <h1>Gerenciar Hotel</h1>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>

    <!-- Formulário para adicionar quartos -->
    <h2>Adicionar Quarto</h2>
    <form action="workerTemplate.php" method="POST">
        <input type="hidden" name="action" value="add_room">
        <label for="room_type">Tipo de Quarto:</label>
        <select name="room_type" required>
            <option value="Suite">Suite</option>
            <option value="T3">T3</option>
            <option value="T2">T2</option>
        </select>
        <label for="room_capacity">Capacidade:</label>
        <input type="number" name="room_capacity" required min="1">
        <label for="room_price">Preço:</label>
        <input type="number" name="room_price" required step="0.01">
        <label for="room_description">Descrição:</label>
        <textarea name="room_description" rows="3" placeholder="Ex: Vista para o mar, sem varanda"></textarea>
        <label for="floor_id">ID do Piso:</label>
        <select name="floor_id" required>
            <?php foreach ($floors as $floor): ?>
                <option value="<?php echo htmlspecialchars($floor['id']); ?>"><?php echo htmlspecialchars($floor['id']); ?></option>
            <?php endforeach; ?>
        </select>
        <label for="status_Room">Status do Quarto:</label>
        <select name="status_Room" required>
            <option value="Disponivel">Disponível</option>
            <option value="Não Disponivel">Não Disponível</option>
        </select>
        <label for="room_image">Imagem do Quarto:</label>
        <select name="room_image" required>
            <option value="/Projeto_DWS/Public/Imagens/transferir (1).jpg">Suite</option>
            <option value="/Projeto_DWS/Public/Imagens/pasted image 0.png">T3</option>
            <option value="/Projeto_DWS/Public/Imagens/quartos-de-hotel-feitos-com-moveis-planejados.png">T2</option>
        </select>
        <button type="submit">Adicionar Quarto</button>
    </form>

    <!-- Formulário para adicionar pisos -->
    <h2>Adicionar Piso</h2>
    <form action="workerTemplate.php" method="POST">
        <input type="hidden" name="action" value="add_floor">
        <label for="num_floor">Número do Piso:</label>
        <input type="number" name="num_floor" required>
        <label for="num_rooms">Número de Quartos:</label>
        <input type="number" name="num_rooms" required min="1">
        <button type="submit">Adicionar Piso</button>
    </form>

    <!-- Formulário para adicionar trabalhadores -->
    <h2>Adicionar Trabalhador</h2>
    <form action="workerTemplate.php" method="POST" onsubmit="return validateForm()">
        <input type="hidden" name="action" value="add_worker">
        <?php if (!empty($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>
        <label for="worker_name">Nome do Trabalhador:</label>
        <input type="text" name="worker_name" required placeholder="Ex: João Silva" pattern="^[\p{Lu}][\p{L} ]+$" title="O nome deve começar com uma letra maiúscula e conter apenas letras e acentos.">
        <label for="worker_email">Email:</label>
        <input type="email" name="worker_email" required oninput="validateEmail()">
        <div id="emailError" class="alert"></div>
        <label for="worker_phone">Telefone:</label>
        <input type="text" name="worker_phone" required placeholder="Ex: 912345678" pattern="\d{9}" title="O número de telefone deve ter exatamente 9 dígitos.">
        <label for="worker_age">Idade:</label>
        <input type="number" name="worker_age" required min="18" max="65" placeholder="Ex: 30">
        <label for="floor_id">ID do Piso:</label>
        <select name="floor_id" required>
            <?php foreach ($floors as $floor): ?>
                <option value="<?php echo htmlspecialchars($floor['id']); ?>"><?php echo htmlspecialchars($floor['id']); ?></option>
            <?php endforeach; ?>
        </select>
        <label for="start_date">Data de Entrada:</label>
        <input type="date" name="start_date" required>
        <button type="submit">Adicionar Trabalhador</button>
    </form>
    <!-- Botão para voltar ao Login -->
    <div class="center">
        <a href="logout.php" class="btn btn-primary">Voltar ao Login</a>
    </div>

    
</body>
</html>