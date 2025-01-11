<?php
include(__DIR__ . '/../BasedeDados.php');

function updateReservationDates($conn, $reservationId, $newCheckin, $newCheckout) {
    // Atualizar as datas de entrada e saída da reserva
    $stmt = $conn->prepare("UPDATE Reservation SET date_Entrance = ?, date_Out = ? WHERE id_Reservation = ?");
    $stmt->bind_param("ssi", $newCheckin, $newCheckout, $reservationId);

    if ($stmt->execute()) {
        echo "Reservation dates successfully updated!";
    } else {
        echo "Error updating reservation dates: " . $stmt->error;
    }

    $stmt->close();
}

// Exemplo de uso da função
$reservationId = 1; // ID da reserva que você deseja atualizar
$newCheckin = '2023-12-01'; // Nova data de entrada
$newCheckout = '2023-12-10'; // Nova data de saída

updateReservationDates($conn, $reservationId, $newCheckin, $newCheckout);

$conn->close();
?>