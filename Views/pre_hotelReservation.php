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

// Obtém o ID do quarto da URL
$roomId = $_GET['room_id'];
// Obtém o ID do usuário da sessão
$userId = $_SESSION['id_Person'];
$roomPricePerNight = 0;

// Recuperar o preço por noite do quarto selecionado
$stmt = $conn->prepare("SELECT price_Room FROM Room WHERE id_Room = ?");
$stmt->bind_param("i", $roomId);
$stmt->execute();
$stmt->bind_result($roomPricePerNight);
$stmt->fetch();
$stmt->close();

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
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

    // Verifica se o quarto está disponível
    if ($reservationCount > 0) {
        echo "Error: The room is not available for the selected dates.";
    } else {
        // Inserir a reserva no banco de dados
        $stmt = $conn->prepare("INSERT INTO Reservation (date_Entrance, date_Out, final_Cost, status, Cient_Person_id_Person) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $checkin, $checkout, $price, $status, $userId);

        // Verifica se a inserção foi bem-sucedida
        if ($stmt->execute()) {
            $reservationId = $stmt->insert_id;

            // Inserir na tabela intermediária Reservation_has_Room
            $stmt2 = $conn->prepare("INSERT INTO Reservation_has_Room (Reservation_id_Reservation, Room_id_Room) VALUES (?, ?)");
            $stmt2->bind_param("ii", $reservationId, $roomId);
            $stmt2->execute();
            $stmt2->close();

            echo "Reservation saved successfully!";
        } else {
            echo "Erro: " . $stmt->error;
        }

        $stmt->close();
    }

    // Fecha a conexão com a base de dados
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel's Reservation</title>
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
    // Função para calcular o preço total da reserva
    function calculatePrice() {
        // Obtém a data de check-in do campo de entrada
        const checkin = new Date(document.getElementById('checkin').value);

        // Obtém a data de check-out do campo de entrada
        const checkout = new Date(document.getElementById('checkout').value);

        // Obtém o preço por noite do campo de entrada
        const pricePerNight = parseFloat(document.getElementById('price_per_night').value);

        // Verifica se todas as entradas necessárias estão preenchidas (check-in, check-out, preço por noite)
        if (checkin && checkout && pricePerNight) {
            // Calcula a diferença de tempo entre check-out e check-in em milissegundos
            const timeDifference = checkout - checkin;

            // Converte a diferença de tempo de milissegundos para dias
            const days = timeDifference / (1000 * 3600 * 24);

            // Calcula o preço total multiplicando o número de dias pelo preço por noite
            const totalPrice = days * pricePerNight;

            // Verifica se o resultado é um número válido antes de exibi-lo
            if (!isNaN(totalPrice)) {
                // Define o valor calculado no campo de preço total, formatado com duas casas decimais
                document.getElementById('price').value = totalPrice.toFixed(2);
            }
        }
    }
</script>

</head>
<!-- Inclui o cabeçalho -->
<?php include(__DIR__ . '/Partials/header.php'); ?>
<body>
    <div class="container">
        <h1>Reserva de Hotel</h1>
        <!-- Formulário de reserva -->
        <form action="pre_hotelReservation.php?room_id=<?php echo $roomId; ?>" method="post">
            <label for="checkin">Date of Entrance:</label>
            <input type="date" id="checkin" name="checkin" required onchange="calculatePrice()">

            <label for="checkout">Release Date:</label>
            <input type="date" id="checkout" name="checkout" required onchange="calculatePrice()">

            <label for="price_per_night">Price per Nigth(E€):</label>
            <input type="number" id="price_per_night" name="price_per_night" step="0.01" value="<?php echo $roomPricePerNight; ?>" readonly required>

            <label for="price">Total Price (E€):</label>
            <input type="number" id="price" name="price" step="0.01" readonly required>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="confirmada">Confirm</option>
            </select>

            <button type="submit">Save Reservation</button>
        </form>
    </div>
</body>
<!-- Inclui o rodapé -->
<?php include(__DIR__ . '/Partials/footer.php'); ?>
</html>