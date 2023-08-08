<?php
require_once "conexion_db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se haya enviado el formulario con el botón "Eliminar"

    // Obtener el ID del paciente a eliminar
    $idPaciente = $_POST['idPaciente'];

    // Realizar la eliminación del paciente en la base de datos
    $sql = "DELETE FROM pacientes WHERE idPaciente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idPaciente);
    $stmt->execute();

    // Verificar si la eliminación fue exitosa
    if ($stmt->affected_rows > 0) {
        header("Location: pacientes.php");
        exit(); // Terminar la ejecución del script después de la redirección
    } else {
        echo "No se pudo eliminar el paciente.";
    }

    $stmt->close();
    $conn->close();
}
?>