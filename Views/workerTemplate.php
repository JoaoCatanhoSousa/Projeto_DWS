<?php
    include(__DIR__ . '/../BasedeDados.php');
    session_start();

    // Recuperar os IDs dos pisos da base de dados
    $stmt = $conn->prepare("SELECT id_Floor FROM Floor");
    $stmt->execute();
    $stmt->bind_result($floorId);
    $floors = [];
    while ($stmt->fetch()) {
        $floors[] = $floorId;
    }
    $stmt->close();
    $conn->close();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
    
        if ($action == 'add_room') {
            $roomType = $_POST['room_type'];
            $roomCapacity = $_POST['room_capacity'];
            $roomPrice = $_POST['room_price'];
            $roomDescription = $_POST['room_description'];
            $floorId = $_POST['floor_id'];
            $statusRoom = $_POST['status_Room'];
    
            $stmt = $conn->prepare("INSERT INTO Room (type_Room, capacity_Room, price_Room, description_Room, Floor_id_Floor, status_Room) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sidsis", $roomType, $roomCapacity, $roomPrice, $roomDescription, $floorId, $statusRoom);
    
            if ($stmt->execute()) {
                echo "Quarto adicionado com sucesso!";
            } else {
                echo "Erro: " . $stmt->error;
            }
    
            $stmt->close();
        } elseif ($action == 'add_floor') {
            $floorNumber = $_POST['floor_number'];
            $numRooms = $_POST['num_rooms'];
    
            $stmt = $conn->prepare("INSERT INTO Floor (num_rooms) VALUES (?)");
            $stmt->bind_param("i", $numRooms);
    
            if ($stmt->execute()) {
                echo "Piso adicionado com sucesso!";
            } else {
                echo "Erro: " . $stmt->error;
            }
    
            $stmt->close();
        } elseif ($action == 'add_worker') {
            $workerName = $_POST['worker_name'];
            $workerEmail = $_POST['worker_email'];
            $workerPhone = $_POST['worker_phone'];
            $floorId = $_POST['floor_id'];
            $startDate = $_POST['start_date'];
    
            // Primeiro, insira o trabalhador na tabela Person
            $stmt = $conn->prepare("INSERT INTO Person (name_Person, email_Person, num_Person) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $workerName, $workerEmail, $workerPhone);
    
            if ($stmt->execute()) {
                $personId = $stmt->insert_id;
    
                // Em seguida, insira o trabalhador na tabela Worker
                $stmt = $conn->prepare("INSERT INTO Worker (Person_id_Person, Floor_id_Floor, start_date) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $personId, $floorId, $startDate);
    
                if ($stmt->execute()) {
                    echo "Trabalhador adicionado com sucesso!";
                } else {
                    echo "Erro: " . $stmt->error;
                }
            } else {
                echo "Erro: " . $stmt->error;
            }
    
            $stmt->close();
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
    </style>
</head>
<body>
    <h1>Gerenciar Hotel</h1>

    <!-- Formulário para adicionar quartos -->
    <h2>Adicionar Quarto</h2>
    <form action="manage_hotel.php" method="POST">
        <input type="hidden" name="action" value="add_room">
        <label for="room_type">Tipo de Quarto:</label>
        <input type="text" name="room_type" required placeholder="Ex: Suite, T3, T2">
        <label for="room_capacity">Capacidade:</label>
        <input type="number" name="room_capacity" required min="1">
        <label for="room_price">Preço:</label>
        <input type="number" name="room_price" required step="0.01">
        <label for="room_description">Descrição:</label>
        <textarea name="room_description" rows="3" placeholder="Ex: Vista para o mar, sem varanda"></textarea>
        <form action="processWorker.php" method="post">
            <label for="floor_id">ID do Piso:</label>
            <select name="floor_id" required>
                <?php foreach ($floors as $floor): ?>
                    <option value="<?php echo htmlspecialchars($floor); ?>"><?php echo htmlspecialchars($floor); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <label for="status_Room">Status do Quarto:</label>
        <select name="status_Room" required>
            <option value="Disponivel">Disponível</option>
            <option value="Não Disponivel">Não Disponível</option>
        </select>
        <button type="submit">Adicionar Quarto</button>
    </form>

    <!-- Formulário para adicionar pisos -->
    <h2>Adicionar Piso</h2>
    <form action="manage_hotel.php" method="POST">
        <input type="hidden" name="action" value="add_floor">
        <label for="floor_number">Número do Piso:</label>
        <input type="number" name="floor_number" required>
        <label for="num_rooms">Número de Quartos:</label>
        <input type="number" name="num_rooms" required min="1">
        <button type="submit">Adicionar Piso</button>
    </form>

    <!-- Formulário para adicionar trabalhadores -->
    <h2>Adicionar Trabalhador</h2>
    <form action="manage_hotel.php" method="POST">
        <input type="hidden" name="action" value="add_worker">
        <label for="worker_name">Nome do Trabalhador:</label>
        <input type="text" name="worker_name" required placeholder="Ex: João Silva">
        <label for="worker_email">Email:</label>
        <input type="email" name="worker_email" required>
        <label for="worker_phone">Telefone:</label>
        <input type="text" name="worker_phone" required placeholder="Ex: 912345678">
        <form action="processWorker.php" method="post">
            <label for="floor_id">ID do Piso:</label>
            <select name="floor_id" required>
                <?php foreach ($floors as $floor): ?>
                    <option value="<?php echo htmlspecialchars($floor); ?>"><?php echo htmlspecialchars($floor); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <label for="start_date">Data de Entrada:</label>
        <input type="date" name="start_date" required>
        <button type="submit">Adicionar Trabalhador</button>
    </form>
</body>
</html>
