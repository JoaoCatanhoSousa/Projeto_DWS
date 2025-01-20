<?php
include(__DIR__ . '/../BasedeDados.php');
session_start();

// Verificar se uma reserva deve ser eliminada
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_reservation_id'])) {
    $reservationIdToDelete = $_POST['delete_reservation_id'];

    // Eliminar as referências na tabela reservation_has_room
    $stmt = $conn->prepare("DELETE FROM Reservation_has_Room WHERE Reservation_id_Reservation = ?");
    $stmt->bind_param("i", $reservationIdToDelete);
    if ($stmt->execute()) {
        // Eliminar a reserva da base de dados
        $stmt = $conn->prepare("DELETE FROM Reservation WHERE id_Reservation = ?");
        $stmt->bind_param("i", $reservationIdToDelete);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Reserva eliminada com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao eliminar a reserva: " . $stmt->error;
        }
    } else {
        $_SESSION['message'] = "Erro ao eliminar a referência da reserva: " . $stmt->error;
    }
    $stmt->close();

    // Redirecionar para evitar reenvio do formulário
    header("Location: hotelReservation.php");
    exit();
}

// Recuperar as reservas dos clientes da base de dados
$stmt = $conn->prepare("SELECT res.id_Reservation, res.date_Entrance, res.date_Out, res.final_Cost, c.name_Person, r.type_Room, r.imgURL
                        FROM Reservation res
                        JOIN Reservation_has_Room rh ON res.id_Reservation = rh.Reservation_id_Reservation
                        JOIN Room r ON rh.Room_id_Room = r.id_Room
                        JOIN Client cl ON res.Cient_Person_id_Person = cl.Person_id_Person
                        JOIN Person c ON cl.Person_id_Person = c.id_Person
                        WHERE c.id_Person = ?");
$stmt->bind_param("i", $_SESSION['id_Person']);
$stmt->execute();
$stmt->bind_result($reservationId, $dateEntrance, $dateOut, $finalCost, $clientName, $roomType, $roomImage);
$reservations = [];
while ($stmt->fetch()) {
    $reservations[] = [
        'id' => $reservationId,
        'date_Entrance' => $dateEntrance,
        'date_Out' => $dateOut,
        'final_Cost' => $finalCost,
        'client_name' => $clientName,
        'room_type' => $roomType,
        'room_image' => $roomImage
    ];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation of the Room</title>
    <!-- Link para o Arquivo CSS -->
    <link rel="stylesheet" href="/Projeto_DWS/Public/style/style.css"> 
    <!-- Google fonts link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body class="d-flex flex-column">
    <?php include(__DIR__ . '/Partials/header.php'); ?>
    <main class="flex-shrink-0">
        <!-- Blog preview section-->
        <section class="py-5">
            <div class="container px-5">
                <h2 class="fw-bolder fs-5 mb-4">Reservations of the Room</h2>
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<p>" . $_SESSION['message'] . "</p>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="row gx-5">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="col-lg-4 mb-5">
                            <div class="card h-100 shadow border-0">
                                <img class="card-img-top" src="<?php echo htmlspecialchars($reservation['room_image']); ?>" alt="Room Image" />
                                <div class="card-body p-4">
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2"><?php echo htmlspecialchars($reservation['room_type']); ?></div>
                                    <h5 class="card-title mb-3">Client: <?php echo htmlspecialchars($reservation['client_name']); ?></h5>
                                    <p class="card-text mb-0">Date of Entrance: <?php echo htmlspecialchars($reservation['date_Entrance']); ?></p>
                                    <p class="card-text mb-0">Release date : <?php echo htmlspecialchars($reservation['date_Out']); ?></p>
                                    <p class="card-text mb-0">Total Cost: $<?php echo htmlspecialchars($reservation['final_Cost']); ?></p>
                                </div>
                                <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <form action="hotelReservation.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this reservation?');">
                                            <input type="hidden" name="delete_reservation_id" value="<?php echo $reservation['id']; ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
    <?php include(__DIR__ . '/Partials/footer.php'); ?>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>