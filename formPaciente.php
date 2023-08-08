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

<body>
    <?php include 'navbar.php'; ?>
    <div class="col-md-8">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="container"><br><br><br><br>
                                <h2>Edición de Pacientes</h2><br>
                                <?php
                                        if (isset($_GET['id'])) {
                                            $idPaciente = $_GET['id'];

                                            // Obtener los datos del paciente según el ID proporcionado
                                            $sql = "SELECT * FROM pacientes WHERE idPaciente = ?";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bind_param("i", $idPaciente);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                        ?>
                                <form action="editarPaciente.php" method="POST">
                                    <input type="hidden" name="idPaciente" value="<?php echo $row['idPaciente']; ?>">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required
                                            value="<?php echo $row['nombre']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="edad">Edad:</label>
                                        <input type="text" min="1" class="form-control" id="edad" name="edad" required
                                            value="<?php echo $row['edad']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="estatura">Estatura:</label>
                                        <input type="text" min="1" pattern="[0-9]+(\.[0-9]+)?" class="form-control"
                                            id="estatura" name="estatura" required
                                            value="<?php echo $row['estatura']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="peso">Peso (KG):</label>
                                        <input type="text" min="1" pattern="[0-9]+(\.[0-9]+)?" class="form-control"
                                            id="peso" name="peso" required value="<?php echo $row['peso']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="sexo">Sexo:</label>
                                        <select name='sexo' class='form-control' required>
                                            <option value='Masculino'
                                                <?php echo ($row["sexo"]=='Masculino') ? 'selected' : ''; ?>>
                                                Masculino</option>
                                            <option value='Femenino'
                                                <?php echo ($row["sexo"]=='Femenino') ? 'selected' : ''; ?>>
                                                Femenino</option>
                                        </select>
                                    </div><br>
                                    <div class="form-group">
                                        <label for="correo">Correo:</label>
                                        <input type="email" class="form-control" id="correo" name="correo" required
                                            value="<?php echo $row['correo']; ?>">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="contacto">Contacto:</label>
                                        <input type="text" class="form-control" id="contacto" name="contacto" required
                                            value="<?php echo $row['contacto']; ?>">
                                    </div><br>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary" name="update">Actualizar
                                            Paciente</button>
                                        <a class="btn btn-danger" href="pacientes.php">Cancelar</a>
                                    </div>
                                </form>
                                <?php
                                            } else {
                                                echo "No se encontró el paciente";
                                            }
                                            $stmt->close();
                                            $conn->close();
                                        } else {
                                            echo "ID de paciente no proporcionado";
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