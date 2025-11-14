<?php
/**
 * eliminar_servicio.php
 * ----------------------------------------
 * Este backend desactiva un servicio (asignación de cita)
 * en la tabla `asignacion_citas`, marcando:
 *  - estado = 'cancelada'
 *  - activo = 0
 *  - motivo_desactiva
 *  - fecha_desactiva
 *  - usuario_desactiva_id
 * 
 * Entrada esperada: JSON con:
 *  - id: ID de la asignación
 *  - motivo: texto del motivo de cancelación
 */

session_start();
include '../config.php';
header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Sesión no iniciada']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Leer datos JSON del fetch
$input = json_decode(file_get_contents('php://input'), true);
$asignacion_id = intval($input['id'] ?? 0);
$motivo = trim($input['motivo'] ?? '');

if (!$asignacion_id || $motivo === '') {
    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios.']);
    exit();
}

// Validar si la asignación existe y está activa
$sqlCheck = "SELECT * FROM asignacion_citas WHERE id = ? AND activo = 1";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param('i', $asignacion_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'La asignación no existe o ya fue desactivada.']);
    exit();
}
$asignacion = $res->fetch_assoc();
$stmt->close();

// Registrar la desactivación
$sqlUpdate = "UPDATE asignacion_citas
              SET estado = 'cancelada',
                  activo = 0,
                  motivo_desactiva = ?,
                  fecha_desactiva = NOW(),
                  usuario_desactiva_id = ?
              WHERE id = ?";
$stmt = $conn->prepare($sqlUpdate);
$stmt->bind_param('sii', $motivo, $usuario_id, $asignacion_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Servicio desactivado correctamente.']);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el registro.']);
}

$stmt->close();
$conn->close();