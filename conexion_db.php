<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se envió un formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    $username = $_POST['usuario'];
    $password = $_POST['contrasena'];

    // Consulta SQL para verificar las credenciales
    $sql = "SELECT * FROM usuarios WHERE usuario = '$username' AND contrasena = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Credenciales correctas, obtener el ID de usuario
        $row = $result->fetch_assoc();
        $userID = $row['idUsuario'];
        $username = $row['usuario'];
    
        // Guardar el ID y nombre de usuario en la sesión
        $_SESSION['userID'] = $userID;
        $_SESSION['username'] = $username;
    
        // Redirigir a la página deseada
        header("Location: panel-admin.php");
        exit();
    }
}

?>