<?php
// Inclui o arquivo de conexão com a base de dados
include(__DIR__ . '/../BasedeDados.php');
// Inicia a sessão
session_start();

// Recupera os dados dos quartos da base de dados
$stmt = $conn->prepare("SELECT r.id_Room, r.type_Room, r.capacity_Room, r.price_Room, r.description_Room, r.imgURL, f.num_floor FROM Room r JOIN Floor f ON r.Floor_id_Floor = f.id_Floor");
$stmt->execute();
$stmt->bind_result($roomId, $roomType, $roomCapacity, $roomPrice, $roomDescription, $roomImage, $floorNumber);
$rooms = [];
while ($stmt->fetch()) {
    // Adiciona cada quarto ao array de quartos
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
// Fecha a declaração
$stmt->close();
// Fecha a conexão com a base de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Define a codificação de caracteres -->
    <meta charset="UTF-8">
    <!-- Define a viewport para responsividade -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Define o título da página -->
    <title>Home</title>
    <!-- Link para o Arquivo CSS -->
    <link rel="stylesheet" href="/Public/style/style.css"> 
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google fonts link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<!-- Inclui o cabeçalho -->
<?php include(__DIR__ . '/Partials/header.php'); ?>
<body class="d-flex flex-column">
    <main class="flex-shrink-0">
        <!-- Seção de visualização dos quartos -->
        <section class="py-5">
            <div class="container px-5">
                <!-- Título da seção -->
                <h2 class="fw-bolder fs-5 mb-4">Welcome to Catanho's Hotel</h2>
                <div class="row gx-5">
                    <?php foreach ($rooms as $room): ?>
                        <div class="col-lg-4 mb-5">
                            <div class="card h-100 shadow border-0">
                                <!-- Imagem do quarto -->
                                <img class="card-img-top" src="<?php echo htmlspecialchars($room['image']); ?>" alt="Room Image" />
                                <div class="card-body p-4">
                                    <!-- Tipo de quarto -->
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2"><?php echo htmlspecialchars($room['type']); ?></div>
                                    <!-- Número do andar -->
                                    <h5 class="card-title mb-3">Floor: <?php echo htmlspecialchars($room['floor']); ?></h5>
                                    <!-- Capacidade do quarto -->
                                    <p class="card-text mb-0">Capacity: <?php echo htmlspecialchars($room['capacity']); ?> people</p>
                                    <!-- Preço do quarto -->
                                    <p class="card-text mb-0">Price: $<?php echo htmlspecialchars($room['price']); ?></p>
                                    <!-- Botão para ver detalhes -->
                                    <a href="#" onclick="showDetails(<?php echo $room['id']; ?>)" class="btn btn-info">Detalhes</a>
                                </div>
                                <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <!-- Botão para reservar o quarto -->
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
    <!-- Inclui o rodapé -->
    <?php include(__DIR__ . '/Partials/footer.php'); ?>

    <!-- Modal para exibir detalhes do quarto -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Título do modal -->
                    <h5 class="modal-title" id="detailsModalLabel">Details of the Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Descrição do quarto -->
                    <p><strong>Description:</strong> <span id="roomDescription"></span></p>
                    <!-- Capacidade do quarto -->
                    <p><strong>Capacity:</strong> <span id="roomCapacity"></span></p>
                    <!-- Preço do quarto -->
                    <p><strong>Price:</strong> <span id="roomPrice"></span></p>
                    <!-- Número do andar -->
                    <p><strong>Floor:</strong> <span id="roomFloor"></span></p>
                </div>
                <div class="modal-footer">
                    <!-- Botão para fechar o modal -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS e dependências -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Função para exibir os detalhes do quarto
        function showDetails(roomId) {
            $.ajax({
                url: '/Projeto_DWS/Views/detalhes.php',
                type: 'GET',
                data: { reservation_id: roomId },
                success: function(response) {
                    // Preenche os campos do modal com os detalhes do quarto
                    $('#roomDescription').text(response.description);
                    $('#roomCapacity').text(response.capacity);
                    $('#roomPrice').text(response.price);
                    $('#roomFloor').text(response.floor);
                    // Exibe o modal
                    $('#detailsModal').modal('show');
                },
                error: function() {
                    // Exibe uma mensagem de erro caso não consiga carregar os detalhes
                    alert('Erro ao carregar os detalhes do quarto.');
                }
            });
        }
    </script>
</body>
</html>