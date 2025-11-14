<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Historia</title>
</head>
<body>
    <h2>Eliminar Historia</h2>
    <?php
    // Verificar si se ha enviado un ID de historia para desactivar
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        // Conexión a la base de datos
        include '..\config.php';

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para eliminar la historia de la tabla
        $sql = "UPDATE historia SET activo=FALSE WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Historia eliminada exitosamente";
        } else {
            echo "Error al eliminar la historia: " . $conn->error;
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        echo "ID de historia no proporcionado";
    }
    ?>
</body>
</html>