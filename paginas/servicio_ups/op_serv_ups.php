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
            if(isset($_POST['ups_id']) && isset($_POST['servicio_id'])) {
                // Obtener los datos del formulario
                $ups_id = $_POST['ups_id'];
                $servicio_id = $_POST['servicio_id'];

                // Preparar la consulta SQL para insertar un nuevo registro
                $sql = "INSERT INTO servicio_ups (ups_id, servicio_id) VALUES ('$ups_id', '$servicio_id')";

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Nuevo servicio UPS creado con éxito";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "No se han recibido los datos del formulario";
            }
            break;
        case 'edit':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['id']) && isset($_POST['ups_id']) && isset($_POST['servicio_id'])) {
                // Obtener los datos del formulario
                $id = $_POST['id'];
                $ups_id = $_POST['ups_id'];
                $servicio_id = $_POST['servicio_id'];

                // Preparar la consulta SQL para actualizar el registro
                $sql = "UPDATE servicio_ups SET ups_id='$ups_id', servicio_id='$servicio_id' WHERE id=$id";

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Servicio UPS actualizado con éxito";
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
                $sql = "UPDATE servicio_ups SET activo=0 WHERE id=$id";

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Servicio UPS desactivado con éxito";
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