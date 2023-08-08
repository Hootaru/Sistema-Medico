<?php
require_once 'conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idPaciente = $_POST['idPaciente'];
    $fCita = $_POST['fCita'];
    $hCita = $_POST['hCita'];

    // Consulta SQL para insertar la cita en la base de datos
    $sql = "INSERT INTO citas (idPaciente, fechaCita, horaCita)
            VALUES ('$idPaciente', '$fCita', '$hCita')";

    if ($conn->query($sql) === TRUE) {
        // La inserción se realizó correctamente, redirigir a la página de citas
        header("Location: citas.php");
        exit();
    } else {
        // Hubo un error en la inserción, mostrar el mensaje de error
        echo "Error al insertar los datos en la tabla citas: " . $conn->error;
    }
}

$conn->close();
?>