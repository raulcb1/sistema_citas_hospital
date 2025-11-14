<?php
// Incluir archivo de configuración
include '../../config.php';

// Variable para almacenar el mensaje de éxito o error
$mensaje = '';

// Verificar si se ha recibido la opción de operación por POST
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Ejecutar la acción correspondiente
    switch ($action) {
        case 'create':
            // Verificar si se han recibido los datos necesarios
            if (isset($_POST['paciente_id']) && isset($_POST['citas']) && isset($_POST['ups_id'])) {
                $paciente_id = $_POST['paciente_id'];
                $citas = $_POST['citas'];
                $ups_id = $_POST['ups_id'];

                // Variable para rastrear si se ha producido un error
                $error_occurred = false;
                $errores = [];

                // Iterar sobre las citas recibidas
                foreach ($citas as $cita) {
                    // Verificar que las claves 'servicio_id' y 'fecha_cita' existen en cada cita
                    if (isset($cita['servicio_id']) && isset($cita['fecha_cita'])) {
                        $servicio_id = $cita['servicio_id'];
                        $fecha_cita = $cita['fecha_cita'];

                        // Preparar la consulta SQL para insertar la cita en la tabla asignacion_citas
                        $sql = "INSERT INTO asignacion_citas (paciente_id, ups_id, servicio_id, fecha_cita) VALUES ('$paciente_id', '$ups_id', '$servicio_id', '$fecha_cita')";

                        // Ejecutar la consulta y verificar si fue exitosa
                        if ($conn->query($sql) !== TRUE) {
                            $error_occurred = true;
                            $errores[] = "Error al crear la cita para el servicio ID $servicio_id en la fecha $fecha_cita: " . $conn->error;
                        }
                    } else {
                        $error_occurred = true;
                        $errores[] = "Datos incompletos para la cita: " . json_encode($cita);
                    }
                }

                // Verificar si se produjo algún error
                if ($error_occurred) {
                    $mensaje = "Se produjeron errores al crear las citas:<br>";
                    foreach ($errores as $error) {
                        $mensaje .= $error . "<br>";
                    }
                } else {
                    $mensaje = "Citas creadas con éxito";
                }
            } else {
                $mensaje = "No se han recibido los datos del formulario";
            }
            break;
        // Agregar casos para otras operaciones CRUD si es necesario
        default:
            $mensaje = "Opción de operación no válida";
    }
} else {
    $mensaje = "No se ha recibido la opción de operación por POST";
}

// Cerrar la conexión
$conn->close();

// Establecer $pag en la página a la que deseas redirigir
$pag = "paginas/cita/crear_cita.php";

// Enviar el mensaje como parámetro POST
$_SESSION['mensaje'] = $mensaje;

// También enviar $pag como parámetro POST
//$_SESSION['pag'] = $pag;
//echo '<pre>';
//print_r($_SESSION);
//echo '</pre>';

// Redirigir a la página anterior con el mensaje como parámetro POST
header("Location: ../../");

exit();
?>