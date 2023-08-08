<?php

require_once "conexion_db.php";

if (isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Obtener los datos del formulario
    $idPaciente = $_POST['idPaciente'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $estatura = $_POST['estatura'];
    $peso = $_POST['peso'];
    $sexo = $_POST['sexo'];
    $correo = $_POST['correo'];
    $contacto = $_POST['contacto'];

    // Actualizar los datos del paciente en la base de datos
    $sql = "UPDATE pacientes SET nombre = ?, edad = ?, estatura = ?, peso = ?, sexo = ?, correo = ?, contacto = ? WHERE idPaciente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssssi", $nombre, $edad, $estatura, $peso, $sexo, $correo, $contacto, $idPaciente);
    $result = $stmt->execute();

    if ($result) {
        // La actualización se realizó correctamente
        header("Location: pacientes.php");
        exit();
    } else {
        // Error al realizar la actualización
        echo "Error al actualizar los datos del paciente. Por favor, intenta nuevamente.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido";
}
?>