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
            if(isset($_POST['dni']) && isset($_POST['nombre']) && isset($_POST['apellido_m']) && isset($_POST['apellido_p']) && isset($_POST['fecha_nac']) && isset($_POST['telefono'])) {
                // Obtener los datos del formulario
                $dni = $_POST['dni'];
                $nombre = $_POST['nombre'];
                $apellido_p = $_POST['apellido_p'];
                $apellido_m = $_POST['apellido_m'];
                $fecha_nac = $_POST['fecha_nac'];
                $telefono = $_POST['telefono'];

                //&& isset($_POST['ups_id']) && isset($_POST['seguro_id']) && isset($_POST['historia_id'])

                if(isset($_POST['ups_id'])) {
                    $ups_id = $_POST['ups_id'];}
                    else $ups_id='';
                if(isset($_POST['seguro_id'])) {
                    $seguro_id = $_POST['seguro_id'];}
                    else $seguro_id='';
                if(isset($_POST['historia_id'])) {
                    $historia_id = $_POST['historia_id'];}
                    else $historia_id='';


                // Preparar la consulta SQL para insertar un nuevo registro
                $sql = "INSERT INTO pacientes (dni, nombre, apellido_m, apellido_p, fecha_nac, telefono, ups_id, seguro_id, historia_id) VALUES ('$dni', '$nombre', '$apellido_m', '$apellido_p', '$fecha_nac', '$telefono', '$ups_id', '$seguro_id', '$historia_id')";
                //echo $sql;

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Nuevo paciente creado con éxito";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "No se han recibido los datos del formulario";
            }
            break;
        case 'edit':
            // Verificar si se han recibido los datos del formulario
            if(isset($_POST['id']) && isset($_POST['dni']) && isset($_POST['nombre']) && isset($_POST['apellido_m']) && isset($_POST['apellido_p']) && isset($_POST['fecha_nac']) && isset($_POST['telefono']) && isset($_POST['ups_id']) && isset($_POST['seguro_id']) && isset($_POST['historia_id'])) {
                // Obtener los datos del formulario
                $id = $_POST['id'];
                $dni = $_POST['dni'];
                $nombre = $_POST['nombre'];
                $apellido_m = $_POST['apellido_m'];
                $apellido_p = $_POST['apellido_p'];
                $fecha_nac = $_POST['fecha_nac'];
                $telefono = $_POST['telefono'];
                $ups_id = $_POST['ups_id'];
                $seguro_id = $_POST['seguro_id'];
                $historia_id = $_POST['historia_id'];

                // Preparar la consulta SQL para actualizar el registro
                $sql = "UPDATE pacientes SET dni='$dni', nombre='$nombre', apellido_m='$apellido_m', apellido_p='$apellido_p', fecha_nac='$fecha_nac', telefono='$telefono', ups_id='$ups_id', seguro_id='$seguro_id', historia_id='$historia_id' WHERE id=$id";

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Paciente actualizado con éxito";
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
                $sql = "UPDATE pacientes SET activo=0 WHERE id=$id";

                // Ejecutar la consulta y verificar si fue exitosa
                if ($conn->query($sql) === TRUE) {
                    echo "Paciente desactivado con éxito";
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