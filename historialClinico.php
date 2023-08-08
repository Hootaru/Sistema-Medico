<?php

require_once "conexion_db.php";

if (isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

// Obtener el idPaciente de la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idPaciente = $_GET['id'];
} else {
    echo "Error: ID de paciente inválido.";
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Seguimientos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/panel.css">
    <link rel="stylesheet" type="text/css" href="css/tabla.css">
    <style>
    .table-container {
        overflow-y: auto;
        max-height: 300px;
    }

    .table {
        table-layout: fixed;
        width: 100%;
    }

    .table th,
    .table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table-custom {
        width: 100%;
    }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- Tabla 1: Citas -->
    <div class="col-md-12 tabla-separada">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid bg-white contenido">
                    <div class="row">
                        <div class="col-md-12">
                            <br><br><br><br><br><br>
                            <br><br><br><br><br><br>
                            <br><br><br><br><br><br>
                            <br><br><br><br><br><br>
                            <br><br><br><br>
                            <br><br><br>
                            <h2>Citas</h2>
                            <div class="d-flex justify-content-end align-items-center">
                                <div>
                                    <a href="pacientes.php" class="btn btn-primary" style="padding: 10px">Volver</a>
                                </div>
                            </div><br>
                        </div>
                        <div class="col-md-12 table-container table-custom">
                            <table class="table">
                                <thead class="thead-dark" style="position: sticky; top: 0; background-color: #343a40;">
                                    <tr>
                                        <th class="text-center">Fecha de Cita</th>
                                        <th class="text-center">Hora de Cita</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Realiza la consulta para obtener todos los datos de citas
                                    $sqlCitas = "SELECT p.idPaciente, p.nombre, p.edad, p.estatura, p.peso, p.sexo, p.correo, p.contacto, c.idCita, c.fechaCita, SUBSTRING(c.horaCita, 1, 5) AS hora, c.receta_generada
                                    FROM pacientes AS p
                                    JOIN citas AS c ON p.idPaciente = c.idPaciente
                                    WHERE p.idPaciente = ?
                                    ORDER BY c.idCita";

                                    $stmtCitas = $conn->prepare($sqlCitas);
                                    $stmtCitas->bind_param("i", $idPaciente);
                                    $stmtCitas->execute();
                                    $resultCitas = $stmtCitas->get_result();

                                    // Variable para realizar un seguimiento de si no se encontraron datos
                                    $noDataCitas = true;

                                    // Itera sobre los resultados de la consulta y muestra los datos en las filas de la tabla
                                    if ($resultCitas !== null && $resultCitas->num_rows > 0) {
                                        while ($row = $resultCitas->fetch_assoc()) {
                                            echo "<tr>";
                                            $fechaCita = date("d/m/Y", strtotime($row["fechaCita"]));
                                            echo "<td class='text-center'>" . $fechaCita . "</td>";
                                            echo "<td class='text-center'>" . $row["hora"] . "</td>";
                                            echo "</tr>";

                                            $noDataCitas = false; // Se encontraron datos, actualiza la variable
                                        }
                                    }

                                    // Si no se encontraron datos, muestra el mensaje "Sin datos existentes"
                                    if ($noDataCitas) {
                                        echo "<tr id='no-data-message'><td colspan='2' class='text-center'>No se han encontrado citas</td></tr>";
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla 2: Historial Clínico -->
    <div class="col-md-12 tabla-separada">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid bg-white ">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Seguimientos</h2><br>
                        </div>
                        <div class="col-md-12 table-container table-custom">
                            <table class="table">
                                <thead class="thead-dark" style="position: sticky; top: 0; background-color: #343a40;">
                                    <tr>
                                        <th class="text-center">Fecha de Seguimiento</th>
                                        <th class="text-center">Hora de Seguimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Realiza la consulta para obtener los datos de todos los seguimientos
                                    $sqlHistorial = "SELECT idSeguimiento, nombreS, DATE_FORMAT(fechaS, '%Y-%m-%d') AS fecha, TIME_FORMAT(horaS, '%H:%i') AS hora
                                    FROM seguimientos
                                    WHERE idPaciente = ?
                                    ORDER BY idSeguimiento";

                                    $stmtHistorial = $conn->prepare($sqlHistorial);
                                    $stmtHistorial->bind_param("i", $idPaciente);
                                    $stmtHistorial->execute();
                                    $resultHistorial = $stmtHistorial->get_result();

                                    // Variable para realizar un seguimiento de si no se encontraron datos
                                    $noDataHistorial = true;

                                    // Itera sobre los resultados de la consulta y muestra los datos en las filas de la tabla
                                    if ($resultHistorial !== null && $resultHistorial->num_rows > 0) {
                                        while ($row = $resultHistorial->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>" . date('d/m/Y', strtotime($row["fecha"])) . "</td>";
                                            echo "<td class='text-center'>" . $row["hora"] . "</td>";
                                            echo "</tr>";

                                            $noDataHistorial = false; // Se encontraron datos, actualiza la variable
                                        }
                                    }

                                    // Si no se encontraron datos, muestra el mensaje "Sin datos existentes"
                                    if ($noDataHistorial) {
                                        echo "<tr id='no-data-message'><td colspan='2' class='text-center'>No se han encontrado seguimientos</td></tr>";
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla 3: Recetas -->
    <div class="col-md-12 tabla-separada">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid bg-white ">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Recetas</h2><br>
                        </div>
                        <div class="col-md-12 table-container table-custom">
                            <table class="table">
                                <thead class="thead-dark" style="position: sticky; top: 0; background-color: #343a40;">
                                    <tr>
                                        <th class="text-center">Diagnóstico</th>
                                        <th class="text-center">Observaciones</th>
                                        <th class="text-center">Receta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Realiza la consulta para obtener los datos de recetas
                                    $sqlRecetas = "SELECT r.idReceta, p.nombre, p.edad, r.diagnostico, r.observaciones, r.prescripcion, c.idCita, p.idPaciente
                                    FROM recetas AS r
                                    JOIN citas AS c ON r.idCita = c.idCita
                                    JOIN pacientes AS p ON c.idPaciente = p.idPaciente
                                    WHERE p.idPaciente = ?
                                    ORDER BY r.idReceta";

                                    $stmtRecetas = $conn->prepare($sqlRecetas);
                                    $stmtRecetas->bind_param("i", $idPaciente);
                                    $stmtRecetas->execute();
                                    $resultRecetas = $stmtRecetas->get_result();

                                    // Variable para realizar un seguimiento de si no se encontraron datos
                                    $noDataRecetas = true;

                                    // Itera sobre los resultados de la consulta y muestra los datos en las filas de la tabla
                                    if ($resultRecetas !== null && $resultRecetas->num_rows > 0) {
                                        while ($row = $resultRecetas->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center diagnostico-celda' style='max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;' data-diagnostico='" . $row["diagnostico"] . "' data-id-paciente='" . $row["idPaciente"] . "'>" . $row["diagnostico"] . "</td>";
                                            echo "<td class='text-center diagnostico-celda' style='max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;' data-diagnostico='" . $row["observaciones"] . "' data-id-paciente='" . $row["idPaciente"] . "'>" . $row["observaciones"] . "</td>";
                                            echo "<td class='text-center'>
                                            <form action='consultarSPDF.php' method='POST' style='display: inline;' target='_blank'>
                                                <input type='hidden' name='idPaciente' value='" . $row['idPaciente'] . "'>
                                                <input type='hidden' name='idCita' value='" . $row['idCita'] . "'>
                                                <button type='submit' class='btn btn-success' style='padding: 10px' title='Ver Receta'>
                                                    <i class='fas fa-file-waveform' style='color: #fff; width: 20px; height: 20px;'></i> 
                                                </button>
                                            </form>
                                        </td>";
                                        echo "</tr>";

                                            $noDataRecetas = false; // Se encontraron datos, actualiza la variable
                                        }
                                    }

                                    // Si no se encontraron datos, muestra el mensaje "Sin datos existentes"
                                    if ($noDataRecetas) {
                                        echo "<tr id='no-data-message'><td colspan='2' class='text-center'>No se han encontrado recetas</td></tr>";
                                    }

                                    // Cierra la conexión a la base de datos
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resto del código HTML y JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous">
    </script>
</body>

</html>