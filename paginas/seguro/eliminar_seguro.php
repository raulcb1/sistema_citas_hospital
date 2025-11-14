<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Seguro</title>
</head>
<body>
    <?php
    // Verificar si se ha enviado un ID de seguro para desactivar
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        // Conexión a la base de datos
        include '..\config.php';

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para actualizar el campo activo a FALSE en lugar de eliminar el seguro
        $sql = "UPDATE seguro SET activo=FALSE WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Seguro desactivado exitosamente";
        } else {
            echo "Error al desactivar el seguro: " . $conn->error;
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        echo "ID de seguro no proporcionado";
    }
    ?>
</body>
</html>