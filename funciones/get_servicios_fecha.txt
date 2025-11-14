<?php
header('Content-Type: application/json');
include '../config.php';

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(array("error" => "Error en la conexión: " . $conn->connect_error)));
}

// Verificar si se recibió la fecha de la cita
if (isset($_POST['fecha_cita'])) {
    // Sanitizar y obtener la fecha de la cita
    $fecha = $_POST['fecha_cita'];

    // Consultar los servicios disponibles para la fecha dada
    $sql = "SELECT s.id, s.nombre, s.turno, COUNT(ac.id) AS citas_asignadas, su.capacidad AS capacidad_total
            FROM servicios s
            LEFT JOIN asignacion_citas ac ON s.id = ac.servicio_id AND ac.fecha_cita = ?
            LEFT JOIN servicio_ups su ON s.id = su.servicio_id
            WHERE su.ups_id = 2 -- Condición para filtrar por el ID del establecimiento
            GROUP BY s.id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear un array para almacenar los servicios disponibles
    $servicios_disponibles = array();

    // Iterar sobre los resultados y guardarlos en el array
    while ($row = $result->fetch_assoc()) {
        $servicios_disponibles[] = $row;
    }

    // Devolver los servicios disponibles como JSON
    echo json_encode($servicios_disponibles);
} else {
    // Si no se recibió la fecha de la cita, devolver un error
    echo json_encode(array('error' => 'No se proporcionó la fecha de la cita.'));
}

// Cerrar la conexión
$conn->close();
?>