<?php
// Incluir archivo de configuración
include '..\config.php';

// Verificar si se ha recibido la opción de operación por POST
if(isset($_POST['action'])) {
    $action = $_POST['action'];

    // Ejecutar la acción correspondiente
    switch($action) {
        case 'create':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['nombre'])) {
                // Obtener los datos del formulario
                $nombre = $_POST['nombre'];

                // Preparar la consulta SQL para insertar un nuevo registro
                $sql = "INSERT INTO servicios (nombre) VALUES ('$nombre')";

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Nuevo servicio creado con éxito";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "No se han recibido los datos del formulario";
            }
            break;
        case 'edit':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['id']) && isset($_POST['nombre'])) {
                // Obtener los datos del formulario
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];

                // Preparar la consulta SQL para actualizar el registro
                $sql = "UPDATE servicios SET nombre='$nombre' WHERE id=$id";

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Servicio actualizado con éxito";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "No se han recibido los datos del formulario";
            }
            break;
        case 'delete':
            // Verificar si se ha recibido el ID del registro a desactivar
            if(isset($_POST['id'])) {
                // Obtener el ID del formulario
                $id = $_POST['id'];

                // Preparar la consulta SQL para desactivar el registro
                $sql = "UPDATE servicios SET activo=0 WHERE id=$id";

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Servicio desactivado con éxito";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "No se ha recibido el ID del registro a desactivar";
            }
            break;
        default:
            echo "Opción de operación no válida";
    }
} else {
    echo "No se ha recibido la opción de operación por POST";
}

// Cerrar la conexión
$conn->close();
?>