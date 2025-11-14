<?php
header('Content-Type: application/json');
include '../config.php';

$fecha = $_GET['fecha'];

$sql = "SELECT s.nombre AS servicio, u.nombre AS medico, ac.turno
        FROM asignacion_consultorios ac
        INNER JOIN servicios s ON ac.servicio_id = s.id
        INNER JOIN medicos m ON ac.medico_id = m.id
        INNER JOIN usuarios u ON m.usuario_id = u.id
        WHERE ac.fecha = ?
        ORDER BY ac.turno, s.nombre";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fecha);
$stmt->execute();
$result = $stmt->get_result();

$eventos = [];
while ($row = $result->fetch_assoc()) {
    $eventos[] = $row;
}

echo json_encode($eventos);

$conn->close();
?>