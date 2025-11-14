<?php
header('Content-Type: application/json');
include '../config.php';

$servicio_id = $_GET['servicio_id'];
$start = date('Y-m-d');
$end = date('Y-m-d', strtotime('+1 month'));

// Obtener capacidad del servicio
$capacidad = $conn->query("
    SELECT capacidad 
    FROM servicio_ups 
    WHERE servicio_id = $servicio_id AND ups_id = 2
")->fetch_row()[0];

// Obtener fechas ocupadas
$ocupadas = $conn->query("
    SELECT fecha_cita, COUNT(*) AS total 
    FROM asignacion_citas 
    WHERE servicio_id = $servicio_id 
    AND fecha_cita BETWEEN '$start' AND '$end'
    GROUP BY fecha_cita
");

// Construir eventos para FullCalendar
$eventos = [];
while ($fecha = $ocupadas->fetch_assoc()) {
    if ($fecha['total'] < $capacidad) {
        $eventos[] = [
            'start' => $fecha['fecha_cita'],
            'color' => '#28a745',
            'display' => 'background'
        ];
    } else {
        $eventos[] = [
            'start' => $fecha['fecha_cita'],
            'color' => '#dc3545',
            'display' => 'background'
        ];
    }
}

echo json_encode($eventos);
?>