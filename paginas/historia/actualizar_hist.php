<?php
// Verificar si se han recibido los datos del formulario
if(isset($_POST['id']) && isset($_POST['codigo']) && isset($_POST['fecha'])) {
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $fecha = $_POST['fecha'];

    // Conexión a la base de datos
    include '..\config.php';

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para actualizar la historia
    $sql = "UPDATE historia SET codigo='$codigo', fecha='$fecha' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Historia actualizada exitosamente";
    } else {
        echo "Error al actualizar la historia: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Datos del formulario no recibidos correctamente";
}
?>