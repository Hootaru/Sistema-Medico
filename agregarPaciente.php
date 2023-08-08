<?php
require_once "conexion_db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $estatura = $_POST['estatura'];
    $peso = $_POST['peso'];
    $sexo = $_POST['sexo'];
    $correo = $_POST['correo'];
    $contacto = $_POST['contacto'];

    // Insertar los datos del nuevo paciente en la base de datos
    $sql = "INSERT INTO pacientes (nombre, edad, estatura, peso, sexo, correo, contacto) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssss", $nombre, $edad, $estatura, $peso, $sexo, $correo, $contacto);
    $result = $stmt->execute();

    if ($result) {
        // El paciente se agregó correctamente
        header("Location: pacientes.php");
        exit();
    } else {
        // Error al agregar el paciente
        echo "Error al agregar el paciente. Por favor, intenta nuevamente.";
    }

    $stmt->close();
    $conn->close();
}
?>