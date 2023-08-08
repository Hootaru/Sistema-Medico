<?php

require_once "conexion_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $idSeguimiento = $_POST['idSeguimiento'];
    $nombreS = $_POST['nombreS'];
    $fechaS = $_POST['fechaS'];
    $horaS = $_POST['horaS'];

    // Actualizar los datos del seguimiento en la base de datos
    $sql = "UPDATE seguimientos SET nombreS = ?, fechaS = ?, horaS = ? WHERE idSeguimiento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombreS, $fechaS, $horaS, $idSeguimiento);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: seguimiento.php");
        exit();
    } else {
        echo "Error al actualizar el seguimiento: " . $stmt->error;
    }
}

?>