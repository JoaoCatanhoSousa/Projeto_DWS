<?php
include(__DIR__ . '/../BasedeDados.php');
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id_Person'])) {
    header("Location: logIn.php");
    exit();
}

$roomId = $_GET['room_id'];
$userId = $_SESSION['id_Person']; // Usar o ID do usuário armazenado na sessão
$roomPricePerNight = 0;

// Recuperar o preço por noite do quarto selecionado
$stmt = $conn->prepare("SELECT price_Room FROM Room WHERE id_Room = ?");
$stmt->bind_param("i", $roomId);
$stmt->execute();
$stmt->bind_result($roomPricePerNight);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Verificar se o quarto está disponível nas datas selecionadas
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Reservation_has_Room rh
                            JOIN Reservation res ON rh.Reservation_id_Reservation = res.id_Reservation
                            WHERE rh.Room_id_Room = ? AND (res.date_Entrance <= ? AND res.date_Out >= ?)");
    $stmt->bind_param("iss", $roomId, $checkout, $checkin);
    $stmt->execute();
    $stmt->bind_result($reservationCount);
    $stmt->fetch();
    $stmt->close();

    if ($reservationCount > 0) {
        echo "Erro: O quarto não está disponível nas datas selecionadas.";
    } else {
        // Inserir a reserva no banco de dados
        $stmt = $conn->prepare("INSERT INTO Reservation (date_Entrance, date_Out, final_Cost, status, Cient_Person_id_Person) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $checkin, $checkout, $price, $status, $userId);

        if ($stmt->execute()) {
            $reservationId = $stmt->insert_id;

            // Inserir na tabela intermediária Reservation_has_Room
            $stmt2 = $conn->prepare("INSERT INTO Reservation_has_Room (Reservation_id_Reservation, Room_id_Room) VALUES (?, ?)");
            $stmt2->bind_param("ii", $reservationId, $roomId);
            $stmt2->execute();
            $stmt2->close();

            echo "Reserva salva com sucesso!";
        } else {
            echo "Erro: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Hotel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
    <script>
        function calculatePrice() {
            const checkin = new Date(document.getElementById('checkin').value);
            const checkout = new Date(document.getElementById('checkout').value);
            const pricePerNight = parseFloat(document.getElementById('price_per_night').value);

            if (checkin && checkout && pricePerNight) {
                const timeDifference = checkout - checkin;
                const days = timeDifference / (1000 * 3600 * 24);
                const totalPrice = days * pricePerNight;

                if (!isNaN(totalPrice)) {
                    document.getElementById('price').value = totalPrice.toFixed(2);
                }
            }
        }
    </script>
</head>
<?php include(__DIR__ . '/Partials/header.php'); ?>
<body>
    <div class="container">
        <h1>Reserva de Hotel</h1>
        <form action="pre_hotelReservation.php?room_id=<?php echo $roomId; ?>" method="post">
            <label for="checkin">Data de Entrada:</label>
            <input type="date" id="checkin" name="checkin" required onchange="calculatePrice()">

            <label for="checkout">Data de Saída:</label>
            <input type="date" id="checkout" name="checkout" required onchange="calculatePrice()">

            <label for="price_per_night">Preço por Noite (R$):</label>
            <input type="number" id="price_per_night" name="price_per_night" step="0.01" value="<?php echo $roomPricePerNight; ?>" readonly required>

            <label for="price">Preço Total (R$):</label>
            <input type="number" id="price" name="price" step="0.01" readonly required>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="confirmada">Confirmada</option>
                <option value="pendente">Pendente</option>
                <option value="cancelada">Cancelada</option>
            </select>

            <button type="submit">Salvar Reserva</button>
        </form>
    </div>
</body>
<?php include(__DIR__ . '/Partials/footer.php'); ?>
</html>