<?php
header('Content-Type: application/json');
include '../../config.php';

// Validar permisos
if ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'recepcion') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit();
}

// Obtener datos
$data = json_decode(file_get_contents('php://input'), true);
$paciente_id = $data['paciente_id'];
$servicios = $data['servicios'];

try {
    $conn->begin_transaction();

    // 1. Crear cita principal
    $stmt = $conn->prepare("INSERT INTO cita (paciente_id, fecha_cita, estado) VALUES (?, NOW(), 'activa')");
    $stmt->bind_param("i", $paciente_id);
    $stmt->execute();
    $cita_id = $conn->insert_id;

    // 2. Insertar asignaciones
    foreach ($servicios as $servicio) {
        $stmt = $conn->prepare("
            INSERT INTO asignacion_citas 
            (cita_id, servicio_id, fecha_cita, estado) 
            VALUES (?, ?, ?, 'pendiente')
        ");
        $stmt->bind_param("iis", $cita_id, $servicio['servicio_id'], $servicio['fecha']);
        $stmt->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>