<?php
require_once "conexion_db.php";

if (isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
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
    .contenido {
        padding-top: 100px;
    }

    .table-container {
        overflow-y: scroll;
        max-height: 300px;
        /* Ajusta la altura máxima según tus necesidades */
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

    /* Estilo para el botón */
    .switch {
        pointer-events: none;
    }
    </style>
</head>
<?php include 'navbar.php'; ?>

<body>
    <div class="col-md-12">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid bg-white contenido">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Registro de Seguimientos</h2><br>
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="me-auto">
                                    <input type="text" id="search-input" placeholder="Puedes buscar por cualquier campo"
                                        class="form-control" style="width: 500px;">
                                </div>
                                <div>
                                    <a href="panel-admin.php" class="btn btn-primary" style="padding: 10px">Volver</a>
                                </div>
                            </div><br>
                        </div>
                        <div class="col-md-12 table-container table-custom" style="overflow-x: hidden;">
                            <table class="table">
                                <thead class="thead-dark"
                                    style="position: sticky; top: 0; background-color: #343a40; margin-top: 20px;">
                                    <tr>
                                        <th class="text-center">Nombre del Paciente</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Hora</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Operaciones</th>
                                    </tr>
                                </thead>
                                <tbody><br>
                                    <?php
                                    // Obtener la fecha y hora actual
                                    $fecha_actual = date('Y-m-d');
                                    $hora_actual = date('H:i');

                                    // Realiza la consulta para obtener los datos de recetas que coincidan con la fecha y hora actual
                                    $sql = "SELECT idSeguimiento, nombreS, DATE_FORMAT(fechaS, '%Y-%m-%d') AS fecha, TIME_FORMAT(horaS, '%H:%i') AS hora
                                    FROM seguimientos 
                                    WHERE fechaS = '$fecha_actual'
                                    ORDER BY idSeguimiento";

                                    $result = $conn->query($sql);

                                    // Itera sobre los resultados de la consulta y muestra los datos en las filas de la tabla
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>" . $row["nombreS"] . "</td>";
                                            echo "<td class='text-center'>" . date('d/m/Y', strtotime($row["fecha"])) . "</td>";
                                            echo "<td class='text-center'>" . $row["hora"] . "</td>";
                                            echo "<td class='text-center'>
                                                <label class='switch'>
                                                    <input type='checkbox' checked>
                                                    <span class='slider'></span>
                                                    <span class='knob'></span>
                                                </label>
                                            </td>";
                                            echo "<td class='text-center'>
                                                <a href='formSeguimiento.php?id=" . $row['idSeguimiento'] . "' class='btn btn-primary'>
                                                    <i class='fas fa-pencil' style='color: #fff; width: 25px; height: 25px;' title='Editar'></i>
                                                </a>
                                                <form action='eliminarSeguimiento.php' method='POST' style='display: inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este seguimiento?\")'>
                                                    <input type='hidden' name='idSeguimiento' value='" . $row['idSeguimiento'] . "'>
                                                    <button type='submit' class='btn btn-danger'>
                                                        <i class='fas fa-trash-alt' style='color: #fff; width: 25px; height: 25px;'></i> 
                                                    </button>
                                                </form>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr id='no-data-message'><td colspan='6' class='text-center'>Hoy no se han programado seguimientos</td></tr>";
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
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous">
    </script>
</body>

</html>
