<?php
// Verificar si se han recibido los datos del formulario
if(isset($_POST['id']) && isset($_POST['nombre'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];

    // Conexión a la base de datos
    include '..\config.php';

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para actualizar el seguro
    $sql = "UPDATE seguro SET nombre='$nombre' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Seguro actualizado exitosamente";
    } else {
        echo "Error al actualizar el seguro: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Datos del formulario no recibidos correctamente";
}
?>