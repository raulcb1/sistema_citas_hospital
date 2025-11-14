<?php
// Verificar si se han recibido los datos del formulario
if(isset($_POST['codigo']) && isset($_POST['fecha'])) {
    $codigo = $_POST['codigo'];
    $fecha = $_POST['fecha'];

    // Conexión a la base de datos
    include '..\config.php';

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para insertar una nueva historia
    $sql = "INSERT INTO historia (codigo, fecha) VALUES ('$codigo', '$fecha')";

    if ($conn->query($sql) === TRUE) {
        echo "Historia creada exitosamente";
    } else {
        echo "Error al crear la historia: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Datos del formulario no recibidos correctamente";
}
?>