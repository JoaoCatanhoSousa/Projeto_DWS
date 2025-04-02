<?php
// Inclui o arquivo de conexão com a base de dados
include(__DIR__ . '/../BasedeDados.php');
// Inicia a sessão
session_start();

// Verifica se o ID da reserva foi passado via GET
if (isset($_GET['reservation_id'])) {
    // Obtém o ID da reserva
    $reservationId = $_GET['reservation_id'];

    // Prepara a consulta para obter os detalhes do quarto
    $stmt = $conn->prepare("SELECT r.description_Room, r.capacity_Room, r.price_Room, f.num_floor
                            FROM Room r
                            JOIN Floor f ON r.Floor_id_Floor = f.id_Floor
                            WHERE r.id_Room = ?");
    // Vincula o ID da reserva como parâmetro
    $stmt->bind_param("i", $reservationId);
    // Executa a consulta
    $stmt->execute();
    // Vincula os resultados às variáveis
    $stmt->bind_result($description, $capacity, $price, $floor);
    // Obtém os resultados
    $stmt->fetch();
    // Fecha a declaração
    $stmt->close();
    // Fecha a conexão com a base de dados
    $conn->close();

    // Cria um array com os detalhes do quarto
    $response = [
        'description' => $description,
        'capacity' => $capacity,
        'price' => $price,
        'floor' => $floor
    ];

    // Define o cabeçalho do tipo de conteúdo como JSON
    header('Content-Type: application/json');
    // Retorna os detalhes do quarto em formato JSON
    echo json_encode($response);
    // Encerra o script
    exit();
}
?>