<?php
// aggSeguimiento.php

require_once "conexion_db.php";

if (isset($_POST['idPaciente'], $_POST['eventName'], $_POST['eventDate'], $_POST['eventTime'])) {
    $idPaciente = $_POST['idPaciente'];
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];

    // Inserta los datos en la tabla de seguimientos
    $sql = "INSERT INTO seguimientos (idPaciente, nombreS, fechaS, horaS)
            VALUES ('$idPaciente', '$eventName', '$eventDate', '$eventTime')";

    if ($conn->query($sql) === TRUE) {
        // Redirecciona a una página específica después de insertar
        header("Location: citas.php");
        exit(); // Asegúrate de incluir exit() después de la redirección
    } else {
        echo "Error al guardar el evento: " . $conn->error;
    }
} else {
    echo "Faltan datos necesarios para guardar el evento.";
}
?>