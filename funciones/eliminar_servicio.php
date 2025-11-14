<?php
/**
 * eliminar_servicio.php
 * 
 * Funcionalidad:
 * Desactiva un servicio de una cita médica y registra motivo, fecha y usuario.
 * Si es el último servicio pendiente, se actualiza la cita maestra según si hubo atención o no.
 */

header('Content-Type: application/json');
include '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado.']);
    exit();
}

// Leer cuerpo JSON
$input = json_decode(file_get_contents('php://input'), true);

$id_asignacion    = intval($input['id_asignacion'] ?? 0);
$motivo           = trim($input['motivo'] ?? '');
$cita_id          = intval($input['cita_id'] ?? 0);
$es_ultima        = boolval($input['es_ultima'] ?? false);
$tiene_atendidos  = boolval($input['tiene_atendidos'] ?? false);
$usuario_id       = $_SESSION['usuario_id'];
$fecha_hoy        = date('Y-m-d H:i:s');

// Validaciones básicas
if (!$id_asignacion || !$motivo || !$cita_id) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos para procesar.']);
    exit();
}

// Iniciar transacción
$conn->begin_transaction();

try {
    // Paso 1: Desactivar el servicio (estado_cita_id = 4 = cancelado)
    $sql1 = "UPDATE asignacion_citas
             SET activo = 0,
                 estado_cita_id = 4,  -- cancelado
                 motivo_desactiva = ?,
                 fecha_desactiva = ?,
                 usuario_desactiva_id = ?
             WHERE id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ssii", $motivo, $fecha_hoy, $usuario_id, $id_asignacion);
    $stmt1->execute();

    // Paso 2: Si es el último servicio pendiente
    if ($es_ultima) {
        if ($tiene_atendidos) {
            // Finalizar la cita (estado_cita_id = 6)
            $sql2 = "UPDATE cita
                     SET estado_cita_id = 6,
                         motivo_desactiva = ?,
                         fecha_desactiva = ?,
                         usuario_desactiva_id = ?
                     WHERE id = ?";
        } else {
            // Cancelar completamente la cita (estado_cita_id = 4)
            $sql2 = "UPDATE cita
                     SET estado_cita_id = 4,
                         motivo_desactiva = ?,
                         fecha_desactiva = ?,
                         usuario_desactiva_id = ?
                     WHERE id = ?";
        }

        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("ssii", $motivo, $fecha_hoy, $usuario_id, $cita_id);
        $stmt2->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => 'Error en base de datos: ' . $e->getMessage()]);
}

$conn->close();