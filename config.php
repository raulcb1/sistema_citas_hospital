<?php
session_start(); // Iniciar sesión si no está iniciada
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php-error.log');
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "citas_hpc";



// Establecer la zona horaria
date_default_timezone_set('America/Lima');


// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

define('UPS_ACTIVA_ID', 2);
define('DEBUG_MODE', 1); // poner 0 para desactivar mensajes detallados

// Validar que exista esa UPS
$verifica = $conn->query("SELECT id FROM ups WHERE id = " . UPS_ACTIVA_ID . " AND activo = 1");
if ($verifica->num_rows === 0) {
    die("Error: UPS no válida o desactivada.");
}
?>