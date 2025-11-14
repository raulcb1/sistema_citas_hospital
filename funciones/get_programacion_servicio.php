<?php
header('Content-Type: application/json');
//conectar a la base de datos
include '../config.php';

// Obtener el ID del servicio
$servicio_id = isset($_GET['servicio_id']) ? intval($_GET['servicio_id']) : null;
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;






// Verificar si el servicio_id está definido
if ($servicio_id === null || $servicio_id === 0) {
    echo json_encode([
        'success' => false,
        'error' => 'ID del servicio no válido.',
        'data' => []
    ]);
    exit();
}

// Preparamos las variables de fecha para insertarlas en la consulta
$where = "WHERE ac.servicio_id = ?";
$params = [$servicio_id];
$types = "i";

if ($fecha_inicio && $fecha_fin) {
    $where .= " AND ac.fecha BETWEEN ? AND ?";
    $params[] = $fecha_inicio;
    $params[] = $fecha_fin;
    $types .= "ss";
} elseif ($fecha_inicio) {
    $where .= " AND ac.fecha >= ?";
    $params[] = $fecha_inicio;
    $types .= "s";
} elseif ($fecha_fin) {
    $where .= " AND ac.fecha <= ?";
    $params[] = $fecha_fin;
    $types .= "s";
}



// Consulta para obtener la programación del servicio específico
$sql = "SELECT 
            ac.fecha,
            ac.turno,
            u.nombre AS medico,
            u.apellido_p,
            u.apellido_m,
            c.nombre AS consultorio,
            s.nombre AS servicio
        FROM asignacion_consultorios ac
        INNER JOIN servicios s ON ac.servicio_id = s.id
        INNER JOIN medicos m ON ac.medico_id = m.id
        INNER JOIN usuarios u ON m.usuario_id = u.id
        LEFT JOIN consultorios c ON ac.consultorio_id = c.id
        $where
        ORDER BY ac.fecha ASC, u.apellido_p ASC, ac.turno ASC";

$stmt = $conn->prepare($sql);

// Verificar si la consulta se preparó correctamente
if ($stmt === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Error al preparar la consulta SQL: ' . $conn->error,
        'data' => []
    ]);
    exit();
}

// Enlazar parámetros y ejecutar la consulta
// Enlazar parámetros dinámicamente
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si la consulta se ejecutó correctamente
if ($result === false) {
    echo json_encode([
        'success' => false,
        'error' => 'Error al ejecutar la consulta SQL: ' . $stmt->error,
        'data' => []
    ]);
    exit();
}

// Construir el array de datos
$programacion = [];
while ($row = $result->fetch_assoc()) {
    // Formatear el nombre completo del médico
    $nombreCompleto = $row['medico'];
    if (!empty($row['apellido_p'])) {
        $nombreCompleto .= ' ' . $row['apellido_p'];
    }
    if (!empty($row['apellido_m'])) {
        $nombreCompleto .= ' ' . $row['apellido_m'];
    }
    
    $programacion[] = [
        'fecha' => $row['fecha'],
        'turno' => $row['turno'],
        'medico' => $nombreCompleto,
        'consultorio' => $row['consultorio'],
        'servicio' => $row['servicio']
    ];
}

// Devolver el JSON con los datos
echo json_encode($programacion);

// Cerrar la conexión
$stmt->close();
$conn->close();
?>