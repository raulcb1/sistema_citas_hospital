<?php
header('Content-Type: application/json');
include '../config.php';

// Parámetros esperados
$servicio_id = isset($_GET['servicio_id']) ? intval($_GET['servicio_id']) : 0;
$anio = isset($_GET['anio']) ? intval($_GET['anio']) : 0;
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : 0;

// Validación
if (!$servicio_id || !$anio || !$mes) {
    echo json_encode([
        'success' => false,
        'error' => 'Faltan parámetros: servicio_id, anio, mes'
    ]);
    exit;
}

// Calcular fechas del mes
$inicio = "$anio-$mes-01";
$fin = date("Y-m-t", strtotime($inicio)); // Último día del mes

// Consulta a asignacion_consultorios
$sql = "SELECT fecha, turno
        FROM asignacion_consultorios
        WHERE servicio_id = ?
        AND fecha BETWEEN ? AND ?
        ORDER BY fecha ASC";

// Consulta a asignacion_consultorios - incluye datos del médico
$sql = "SELECT ac.fecha, ac.turno, ac.medico_id, u.nombre, u.apellido_p, u.apellido_m, s.nombre as servicio_nombre
            FROM asignacion_consultorios ac
            INNER JOIN medicos m ON ac.medico_id = m.id
            INNER JOIN usuarios u ON m.usuario_id = u.id
            INNER JOIN servicio_ups su ON su.servicio_id = ac.servicio_id
            INNER JOIN servicios s ON s.id = su.servicio_id
            WHERE ac.servicio_id = ?
            AND ac.fecha BETWEEN ? AND ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $servicio_id, $inicio, $fin);
$stmt->execute();
$result = $stmt->get_result();

// Crear eventos para el calendario
$eventos = [];

while ($row = $result->fetch_assoc()) {
    $fecha = $row['fecha'];
    $turno = $row['turno'];
    $nombreCompleto = trim("{$row['nombre']} {$row['apellido_p']} {$row['apellido_m']}");
    $servicio_nombre= $row['servicio_nombre'];

    // Rango horario según el turno
    if ($turno === 'mañana') {
        $start = "{$fecha}T07:30:00";
        $end   = "{$fecha}T13:30:00";
        $color = '#6C9CDC'; // verde claro
    } elseif ($turno === 'tarde') {
        $start = "{$fecha}T14:00:00";
        $end   = "{$fecha}T19:30:00";
        $color = '#6C9CDC'; // azul claro
    } else {
        continue; // ignorar si no hay turno válido
    }


    $eventos[] = [
        'start' => $start,
        'end' => $end,
        'display' => 'background',
        'color' => $color, // Verde claro
        'extendedProps' => [
            'tipo' => 'programado',
            'medico' => $nombreCompleto, // <- nombre del médico
            'servicio' => $servicio_nombre // <- nombre del servicio
        ]
    ];
}

echo json_encode([
    'success' => true,
    'data' => $eventos
]);