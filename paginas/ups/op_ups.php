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

            if(isset($_POST['codigo_ups']) && isset($_POST['nombre']) && isset($_POST['direccion']) && isset($_POST['departamento']) && isset($_POST['provincia']) && isset($_POST['distrito'])) {
                $codigo_ups = $_POST['codigo_ups'];
                $nombre = $_POST['nombre'];
                $direccion = $_POST['direccion'];
                $departamento = $_POST['departamento'];
                $provincia = $_POST['provincia'];
                $distrito = $_POST['distrito'];

                // Consulta SQL para insertar un nuevo registro en la tabla 'ups'
                $sql = "INSERT INTO ups (codigo_ups, nombre, direccion, departamento, provincia, distrito) VALUES ('$codigo_ups', '$nombre', '$direccion', '$departamento', '$provincia', '$distrito')";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Registro de UPS creado exitosamente</p>";
                } else {
                    echo "<p>Error al crear el registro de UPS: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Datos del formulario no recibidos correctamente</p>";
            }
            break;

        case 'edit':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['id']) && isset($_POST['codigo_ups']) && isset($_POST['nombre']) && isset($_POST['direccion']) && isset($_POST['departamento']) && isset($_POST['provincia']) && isset($_POST['distrito'])) {
                $id = $_POST['id'];
                $codigo_ups = $_POST['codigo_ups'];
                $nombre = $_POST['nombre'];
                $direccion = $_POST['direccion'];
                $departamento = $_POST['departamento'];
                $provincia = $_POST['provincia'];
                $distrito = $_POST['distrito'];

                // Consulta SQL para actualizar un registro en la tabla 'ups'
                $sql = "UPDATE ups SET codigo_ups = '$codigo_ups', nombre = '$nombre', direccion = '$direccion', departamento = '$departamento', provincia = '$provincia', distrito = '$distrito' WHERE id = $id";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Registro de UPS actualizado exitosamente</p>";
                } else {
                    echo "<p>Error al actualizar el registro de UPS: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Datos del formulario no recibidos correctamente</p>";
            }
            break;

        case 'delete':
            // Verificar si se ha recibido el ID del registro a desactivar
            if(isset($_POST['id'])) {
                $id = $_POST['id'];

                // Consulta SQL para desactivar un registro en la tabla 'ups'
                $sql = "UPDATE ups SET activo = 0 WHERE id = $id";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Registro de UPS desactivado exitosamente</p>";
                } else {
                    echo "<p>Error al desactivar el registro de UPS: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>ID del registro no proporcionado</p>";
            }
            break;
    }
}

// Cerrar la conexión
$conn->close();
?>