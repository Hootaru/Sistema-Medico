<?php
// obtenerDatosPaciente.php

require_once 'conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idPaciente'])) {
    $idPaciente = $_POST['idPaciente'];

    // Consulta SQL para obtener el nombre y los demás datos del paciente
    $stmt = $conn->prepare("SELECT nombre, edad, sexo, correo, contacto FROM pacientes WHERE idPaciente = ?");
    $stmt->bind_param("s", $idPaciente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombrePaciente = $row['nombre'];
        $edadPaciente = $row['edad'];
        $sexoPaciente = $row['sexo'];
        $correoPaciente = $row['correo'];
        $contactoPaciente = $row['contacto'];

        // Crear un array asociativo con los datos del paciente
        $datosPaciente = array(
            'nombre' => $nombrePaciente,
            'edad' => $edadPaciente,
            'sexo' => $sexoPaciente,
            'correo' => $correoPaciente,
            'contacto' => $contactoPaciente
        );

        header('Content-Type: application/json');
        echo json_encode($datosPaciente); // Devolver los datos del paciente al cliente en formato JSON
    }

    $stmt->close();
}

$conn->close();
?>