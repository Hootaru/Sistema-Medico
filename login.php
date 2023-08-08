<?php
require_once "conexion_db.php";

// Verificar si el usuario ya está autenticado y redirigirlo al panel
if (isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    header("Location: panel-admin.php"); // Cambia "panel-doctor.php" por la página deseada
    exit();
}

// Verificar si se envió el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];

    // Consultar el hash de la contraseña almacenada en la base de datos
    $sql_get_hash = "SELECT usuario, contrasena FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql_get_hash);
    
    // Verificar si la preparación de la consulta fue exitosa
    if (!$stmt) {
        die("Error de preparación de la consulta: " . $conn->error);
    }

    // Unir el valor del parámetro al placeholder '?' y ejecutar la consulta
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Verificar si la ejecución de la consulta fue exitosa
    if (!$stmt->execute()) {
        die("Error de ejecución de la consulta: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Obtener el hash de la contraseña almacenada y el ID del usuario
        $row = $result->fetch_assoc();
        $stored_hash = $row['contrasena'];
        $userID = $row['usuario'];

        // Verificar si la contraseña proporcionada coincide con el hash almacenado
        if (password_verify($password, $stored_hash)) {
            // Inicio de sesión exitoso, establecer las variables de sesión
            $_SESSION['userID'] = $userID;
            $_SESSION['username'] = $username;

            // Redirigir al usuario al panel de control
            header("Location: panel-admin.php"); // Cambia "panel-doctor.php" por la página deseada
            exit();
        } else {
            $errorMessage = "Usuario o Contraseña Incorrectos.";
        }
    } else {
        $errorMessage = "Usuario o Contraseña Incorrectos.";
    }

    // Cerrar la consulta
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Inicio de sesión</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>

<body>
    <div class="container-Login">
        <img src="images/logo.png" class="logo">
        <form class="form" action="" method="POST">
            <p id="heading">Iniciar Sesión</p>
            <?php if (isset($errorMessage)) : ?>
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
                <input autocomplete="off" name="usuario" placeholder="Usuario" class="input-field" type="text">
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
            <p id="register-link">¿No tienes cuenta? <a href="registro.php" id="enlace">Regístrate aquí</a></p>
            <button
                class="button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ingresar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>