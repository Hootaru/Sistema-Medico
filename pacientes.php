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
    <title>Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/panel.css">
    <link rel="stylesheet" type="text/css" href="css/tabla.css">
    <style>
    .table-container {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
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
    <div class="col-md-10">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-sm-11">
                            <div class="container">
                                <h2>Registro de Pacientes</h2><br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="aggPaciente.php" class="a-1">
                                        <i class="fa-solid fa-user-plus" style="color: #007bff; font-size: 30px;"></i>
                                        Agregar Paciente
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
                                            <a href="pdfPacientes.php" class="btn btn-danger" style="padding: 10px"
                                                target="_blank">
                                                <i class="fa-solid fa-file-pdf"
                                                    style="color: #fff; width: 20px; height: 20px;"></i>
                                                Exportar PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12 table-container table-custom">
                                    <table class="table">
                                        <thead class="thead-dark"
                                            style="position: sticky; top: 0; background-color: #0457af; margin-top: 20px;">
                                            <tr>
                                                <th class="text-center">Nombre</th>
                                                <th class="text-center">Edad</th>
                                                <th class="text-center">Estatura</th>
                                                <th class="text-center">Peso (kg)</th>
                                                <th class="text-center">Sexo</th>
                                                <th class="text-center">Correo</th>
                                                <th class="text-center">Contacto</th>
                                                <th class="text-center">Operaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Realiza la consulta para obtener los datos de pacientes
                                            $sql = "SELECT idPaciente, nombre, edad, estatura, peso, sexo, correo, contacto FROM pacientes ORDER BY idPaciente";
                                            $result = $conn->query($sql);

                                            // Itera sobre los resultados de la consulta y muestra los datos en las filas de la tabla
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td class='text-center'>" . $row["nombre"] . "</td>";
                                                    echo "<td class='text-center'>" . $row["edad"] . "</td>";
                                                    echo "<td class='text-center'>" . $row["estatura"] . "</td>";
                                                    echo "<td class='text-center'>" . $row["peso"] . "</td>";
                                                    echo "<td class='text-center'>" . $row["sexo"] . "</td>";
                                                    echo "<td class='text-center'>" . $row["correo"] . "</td>";
                                                    echo "<td class='text-center'>" . $row["contacto"] . "</td>";
                                                    echo "<td class='text-center'>
                                                    <div class='d-flex justify-content-between align-items-center'>
                                                        <a href='formPaciente.php?id=" . $row['idPaciente'] . "' class='btn btn-primary'>
                                                            <i class='fa-solid fa-user-pen' style='color: #fff; width: 25px; height: 25px;' title='Editar'></i>
                                                        </a>&nbsp;
                                                        <form action='aggCita.php' method='GET'>
                                                            <input type='hidden' name='id' value='" . $row['idPaciente'] . "'>
                                                            <button type='submit' class='btn btn-success' id='generarCita'>
                                                                <i class='fas fa-file-medical' style='color: #fff; width: 25px; height: 25px;' title='Generar Cita'></i>
                                                            </button>
                                                        </form>&nbsp;
                                                        <form action='historialClinico.php' method='GET'>
                                                            <input type='hidden' name='id' value='" . $row['idPaciente'] . "'>
                                                            <button type='submit' class='btn btn-secondary' id='historialClinico'>
                                                                <i class='fas fas fa-book' style='color: #fff; width: 25px; height: 25px;' title='Ver Historial'></i>
                                                            </button>
                                                        </form>&nbsp;
                                                        <form action='eliminarPaciente.php' method='POST' style='display: inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este paciente?\")'>
                                                            <input type='hidden' name='idPaciente' value='" . $row['idPaciente'] . "'>
                                                            <button type='submit' class='btn btn-danger'>
                                                                <i class='fas fa-user-xmark' style='color: #fff; width: 25px; height: 25px;' title='Borrar'></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>";
                                                    echo "</tr>" ; } } else {
                                                    echo "<tr><td colspan='3'>No se encontraron registros</td></tr>" ; }
                                                    // Cierra la conexión a la base de datos $conn->close();
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

    <script>
    const searchInput = document.getElementById('search-input');
    const table = document.querySelector('.table');

    searchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase();

        Array.from(table.tBodies[0].rows).forEach(function(row) {
            const idPaciente = row.cells[0].textContent.toLowerCase();
            const nombre = row.cells[1].textContent.toLowerCase();
            const edad = row.cells[2].textContent.toLowerCase();
            const sexo = row.cells[3].textContent.toLowerCase();
            const correo = row.cells[4].textContent.toLowerCase();
            const contacto = row.cells[5].textContent.toLowerCase();

            if (idPaciente.includes(searchText) || nombre.includes(searchText) || edad.includes(
                    searchText) || sexo.includes(searchText) || correo.includes(searchText) ||
                contacto.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    </script>
</body>

</html>