<?php
require_once('library/tcpdf.php');
require_once('conexion_db.php');

// Comprobar la sesión
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
    <link rel="stylesheet" href="assets/tinymce/skins/ui/oxide/skin.min.css">

    <style>
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
    }

    .custom-dialog p {
        margin-bottom: 20px;
        text-align: justify;
    }

    .custom-dialog button {
        margin-right: 10px;
        margin: 0 auto;
    }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="col-md-8">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="container"><br><br><br><br><br><br><br><br><br>
                                <br><br><br><br><br><br><br><br><br>
                                <h2>Receta Médica</h2><br>
                                <?php
                                // Obtener el idCita enviado por GET
                                if (isset($_GET['idCita'])) {
                                    $idCita = $_GET['idCita'];

                                    // Obtener los datos de la cita para el idCita especificado
                                    $sql = "SELECT c.idCita, p.idPaciente, p.nombre, p.edad, p.estatura, p.peso, p.sexo, p.correo, p.contacto,
                                            DATE_FORMAT(c.fechaCita, '%d-%m-%Y') AS fecha,
                                            SUBSTRING(c.horaCita, 1, 5) AS hora
                                            FROM citas AS c
                                            JOIN pacientes AS p ON c.idPaciente = p.idPaciente
                                            WHERE c.idCita = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $idCita);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // Verificar si hay resultados
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $idPaciente = $row['idPaciente'];
                                ?>
                                <form id="recetaForm" method="POST" onsubmit="submitForm(event)">
                                    <input type="hidden" name="idCita" value="<?php echo $row['idCita']; ?>">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" readonly class="form-control form-control-lg" id="nombre"
                                            required name="nombre" value="<?php echo $row['nombre']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="edad">Edad:</label>
                                        <input type="text" readonly class="form-control" id="edad" name="edad" required
                                            value="<?php echo $row['edad']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="estatura">Estatura:</label>
                                        <input type="text" readonly class="form-control" id="estatura" name="estatura"
                                            required value="<?php echo $row['estatura']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="peso">Peso (KG):</label>
                                        <input type="text" readonly class="form-control" id="peso" name="peso" required
                                            value="<?php echo $row['peso']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="diagnostico">Diagnóstico:</label>
                                        <input type="text" class="form-control" id="diagnostico" name="diagnostico"
                                            required>
                                    </div><br>
                                    <div class="form-group">
                                        <label for="observaciones">Observaciones:</label>
                                        <input type="text" class="form-control" id="observaciones" name="observaciones"
                                            required>
                                    </div><br>
                                    <div class="form-group">
                                        <label for="prescripcion">Prescripción:</label><br><br>
                                        <textarea class="form-control" id="prescripcion" name="prescripcion"></textarea>
                                    </div><br>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary" name="update">Generar
                                            Receta</button>
                                        <button type="button" class="btn btn-danger"
                                            onclick="cancelForm()">Cancelar</button>
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

    <div id="dialog-container" class="custom-dialog-container" style="display: none;">
        <div id="dialog" class="custom-dialog">
            <p>¿Quieres dar seguimiento al paciente?</p>
            <?php if ($idPaciente !== null) { ?>
            <button type="button" class="btn btn-primary" onclick="redirectToCalendario(<?php echo $idPaciente; ?>)">Dar
                seguimiento</button>
            <?php } ?>
            <button type="button" class="btn btn-danger" onclick="redirectToCitas()">Cancelar</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="assets/tinymce/tinymce.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous">
    </script>
    <script>
    tinymce.init({
        selector: '#prescripcion',
        height: 300,
        plugins: 'link lists',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link',
        menubar: false,
        skin: 'tinymce-5'
    });

    function submitForm(event) {
        event.preventDefault();
        document.getElementById('recetaForm').target = '_blank';
        document.getElementById('recetaForm').action = 'pdfRecetas.php';
        document.getElementById('recetaForm').submit();
        showDialog();
    }

    function cancelForm() {
        window.location.href = "citas.php";
    }

    function showDialog() {
        document.getElementById('dialog-container').style.display = 'flex';
    }

    function redirectToCalendario(idPaciente) {
        window.location.href = "calendarioSeguimiento.php?idPaciente=" + idPaciente;
    }

    function redirectToCitas() {
        window.location.href = "citas.php";
    }
    </script>
</body>

</html>