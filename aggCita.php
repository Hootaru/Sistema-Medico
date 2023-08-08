<?php
require_once "conexion_db.php";

if (isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

// Obtener la lista de nombres de la base de datos
$consulta = "SELECT idPaciente, nombre FROM pacientes";
$resultado = $conn->query($consulta);

$nombres = array();
while ($fila = $resultado->fetch_assoc()) {
    $idPaciente = $fila['idPaciente'];
    $nombre = $fila['nombre'];
    $nombres[$idPaciente] = $nombre;
}

// Obtener el idPaciente seleccionado
if (isset($_POST['idPaciente'])) {
    $idPaciente = $_POST['idPaciente'];

    // Obtener el nombre del paciente correspondiente al id
    $consultaNombre = "SELECT nombre FROM pacientes WHERE idPaciente = '$idPaciente'";
    $resultadoNombre = $conn->query($consultaNombre);

    if ($resultadoNombre->num_rows > 0) {
        $filaNombre = $resultadoNombre->fetch_assoc();
        $nombrePaciente = $filaNombre['nombre'];
    } else {
        $nombrePaciente = "";
    }
} else {
    $idPaciente = null;
    $nombrePaciente = "";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container-fluid">
        <br><br><br>
        <div class="row">
            <div class="col-md-6">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="list-pat" role="tabpanel"
                        aria-labelledby="list-pat-list">
                        <h2>Agendar cita</h2><br>

                        <form id="agregarCita" action="agregarCita.php" method="POST">

                            <div class="form-group">
                                <label for="nombre">Paciente:</label>

                                <div class="form-group">
                                <select class="form-control" id="nombre" name="idPaciente" required>
                                    <option value="" disabled <?php if (!isset($idPaciente)) echo 'selected'; ?>>
                                        Selecciona un paciente
                                    </option>
                                    <?php foreach ($nombres as $id => $nombre) { ?>
                                        <option value="<?php echo $id; ?>" <?php if (isset($idPaciente) && $idPaciente == $id) echo 'selected'; ?>>
                                            <?php echo $nombre; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div><br>

                            <div class="form-group">
                                <label for="fCita">Fecha de Cita:</label>
                                <input type="date" class="form-control" id="fCita" name="fCita" required>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="hCita">Hora de Cita:</label>
                                <input type="time" class="form-control" id="hCita" name="hCita" required>
                            </div>
                            <br>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" id="guardarCita">Agendar
                                    Cita</button>
                                <button class="btn btn-danger btn1"
                                    onclick="window.location.href = 'citas.php';">Cancelar</button>
                            </div>

                        </form>

                        <script>
                        $(document).ready(function() {
                            // Obtener los datos del paciente al cargar la página
                            var idPaciente = $('#nombre').val();
                            obtenerDatosPaciente(idPaciente);

                            // Evento para obtener los datos del paciente al cambiar la selección del select
                            $('#nombre').change(function() {
                                var idPaciente = $(this).val();
                                obtenerDatosPaciente(idPaciente);
                            });

                            // Evento para enviar el formulario al hacer clic en el botón "Guardar"
                            $('#guardarCita').click(function() {
                                $('#agregarCita').submit();
                            });
                        });

                        // Función para obtener los datos del paciente mediante una petición AJAX
                        function obtenerDatosPaciente(idPaciente) {
                            if (idPaciente !== '') {
                                $.ajax({
                                    url: 'obtenerPaciente.php',
                                    method: 'POST',
                                    data: {
                                        idPaciente: idPaciente
                                    },
                                    success: function(response) {
                                        if (response !== 'error') {
                                            var datosPaciente = JSON.parse(response);
                                            // No se necesita asignar los datos a los campos eliminados
                                        } else {
                                            // Si hubo un error al obtener los datos del paciente
                                            // Aquí puedes mostrar un mensaje de error o realizar alguna acción adicional
                                            console.log('Error al obtener los datos del paciente.');
                                        }
                                    }
                                });
                            }
                        }

                        // Mostrar el nombre del paciente seleccionado
                        var nombrePaciente = '<?php echo $nombrePaciente; ?>';
                        document.getElementById('nombre').value = nombrePaciente;
                        </script>

                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
                            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
                            crossorigin="anonymous"></script>

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
                            crossorigin="anonymous"></script>
</body>

</html>