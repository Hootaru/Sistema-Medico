<?php
require_once "conexion_db.php";

if(isset($_SESSION['userID']) && isset($_SESSION['username'])) {
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
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/panel.css">
    <link rel="stylesheet" type="text/css" href="css/tabla.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="col-md-10">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-sm-8">

                            <h2>Edición de Citas</h2><br>
                            <div class="container">
                                <?php
                                if (isset($_GET['id'])) {
                                    $idCita = $_GET['id'];

                                    // Obtener los datos de la cita según el ID proporcionado
                                    $sql = "SELECT p.idPaciente, p.nombre, p.edad, p.sexo, p.correo, p.contacto, c.idCita, c.fechaCita, SUBSTRING(c.horaCita, 1, 5) AS hora
                                            FROM pacientes AS p
                                            JOIN citas AS c ON p.idPaciente = c.idPaciente
                                            WHERE c.idCita = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $idCita);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        ?>
                                <form action="editarCita.php" method="POST">
                                    <input type="hidden" name="idCita" value="<?php echo $row['idCita']; ?>">
                                    <div class="form-group">
                                        <label for="nombre">Nombre del Paciente:</label>
                                        <input type="text" class="form-control" disabled id="nombre" name="nombre"
                                            required value="<?php echo $row['nombre']; ?>">
                                    </div><br>

                                    <div class="form-group">
                                        <label for="fechaCita">Fecha de Cita:</label>
                                        <input type="date" class="form-control" id="fechaCita" name="fechaCita" required
                                            value="<?php echo $row['fechaCita']; ?>">
                                        <span id="fechaError" style="color: red;"></span>
                                    </div><br>

                                    <div class="form-group">
                                        <label for="hora">Hora de Cita:</label>
                                        <input type="time" class="form-control" id="hora" name="hora" required
                                            value="<?php echo $row['hora']; ?>">
                                    </div><br>

                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary">Actualizar Cita</button>
                                        <a class="btn btn-danger" href="citas.php">Cancelar</a>
                                    </div>
                                </form>
                                <?php
                                        } else {
                                            echo "No se encontró la cita";
                                        }
                                        $stmt->close();
                                        $conn->close();
                                    } else {
                                        echo "ID de cita no proporcionado";
                                    }
                                    ?>
                            </div>
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

    <script>
    var fechaCitaInput = document.getElementById('fechaCita');
    var fechaError = document.getElementById('fechaError');

    fechaCitaInput.addEventListener('change', validarFecha);

    function validarFecha() {
        var fechaCita = new Date(fechaCitaInput.value);
        var fechaActual = new Date();
        var fechaActualFormato = obtenerFormatoFecha(fechaActual);

        fechaCita.setHours(0, 0, 0, 0);
        fechaActual.setHours(0, 0, 0, 0);

        if (fechaCita < fechaActual) {
            fechaError.textContent = 'La fecha debe ser igual o mayor a ' + fechaActualFormato + '.';
            fechaCitaInput.value = '';
            fechaCitaInput.focus();
        } else {
            fechaError.textContent = '';
        }
    }

    function obtenerFormatoFecha(fecha) {
        var day = fecha.getDate();
        var month = fecha.getMonth() + 1;
        var year = fecha.getFullYear();

        if (day < 10) {
            day = '0' + day;
        }

        if (month < 10) {
            month = '0' + month;
        }

        return day + '/' + month + '/' + year;
    }
    </script>
</body>

</html>