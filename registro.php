<?php
require_once "conexion_db.php";

// Variables para almacenar mensajes
$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];

    // Verificamos si el usuario ya existe en la base de datos
    $sql_check_user = "SELECT * FROM usuarios WHERE usuario = '$username'";
    $result_check_user = $conn->query($sql_check_user);

    if ($result_check_user->num_rows > 0) {
        $errorMessage = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; El usuario ya existe.";
    } else {
        // Encriptamos la contraseña antes de guardarla en la base de datos
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertamos el nuevo usuario en la base de datos
        $sql_insert_user = "INSERT INTO usuarios (usuario, contrasena) VALUES ('$username', '$hashed_password')";
        if ($conn->query($sql_insert_user) === TRUE) {
            $successMessage = "Registro exitoso.";

            // Guardamos el mensaje de éxito en una variable de sesión
            $_SESSION['successMessage'] = $successMessage;

            // Redireccionamos al usuario a la página de inicio de sesión después de 2 segundos
            header("Location: login.php");
            exit(); // Es importante incluir exit() para asegurar que la redirección sea efectiva
        } else {
            $errorMessage = "Error al registrar el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <style>
    .buttons-container {
        display: flex;
        justify-content: space-between;
    }

    /* Estilo para los botones en el lado izquierdo */
    .button-left {
        /* Puedes ajustar el margen derecho según necesites */
        margin-left: 10px;
    }

    /* Estilo para los botones en el lado derecho */
    .button-right {
        /* Puedes ajustar el margen izquierdo según necesites */
        margin-left: 10px;
    }
    </style>
</head>

<body>
    <div class="container-Login">
        <img src="images/logo.png" class="logo">
        <form class="form" action="" method="POST">
            <p id="heading">Registro de Usuario </p>
            <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-primary alert-right" role="alert">
                <?php echo $successMessage; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-primary alert-right" role="alert">
                <?php echo $errorMessage; ?>
            </div>
            <?php endif; ?>
            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z">
                    </path>
                </svg>
                <input autocomplete="off" name="usuario" placeholder="Usuario" class="input-field" type="text" required>
            </div>
            <div class="field">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z">
                    </path>
                </svg>
                <input name="contrasena" placeholder="Contraseña" class="input-field" type="password" required>
            </div><br>
            <div class="buttons-container">
                <button class="button1 button-left">Registrarse</button>
                <a href="login.php" class="button2 button-right">&nbsp;&nbsp;&nbsp;&nbsp;Cancelar</a>
            </div>
        </form>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>

</html>