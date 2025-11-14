<?php
ob_start(); // Buffer de salida
header('Content-Type: application/json');
include '../config.php';

// Verificar sesión y permisos
session_start();
if ($_SESSION['rol'] != 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit();
}

// Leer datos del cuerpo de la solicitud en formato JSON
$input = json_decode(file_get_contents('php://input'), true);

// Capturar entrada JSON
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die(json_encode(['success' => false, 'error' => 'JSON inválido al capturar entrada: ' . json_last_error_msg()]));
}

// Validar datos esenciales
$required = ['medico_id', 'servicio_id', 'turno', 'fechas'];
foreach ($required as $field) {
    if (!isset($input[$field])){
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "Campo requerido: $field"]);
        exit();
    }
}

// Asignar variables
$medico_id = (int)$input['medico_id'];
$servicio_id = (int)$input['servicio_id'];
$turno = in_array($input['turno'], ['mañana', 'tarde']) ? $input['turno'] : null;
$fechas = array_unique($input['fechas']);
$sobreescribir = $input['sobreescribir'] ?? false;

// Validar valores
if (!$turno || empty($fechas)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit();
}

// Preparar respuesta
$response = ['success' => true];
$conflictos = [];

try {
    $conn->begin_transaction();

    foreach ($fechas as $fecha) {
        // Validar formato de fecha
        if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
            $response['success'] = false;
            $response['error'] = "Formato de fecha inválido: $fecha";
            throw new Exception($response['error']);
        }

        // Verificar existencia previa
        $stmt = $conn->prepare("SELECT id FROM asignacion_consultorios 
                              WHERE medico_id = ? 
                              AND servicio_id = ? 
                              AND fecha = ? 
                              AND turno = ?");
        $stmt->bind_param("iiss", $medico_id, $servicio_id, $fecha, $turno);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;

        if ($exists) {
            $conflictos[] = $fecha;
            if (!$sobreescribir) continue;
            
            // Eliminar registro existente si se desea sobreescribir
            $delete = $conn->prepare("DELETE FROM asignacion_consultorios 
                                    WHERE medico_id = ? 
                                    AND servicio_id = ? 
                                    AND fecha = ? 
                                    AND turno = ?");
            $delete->bind_param("iiss", $medico_id, $servicio_id, $fecha, $turno);
            $delete->execute();
        }

        // Insertar nueva asignación
        $insert = $conn->prepare("INSERT INTO asignacion_consultorios 
                                 (medico_id, servicio_id, fecha, turno) 
                                 VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiss", $medico_id, $servicio_id, $fecha, $turno);
        $insert->execute();
    }

    $conn->commit();

    // Agregar advertencia si hubo conflictos
    if (!empty($conflictos)) {
        $response['warning'] = 'Algunas fechas tenían programaciones existentes';
        $response['conflictos'] = $conflictos;
    }

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    $response = [
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $conn->error // Solo para ambiente de desarrollo
    ];
}

// Al final del archivo
ob_end_clean(); // Limpiar buffer antes de enviar JSON
echo json_encode($response);
exit();
?>