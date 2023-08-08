<?php
// consultaSeguimiento.php

require_once "conexion_db.php";

if (isset($_POST['idPaciente'])) {
    $idPaciente = $_POST['idPaciente'];

    // Realiza la consulta para obtener los datos de los seguimientos del paciente
    $sql = "SELECT nombreS
            FROM seguimientos
            WHERE idPaciente = " . $idPaciente;

    $result = $conn->query($sql);

    // Imprime los nombres de los seguimientos separados por comas
    if ($result->num_rows > 0) {
        $seguimientos = array();
        while ($row = $result->fetch_assoc()) {
            $seguimientos[] = $row["nombreS"];
        }
        echo implode(',', $seguimientos);
    }
} else {
    echo "No se proporcionó el ID de paciente.";
}
?>