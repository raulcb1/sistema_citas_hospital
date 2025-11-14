<?php
header('Content-Type: application/json');
include '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idCita = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$idCita) {
    echo json_encode(['success' => false, 'error' => 'ID de cita no especificado']);
    exit;
}

// Consulta de la cita principal y el paciente
$sqlCita = "SELECT c.id, c.fecha_cita, c.motivo, c.estado, p.nombre, p.apellido_p, p.apellido_m, p.dni, p.telefono
            FROM cita c
            JOIN pacientes p ON c.paciente_id = p.id
            WHERE c.id = ?";
$stmt = $conn->prepare($sqlCita);
$stmt->bind_param("i", $idCita);

// 🧾 (opcional para DEBUG): Generar la SQL como string real con valores reemplazados
      $sqlCitadebug = sprintf(
        "SELECT c.id, c.fecha_cita, c.motivo, p.nombre, p.apellido_p, p.apellido_m, p.dni, p.telefono
            FROM cita c
            JOIN pacientes p ON c.paciente_id = p.id
            WHERE c.id = ?",
        $idCita
      );
      if (defined('DEBUG_MODE') && DEBUG_MODE) {
        $debug_messages[] = "📝 SQL INSERT cita: $sqlCitadebug";
      }
    // Fin del DEBUG


$stmt->execute();
$result = $stmt->get_result();
$cita = $result->fetch_assoc();
$stmt->close();

if (!$cita) {
    echo json_encode(['success' => false, 'error' => 'Cita no encontrada']);
    exit;
}

// Consulta de servicios asociados a la cita
$sqlServicios = "SELECT ac.id, s.nombre AS servicio, ac.fecha_cita, ac.hora, ac.estado, ac.activo
                 FROM asignacion_citas ac
                 JOIN servicios s ON ac.servicio_id = s.id
                 WHERE ac.cita_id = ?
                 ORDER BY ac.fecha_cita, ac.hora";
$stmt = $conn->prepare($sqlServicios);
$stmt->bind_param("i", $idCita);
$stmt->execute();
$result = $stmt->get_result();
$servicios = [];
while ($row = $result->fetch_assoc()) {
    $servicios[] = $row;
}
$stmt->close();

// Usuario que imprime
$usuario_id = $_SESSION['usuario_id'] ?? null;
$sqlUsuario = "SELECT nombre, apellido_p FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sqlUsuario);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resUsuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo json_encode([
    'success' => true,
    'cita' => $cita,
    'servicios' => $servicios,
    'usuario' => $resUsuario
]);
?>