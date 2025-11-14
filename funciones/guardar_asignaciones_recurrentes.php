<?php
header('Content-Type: application/json');
include '../config.php';

if ($_SESSION['rol'] != 'admin') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado.']);
    exit();
}
/*
$medico_id = $_POST['medico_id'];
$servicio_id = $_POST['servicio_id'];
$turno = $_POST['turno'];
$fechas = json_decode($_POST['fechas']);
*/

// Validar datos de entrada
$data = json_decode(file_get_contents('php://input'), true);
$medico_id = $data['medico_id'] ?? null;
$servicio_id = $data['servicio_id'] ?? null;
$turno = $data['turno'] ?? null;
$fechas = $data['fechas'] ?? [];
$sobreescribir = $data['sobreescribir'] ?? false;

if (!$medico_id || !$servicio_id || !$turno || empty($fechas)) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit();
}


$conn->begin_transaction();
try {
    $conflictos = [];

    foreach ($fechas as $fecha) {
        // Verificar si ya existe la asignación
        $stmt = $conn->prepare("SELECT id FROM asignacion_consultorios 
                                WHERE medico_id = ? AND servicio_id = ? 
                                AND fecha = ? AND turno = ?");
        $stmt->bind_param("iiss", $medico_id, $servicio_id, $fecha, $turno);
        $stmt->execute();
        $result = $stmt->get_result();
if ($result->num_rows > 0) {
            $conflictos[] = $fecha;
            if (!$sobreescribir) continue; // Saltar si no se desea sobreescribir
        }

        // Eliminar asignación existente si se decide sobreescribir
        if ($sobreescribir) {
            $stmt = $conn->prepare("DELETE FROM asignacion_consultorios 
                                   WHERE medico_id = ? AND servicio_id = ? 
                                   AND fecha = ? AND turno = ?");
            $stmt->bind_param("iiss", $medico_id, $servicio_id, $fecha, $turno);
            $stmt->execute();
        }

        // Insertar nueva asignación
        $stmt = $conn->prepare("INSERT INTO asignacion_consultorios 
                               (medico_id, servicio_id, fecha, turno) 
                               VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $medico_id, $servicio_id, $fecha, $turno);
        $stmt->execute();
    }

    $conn->commit();
    
    if (!empty($conflictos)) {
        echo json_encode([
            'success' => true,
            'warning' => 'Algunas fechas tenían conflictos',
            'conflictos' => $conflictos
        ]);
    } else {
        echo json_encode(['success' => true]);
    }
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>