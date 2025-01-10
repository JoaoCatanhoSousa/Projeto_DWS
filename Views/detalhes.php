<?php
include(__DIR__ . '/../BasedeDados.php');
session_start();

if (isset($_GET['reservation_id'])) {
    $reservationId = $_GET['reservation_id'];

    $stmt = $conn->prepare("SELECT r.description_Room, r.capacity_Room, r.price_Room, f.num_floor
                            FROM Room r
                            JOIN Floor f ON r.Floor_id_Floor = f.id_Floor
                            WHERE r.id_Room = ?");
    $stmt->bind_param("i", $reservationId);
    $stmt->execute();
    $stmt->bind_result($description, $capacity, $price, $floor);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    $response = [
        'description' => $description,
        'capacity' => $capacity,
        'price' => $price,
        'floor' => $floor
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>