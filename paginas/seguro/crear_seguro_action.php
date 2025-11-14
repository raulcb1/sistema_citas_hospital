<?php
// Verificar si se han recibido los datos del formulario
if(isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    // Conexión a la base de datos
    include '..\config.php';

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para insertar un nuevo seguro
    $sql = "INSERT INTO seguro (nombre) VALUES ('$nombre')";

    if ($conn->query($sql) === TRUE) {
        echo "Seguro creado exitosamente";
    } else {
        echo "Error al crear el seguro: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Datos del formulario no recibidos correctamente";
}
?>