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
    <title>Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/panel.css">
    <link rel="stylesheet" type="text/css" href="css/tabla.css">
</head>

<?php include 'navbar.php'; ?>

<body>
    <div class="container-fluid">
        <br><br><br><br>
        <div class="col-md-12">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-sm-18">
                                <div class="container"><br><br>
                                    <h2>Agregar Paciente</h2><br>
                                    <form action="agregarPaciente.php" method="POST">
                                        <div class="form-group">
                                            <label for="nombre">Nombre:</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div><br>
                                        <div class="form-group">
                                            <label for="edad">Edad:</label>
                                            <input type="number" min="1" class="form-control" id="edad" name="edad"
                                                required>
                                        </div><br>
                                        <div class="form-group">
                                            <label for="estatura">Estatura (CM):</label>
                                            <input type="text" pattern="[0-9]+(\.[0-9]+)?" class="form-control"
                                                id="estatura" name="estatura" required>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="peso">Peso (KG):</label>
                                            <input type="text" pattern="[0-9]+(\.[0-9]+)?" class="form-control"
                                                id="peso" name="peso" required>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="sexo">Sexo:</label>
                                            <select name="sexo" class="form-control" required>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                            </select>
                                        </div><br>
                                        <div class="form-group">
                                            <label for="correo">Correo:</label>
                                            <input type="email" class="form-control" id="correo" name="correo" required>
                                        </div><br>
                                        <div class="form-group">
                                            <label for="contacto">Contacto:</label>
                                            <input type="text" class="form-control" id="contacto" name="contacto"
                                                required></input>
                                        </div><br>
                                        <div class="d-flex justify-content-between">
                                            <button type="submit" class="btn btn-primary">Agregar Paciente</button>
                                            <button class="btn btn-danger"
                                                onclick="window.location.href = 'pacientes.php';">Cancelar</button>
                                        </div>
                                    </form>
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