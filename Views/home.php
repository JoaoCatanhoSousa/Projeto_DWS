<?php
include(__DIR__ . '/../BasedeDados.php');
session_start();

// Recuperar os dados dos quartos da base de dados
$stmt = $conn->prepare("SELECT r.id_Room, r.type_Room, r.capacity_Room, r.price_Room, r.description_Room, r.imgURL, f.num_floor FROM Room r JOIN Floor f ON r.Floor_id_Floor = f.id_Floor");
$stmt->execute();
$stmt->bind_result($roomId, $roomType, $roomCapacity, $roomPrice, $roomDescription, $roomImage, $floorNumber);
$rooms = [];
while ($stmt->fetch()) {
    $rooms[] = [
        'id' => $roomId,
        'type' => $roomType,
        'capacity' => $roomCapacity,
        'price' => $roomPrice,
        'description' => $roomDescription,
        'image' => $roomImage,
        'floor' => $floorNumber
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
    <title>Home</title>
    <!-- Link para o Arquivo CSS -->
    <link rel="stylesheet" href="/Public/style/style.css"> 
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google fonts link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<?php include(__DIR__ . '/Partials/header.php'); ?>
<body class="d-flex flex-column">
    <main class="flex-shrink-0">
        <!-- Blog preview section-->
        <section class="py-5">
            <div class="container px-5">
                <h2 class="fw-bolder fs-5 mb-4">Welcome to Catanho's Hotel</h2>
                <div class="row gx-5">
                    <?php foreach ($rooms as $room): ?>
                        <div class="col-lg-4 mb-5">
                            <div class="card h-100 shadow border-0">
                                <img class="card-img-top" src="<?php echo htmlspecialchars($room['image']); ?>" alt="Room Image" />
                                <div class="card-body p-4">
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2"><?php echo htmlspecialchars($room['type']); ?></div>
                                    <h5 class="card-title mb-3">Floor: <?php echo htmlspecialchars($room['floor']); ?></h5>
                                    <p class="card-text mb-0">Capacity: <?php echo htmlspecialchars($room['capacity']); ?> people</p>
                                    <p class="card-text mb-0">Price: $<?php echo htmlspecialchars($room['price']); ?></p>
                                    <a href="#" onclick="showDetails(<?php echo $room['id']; ?>)" class="btn btn-info">Detalhes</a>
                                </div>
                                <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <a href="pre_hotelReservation.php?room_id=<?php echo $room['id']; ?>" class="btn btn-primary">Reserve</a>
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

    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Detalhes do Quarto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Descrição:</strong> <span id="roomDescription"></span></p>
                    <p><strong>Capacidade:</strong> <span id="roomCapacity"></span></p>
                    <p><strong>Preço:</strong> <span id="roomPrice"></span></p>
                    <p><strong>Piso:</strong> <span id="roomFloor"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showDetails(roomId) {
            $.ajax({
                url: '/Projeto_DWS/Views/detalhes.php',
                type: 'GET',
                data: { reservation_id: roomId },
                success: function(response) {
                    $('#roomDescription').text(response.description);
                    $('#roomCapacity').text(response.capacity);
                    $('#roomPrice').text(response.price);
                    $('#roomFloor').text(response.floor);
                    $('#detailsModal').modal('show');
                },
                error: function() {
                    alert('Erro ao carregar os detalhes do quarto.');
                }
            });
        }
    </script>
</body>
</html>