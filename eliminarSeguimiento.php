<?php
require_once 'conexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idSeguimiento = $_POST['idSeguimiento'];

    $sql = "DELETE FROM seguimientos WHERE idSeguimiento = '$idSeguimiento'";

    if ($conn->query($sql) === TRUE) {
        header("Location: seguimiento.php");
        exit();
    } else {
        echo "Error al eliminar el seguimiento: " . $conn->error;
    }
}

$conn->close();
?>