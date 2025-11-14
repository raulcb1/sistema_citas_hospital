<?php
header('Content-Type: application/json');
include '../config.php';

$sql = "SELECT su.id, CONCAT(s.nombre, ' - ', u.nombre) AS nombre
        FROM servicio_ups su
        INNER JOIN servicios s ON su.servicio_id = s.id
        INNER JOIN ups u ON su.ups_id = u.id
        WHERE s.activo = 1 AND u.activo = 1 AND su.ups_id = " . UPS_ACTIVA_ID;

$result = $conn->query($sql);
$servicios = [];

while ($row = $result->fetch_assoc()) {
    $servicios[] = [
        'id' => $row['id'],
        'nombre' => $row['nombre']
    ];
}

echo json_encode($servicios);