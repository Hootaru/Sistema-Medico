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
    <title>Citas</title>
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
                            <h2>Registro de Citas</h2><br>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="aggCita.php" class="a-1">
                                    <i class="fa-solid fa-hospital-user" style="color: #007bff; font-size: 30px;"></i>
                                    Agendar Cita
                                </a>
                                <div class="d-flex">
                                    <div class="me-5">
                                        <input type="text" id="search-input"
                                            placeholder="Puedes buscar por cualquier campo" class="form-control"
                                            style="width: 500px;">
                                    </div>
                                    <div>
                                        <a href="panel-admin.php" class="btn btn-primary"
                                            style="padding: 10px">Volver</a>
                                        <a href="pdfCitas.php" class="btn btn-danger" style="padding: 10px"
                                            target="_blank">
                                            <i class="fa-solid fa-file-pdf"
                                                style="color: #fff; width: 20px; height: 20px;"></i>
                                            Exportar PDF
                                        </a>
                                    </div>
                                </div>
                            </div><br>
                        </div>
                        <div class="col-md-12 table-container table-custom" style="overflow-x: hidden;">
                            <table class="table">
                                <thead class="thead-dark"
                                    style="position: sticky; top: 0; background-color: #343a40; margin-top: 20px;">
                                    <tr>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Edad</th>
                                        <th class="text-center">Contacto</th>
                                        <th class="text-center">Fecha de Cita</th>
                                        <th class="text-center">Hora de Cita</th>
                                        <th class="text-center">Operaciones</th>
                                    </tr>
                                </thead>
                                <tbody><br>
                                    <?php
                                        // Obtiene la fecha actual en formato "día, mes y año"
                                        $fechaActual = date("d/m/Y");

                                        // Realiza la consulta para obtener los datos de citas de la fecha actual
                                        $sql = "SELECT p.idPaciente, p.nombre, p.edad, p.sexo, p.correo, p.contacto, c.idCita, c.fechaCita, SUBSTRING(c.horaCita, 1, 5) AS hora, c.receta_generada
                                                FROM pacientes AS p
                                                JOIN citas AS c ON p.idPaciente = c.idPaciente
                                                WHERE DATE_FORMAT(c.fechaCita, '%d/%m/%Y') = '$fechaActual'
                                                ORDER BY c.idCita";

                                        $result = $conn->query($sql);

                                        // Variable para realizar un seguimiento de si no se encontraron datos
                                        $noData = true;

                                        // Itera sobre los resultados de la consulta y muestra los datos en las filas de la tabla
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td class='text-center'>" . $row["nombre"] . "</td>";
                                                echo "<td class='text-center'>" . $row["edad"] . "</td>";
                                            echo "<td class='text-center'>" . $row["contacto"] . "</td>";
                                            $fechaCita = date("d/m/Y", strtotime($row["fechaCita"]));
                                            echo "<td class='text-center'>" . $fechaCita . "</td>";
                                            echo "<td class='text-center'>" . $row["hora"] . "</td>";
                                            echo "<td class='text-center'>";

                                            echo " <a href='formCita.php?id=" . $row['idCita'] . "' class='btn btn-primary'>
                                            <i class='fa-solid fa-user-pen' style='color: #fff; width: 20px; height: 20px;' title='Editar'></i>
                                        </a>";

                                            // Verifica si la receta ha sido generada
                                            if ($row['receta_generada'] == 0) {
                                                echo "<form action='formReceta.php' method='GET' style='display: inline;'>
                                                        <input type='hidden' name='idCita' value='" . $row['idCita'] . "'>
                                                        <button type='submit' class='btn btn-success'>
                                                            <i class='fas fa-file-waveform' style='color: #fff; width: 20px; height: 20px;' title='Generar Receta'></i>
                                                        </button>
                                                    </form>";
                                            }

                                            echo "<form action='eliminarCita.php' method='POST' style='display: inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar esta cita?\")'>
                                                    <input type='hidden' name='idCita' value='" . $row['idCita'] . "'>
                                                    <button type='submit' class='btn btn-danger'>
                                                        <i class='fas fa-user-xmark' style='color: #fff; width: 20px; height: 20px;' title='Borrar'></i>
                                                    </button>
                                                </form>";

                                            echo "</td>";
                                            echo "</tr>";

                                            $noData = false; // Se encontraron datos, actualiza la variable
                                        }
                                    }

                                    // Si no se encontraron datos, muestra el mensaje "Sin datos existentes"
                                    if ($noData) {
                                        echo "<tr id='no-data-message'><td colspan='6' class='text-center'>Hoy no se han programado citas</td></tr>";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous">
    </script>
    <script>
    const searchInput = document.getElementById('search-input');
    const table = document.querySelector('.table');
    const noDataMessage = document.getElementById(
        'no-data-message'); // Agrega un elemento con el ID 'no-data-message' al HTML

    searchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        let noData = true; // Variable para realizar un seguimiento de si no se encontraron datos

        Array.from(table.tBodies[0].rows).forEach(function(row) {
            const nombre = row.cells[0].textContent.toLowerCase(); // Índice 0 para el campo "nombre"
            const edad = row.cells[1].textContent.toLowerCase(); // Índice 1 para el campo "edad"
            const contacto = row.cells[2].textContent
                .toLowerCase(); // Índice 2 para el campo "contacto"
            const fechaCita = row.cells[3].textContent
                .toLowerCase(); // Índice 3 para el campo "fechaCita"
            const horaCita = row.cells[4].textContent.toLowerCase(); // Índice 4 para el campo "hora"

            if (nombre.includes(searchText) || edad.includes(searchText) || contacto.includes(
                    searchText) ||
                fechaCita.includes(searchText) || horaCita.includes(searchText)) {
                row.style.display = '';
                noData = false; // Se encontraron datos, actualiza la variable
            } else {
                row.style.display = 'none';
            }
        });

        // Muestra u oculta el mensaje "Sin datos existentes"
        if (noData) {
            noDataMessage.style.display = 'table-row';
        } else {
            noDataMessage.style.display = 'none';
        }
    });
    </script>
</body>

</html>