<?php
require_once('conexion_db.php');

$idReceta = isset($_POST['idReceta']) ? $_POST['idReceta'] : '';

// Realiza la eliminación de la receta
$sql = "DELETE FROM recetas WHERE idReceta = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idReceta);

if ($stmt->execute()) {
    header("Location: recetas.php");
    exit(); // 
} else {
    echo 'error'; // Envía una respuesta de error al cliente
}

$stmt->close();
$conn->close();
?>