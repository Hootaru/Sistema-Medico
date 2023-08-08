<?php
require_once 'conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID de la cita a eliminar
    $idCita = $_POST['idCita'];

    // Consulta SQL para eliminar la cita de la tabla citas
    $sql = "DELETE FROM citas WHERE idCita = '$idCita'";

    if ($conn->query($sql) === TRUE) {
        // La eliminación se realizó correctamente, redirigir a la página de citas
        header("Location: citas.php");
        exit();
    } else {
        // Hubo un error en la eliminación, mostrar el mensaje de error
        echo "Error al eliminar la cita: " . $conn->error;
    }
}

$conn->close();
?>