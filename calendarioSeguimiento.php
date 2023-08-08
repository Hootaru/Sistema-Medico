<?php
require_once "conexion_db.php";

if (isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

// Obtener el idPaciente (por ejemplo, de la URL o del formulario)
$idPaciente = $_GET['idPaciente']; // Modifica esto según tu implementación
?>

<!DOCTYPE html>
<html>

<head>
    <title>Seguimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/panel.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <style>
    .contenido {
        padding-top: 100px;
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
                            <br><br><br>
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="mt-5">
                                    <a href="citas.php" class="btn btn-primary" style="padding: 10px">Volver</a>
                                </div>
                            </div><br>
                        </div>
                        <div class="col-md-12">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="createEventModal" class="modal fade" role="dialog"><br><br><br><br><br><br><br><br>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agendar Seguimiento</h4>
                </div>
                <div class="modal-body">
                    <form id="createEventForm" method="post" action="aggSeguimiento.php">
                        <div class="form-group">
                            <label>Nombre del paciente</label>
                            <?php
                            // Realiza una consulta para obtener el nombre del paciente
                            $sql = "SELECT nombre FROM pacientes WHERE idPaciente = " . $idPaciente;
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $nombrePaciente = $row["nombre"];

                                // Muestra el nombre del paciente
                                echo "<input type='text' class='form-control' name='eventName' value='" . $nombrePaciente . "' readonly>";
                            }
                            ?>
                        </div><br>

                        <input type="hidden" class="form-control" name="eventDate" id="eventDate" required>

                        <div class="form-group">
                            <label>Hora</label>
                            <input type="time" class="form-control" name="eventTime" required>
                        </div><br>
                        <input type="hidden" name="idPaciente" value="<?php echo $idPaciente; ?>">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/es.js"></script>
    <script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            defaultView: 'month',
            selectable: true,
            selectHelper: true,
            editable: true,
            locale: 'es',
            events: {
                type: 'POST',
                data: {
                    idPaciente: '<?php echo $idPaciente; ?>'
                },
                error: function() {
                    alert('Error al cargar los eventos del calendario.');
                }
            },
            eventRender: function(event, element) {
                element.append("<br/>" + event.title);
            },
            selectAllow: function(selectInfo) {
                // Permitir la selección de fechas
                return true;
            },
            select: function(start, end) {
                // Obtiene la fecha seleccionada y la formatea
                var selectedDate = moment(start).format('YYYY-MM-DD');

                // Establece la fecha en el campo oculto
                $('#eventDate').val(selectedDate);

                // Abre el modal o realiza otras acciones necesarias
                $('#createEventModal').modal('show');
            }
        });
    });
    </script>
</body>

</html>