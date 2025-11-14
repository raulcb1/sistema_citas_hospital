<?php
// Conexión a la base de datos
include '..\config.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha recibido la opción de operación por POST
if(isset($_POST['action'])) {
    $action = $_POST['action'];


    // Ejecutar la acción correspondiente
    switch($action) {
        case 'create':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['tipo'])) {
                // Obtener los datos del formulario
                $tipo = $_POST['tipo'];

                // Consulta SQL para insertar un nuevo tipo de atención
                $sql = "INSERT INTO tipo_atencion (tipo) VALUES ('$tipo')";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Tipo de atención creado exitosamente</p>";
                } else {
                    echo "<p>Error al crear el tipo de atención: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Datos del formulario no recibidos correctamente</p>";
            }
            break;

        case 'edit':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['id']) && isset($_POST['tipo'])) {
                $id = $_POST['id'];
                $tipo = $_POST['tipo'];

                // Consulta SQL para actualizar el tipo de atención
                $sql = "UPDATE tipo_atencion SET tipo='$tipo' WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Tipo de atención actualizado exitosamente</p>";
                } else {
                    echo "<p>Error al actualizar el tipo de atención: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Datos del formulario no recibidos correctamente</p>";
            }
            break;

        case 'delete':
            // Verificar si se ha enviado un ID de tipo de atención para desactivar
            if(isset($_POST['id'])) {
                $id = $_POST['id'];

                // Consulta SQL para desactivar el tipo de atención
                $sql = "UPDATE tipo_atencion SET activo=FALSE WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Tipo de atención desactivado exitosamente</p>";
                } else {
                    echo "<p>Error al desactivar el tipo de atención: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>ID de tipo de atención no proporcionado</p>";
            }
            break;
    }
}

// Cerrar la conexión
$conn->close();
?>