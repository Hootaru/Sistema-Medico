<?php

require_once "conexion_db.php";

if (isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $idCita = $_POST['idCita'];
    $fechaCita = $_POST['fechaCita'];
    $horaCita = $_POST['hora'];

    // Actualizar los datos de la cita en la base de datos
    $sql = "UPDATE citas SET fechaCita = ?, horaCita = ? WHERE idCita = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fechaCita, $horaCita, $idCita);
    $result = $stmt->execute();

    if ($result) {
        // La actualización se realizó correctamente
        header("Location: citas.php");
        exit();
    } else {
        // Error al realizar la actualización
        echo "Error al actualizar los datos de la cita. Por favor, intenta nuevamente.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido";
}
?>