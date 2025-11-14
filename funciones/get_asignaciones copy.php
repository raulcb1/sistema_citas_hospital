<?php
header('Content-Type: application/json');
include '../config.php';

// Capturar las fechas de inicio y fin
//$start = $_GET['start'];
//$end = $_GET['end'];

echo "<pre>";
print_r($_GET);
echo "</pre>";

// Capturar las fechas de inicio y fin
$start = isset($_GET['start']) ? $_GET['start'] : null;
$end = isset($_GET['end']) ? $_GET['end'] : null;

// Verificar si las variables start y end están definidas
if ($start === null || $end === null) {
    echo json_encode([
        'success' => false,
        'error' => 'Faltan parámetros start o end en la solicitud.'
    ]);
    exit();
}

$sql="SELECT ac.fecha, ac.turno, s.nombre AS consultorio, u.nombre AS medico
        FROM asignacion_consultorios ac
        INNER JOIN servicios s ON ac.servicio_id = s.id
        INNER JOIN medicos m ON ac.medico_id = m.id
        INNER JOIN usuarios u ON m.usuario_id = u.id
        WHERE ac.fecha BETWEEN ? AND ?";

/*$sql = "SELECT 
            ac.id,
            ac.fecha AS start,
            CONCAT(s.nombre, ' (', ac.turno, ') - ', u.nombre) AS title,
            s.nombre AS consultorio,
            u.nombre AS medico,
            ac.turno,
            CASE 
                WHEN ac.turno = 'mañana' THEN '#4CAF50' 
                ELSE '#2196F3' 
            END AS backgroundColor,
            '#FF5722' AS borderColor  -- Color para días con conflictos
        FROM asignacion_consultorios ac
        INNER JOIN servicios s ON ac.consultorio_id = s.id
        INNER JOIN medicos m ON ac.medico_id = m.id
        INNER JOIN usuarios u ON m.usuario_id = u.id";
        */

$stmt = $conn->prepare($sql);

// Verificar si la consulta se preparó correctamente
if ($stmt === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Error al preparar la consulta SQL: ' . $conn->error
    ]);
    exit();
}

// Enlazar los parámetros y ejecutar la consulta
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si la consulta se ejecutó correctamente
if ($result === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Error al ejecutar la consulta SQL: ' . $stmt->error
    ]);
    exit();
}

// Construir el array de eventos
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => $row['consultorio'],
        'start' => $row['fecha'],
        'allDay' => true,
        'extendedProps' => [
            'consultorio' => $row['consultorio'],
            'medico' => $row['medico'],
            'turno' => $row['turno']
        ]
    ];
}

// Devolver el JSON de eventos
echo json_encode($events);

// Cerrar la conexión
$stmt->close();
$conn->close();
?>