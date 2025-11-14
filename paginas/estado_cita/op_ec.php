<?php
// Conexión a la base de datos
include '..\config.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha enviado una acción
if(isset($_POST['action'])) {
    $action = $_POST['action'];

    // Ejecutar la acción correspondiente
    switch($action) {
        case 'create':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['estado'])) {
                $estado = $_POST['estado'];
                // Consulta SQL para insertar un nuevo estado de cita
                $sql = "INSERT INTO estado_cita (estado) VALUES ('$estado')";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Estado de cita creado exitosamente</p>";
                } else {
                    echo "<p>Error al crear el estado de cita: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Datos del formulario no recibidos correctamente</p>";
            }
            break;

        case 'edit':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['id']) && isset($_POST['estado'])) {
                $id = $_POST['id'];
                $estado = $_POST['estado'];

                // Consulta SQL para actualizar el estado de cita
                $sql = "UPDATE estado_cita SET estado='$estado' WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Estado de cita actualizado exitosamente</p>";
                } else {
                    echo "<p>Error al actualizar el estado de cita: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Datos del formulario no recibidos correctamente</p>";
            }
            break;

        case 'delete':
            // Verificar si se ha enviado un ID de estado de cita para desactivar
            if(isset($_POST['id'])) {
                $id = $_POST['id'];

                // Consulta SQL para desactivar el estado de cita
                $sql = "UPDATE estado_cita SET activo=0 WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Estado de cita desactivado exitosamente</p>";
                } else {
                    echo "<p>Error al desactivar el estado de cita: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>ID de estado de cita no proporcionado</p>";
            }
            break;
    }
}

// Cerrar la conexión
$conn->close();
?>
