<?php

require_once "conexion_db.php"; 

if(isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $idSeguimiento = $_POST['idSeguimiento'];
    $nombreS = $_POST['nombreS'];
    $fechaS = $_POST['fechaS'];
    $horaS = $_POST['horaS'];

    // Actualizar los datos del seguimiento en la base de datos
    $sql = "UPDATE seguimientos SET nombreS = ?, fechaS = ?, horaS = ? WHERE idSeguimiento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombreS, $fechaS, $horaS, $idSeguimiento);

    if ($stmt->execute()) {
        header("Location: seguimiento.php");
        exit();
    } else {
        echo "Error al actualizar el seguimiento: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
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
    <link rel="stylesheet" type="text/css" href="css/tabla.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="col-md-8">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="container">
                                <h2>Edición de Seguimiento</h2><br>
                                <?php
                                if (isset($_GET['id'])) {
                                    $idSeguimiento = $_GET['id'];

                                    // Obtener los datos del seguimiento según el ID proporcionado
                                    $sql = "SELECT * FROM seguimientos WHERE idSeguimiento = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $idSeguimiento);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                ?>
                                <form action="editarSeguimiento.php" method="POST">
                                    <input type="hidden" name="idSeguimiento"
                                        value="<?php echo $row['idSeguimiento']; ?>">
                                    <div class="form-group">
                                        <label for="nombreS">Nombre del Paciente:</label>
                                        <input type="text" readonly class="form-control" id="nombreS" name="nombreS"
                                            required value="<?php echo $row['nombreS']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="fechaS">Fecha:</label>
                                        <input type="date" class="form-control" id="fechaS" name="fechaS" required
                                            value="<?php echo $row['fechaS']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="horaS">Hora:</label>
                                        <input type="time" class="form-control" id="horaS" name="horaS" required
                                            value="<?php echo $row['horaS']; ?>">
                                    </div><br>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary" name="update">Actualizar
                                            Seguimiento</button>
                                        <a class="btn btn-danger" href="seguimiento.php">Cancelar</a>
                                    </div>
                                </form>
                                <?php
                                    } else {
                                        echo "No se encontró el seguimiento";
                                    }
                                    $stmt->close();
                                    $conn->close();
                                } else {
                                    echo "ID de seguimiento no proporcionado";
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
</body>

</html>