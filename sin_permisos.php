<?php
include 'config.php';
if (!isset($_SESSION['usuario_id'])) {
    //header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Acceso Denegado</title>
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="alert alert-danger text-center">
            <h4>¡Acceso denegado!</h4>
            <p>No tienes permisos para ver esta sección.</p>
            <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>