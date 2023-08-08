<?php
// Iniciar sesión
require_once "conexion_db.php";

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redireccionar a la página de inicio de sesión
header("Location: login.php");
exit;
?>