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
    <title>Recetas</title>
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

    .custom-dialog-container {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .custom-dialog {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        width: 400px;
        max-width: 80%;
        max-height: 80%;
        overflow: auto;
        margin-bottom: 20px;
        text-align: justify;
    }

    .custom-dialog p {
        margin-bottom: 20px;
    }

    .custom-dialog button {
        margin-right: 10px;
        display: block;
        margin: 0 auto;
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
                            <h2>Registro de Recetas</h2><br>
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
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Edad</th>
                                        <th class="text-center">Diagnóstico</th>
                                        <th class="text-center">Operaciones</th>
                                    </tr>
                                </thead>
                                <tbody><br>
                                    <?php
                                    // Realiza la consulta para obtener los datos de recetas
                                    $sql = "SELECT r.idReceta, p.nombre, p.edad, r.diagnostico, r.prescripcion, c.idCita, p.idPaciente
                                    FROM recetas AS r
                                    JOIN citas AS c ON r.idCita = c.idCita
                                    JOIN pacientes AS p ON c.idPaciente = p.idPaciente
                                    ORDER BY r.idReceta";

                                    $result = $conn->query($sql);

                                    // Itera sobre los resultados de la consulta y muestra los datos en las filas de la tabla
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='text-center'>" . $row["nombre"] . "</td>";
                                            echo "<td class='text-center'>" . $row["edad"] . "</td>";
                                            echo "<td class='text-center diagnostico-celda' style='max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;' data-diagnostico='" . $row["diagnostico"] . "' data-id-paciente='" . $row["idPaciente"] . "'>" . $row["diagnostico"] . "</td>";
                                            echo "<td class='text-center'>
                                            <form action='consultarSPDF.php' method='POST' style='display: inline;' target='_blank'>
                                                <input type='hidden' name='idPaciente' value='" . $row['idPaciente'] . "'>
                                                <input type='hidden' name='idCita' value='" . $row['idCita'] . "'>
                                                <button type='submit' class='btn btn-success' style='padding: 10px' title='Ver Receta'>
                                                    <i class='fas fa-file-waveform' style='color: #fff; width: 20px; height: 20px;'></i> 
                                                </button>
                                            </form>
                                        <form action='eliminarReceta.php' method='POST' style='display: inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar esta receta?\")'>
                                        <input type='hidden' name='idReceta' value='" . $row['idReceta'] . "'>
                                        <button type='submit' class='btn btn-danger' style='padding: 10px'  title='Eliminar Registro'>
                                            <i class='fas fa-trash-alt' style='color: #fff; width: 20px; height: 20px;'></i> 
                                        </button>
                                        </form>
                                        </td>";
                                        
                                        
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No se encontraron registros</td></tr>";
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

    <div id="dialog-container" class="custom-dialog-container" style="display: none;">
        <div id="dialog" class="custom-dialog">
            <p></p>
            <button id="follow-button" type="button" class="btn btn-primary" hidden onclick="redirectToCalendario()">Dar
                seguimiento</button>
            <button type="button" class="btn btn-danger" onclick="closeDialog()">Cerrar</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous">
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const diagnosticoCeldas = document.querySelectorAll('.diagnostico-celda');

        diagnosticoCeldas.forEach(function(celda) {
            celda.addEventListener('click', function() {
                const diagnosticoCompleto = this.getAttribute('data-diagnostico');

                const dialogContainer = document.getElementById('dialog-container');
                const dialog = document.getElementById('dialog');
                const dialogContent = dialog.querySelector('p');
                const dialogFollowButton = dialog.querySelector('#follow-button');

                dialogContent.textContent = diagnosticoCompleto;
                dialogFollowButton.dataset.idPaciente = this.dataset.idPaciente;

                dialogContainer.style.display = 'flex';
            });
        });

        document.getElementById('dialog-container').addEventListener('click', function(event) {
            if (event.target === this) {
                this.style.display = 'none';
            }
        });
    });

    function redirectToCalendario() {
        const dialogFollowButton = document.getElementById('follow-button');
        const idPaciente = dialogFollowButton.dataset.idPaciente;
        // Aquí puedes redirigir a la página de calendario o cualquier otra acción que desees realizar con el ID del paciente
        console.log('Redirigir a la página de calendario para el paciente con ID:', idPaciente);
    }

    function closeDialog() {
        const dialogContainer = document.getElementById('dialog-container');
        dialogContainer.style.display = 'none';
    }
    </script>
</body>

</html>