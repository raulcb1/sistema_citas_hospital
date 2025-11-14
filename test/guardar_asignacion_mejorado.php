<?php
header('Content-Type: application/json');
include '../config.php';

// Verificar autenticación y autorización
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

if ($_SESSION['rol'] != 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit();
}

// Validar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit();
}

// Capturar y validar datos
$medico_id = isset($_POST['medico_id']) ? intval($_POST['medico_id']) : 0;
$servicio_id = isset($_POST['servicio_id']) ? intval($_POST['servicio_id']) : 0;
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$turno = isset($_POST['turno']) ? $_POST['turno'] : '';

// Validar campos requeridos
if (!$medico_id || !$servicio_id || !$fecha || !$turno) {
    echo json_encode([
        'success' => false, 
        'error' => 'Todos los campos son obligatorios',
        'datos_recibidos' => [
            'medico_id' => $medico_id,
            'servicio_id' => $servicio_id,
            'fecha' => $fecha,
            'turno' => $turno
        ]
    ]);
    exit();
}

// Validar formato de fecha
if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
    echo json_encode(['success' => false, 'error' => 'Formato de fecha inválido']);
    exit();
}

// Validar turno
if (!in_array($turno, ['mañana', 'tarde'])) {
    echo json_encode(['success' => false, 'error' => 'Turno inválido']);
    exit();
}

try {
    // Verificar si el médico existe
    $stmt = $conn->prepare("SELECT id FROM medicos WHERE id = ?");
    $stmt->bind_param("i", $medico_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Médico no encontrado']);
        exit();
    }
    
    // Verificar si el servicio existe
    $stmt = $conn->prepare("SELECT id FROM servicios WHERE id = ?");
    $stmt->bind_param("i", $servicio_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Servicio no encontrado']);
        exit();
    }
    
    // Verificar disponibilidad (evitar duplicados)
    $stmt = $conn->prepare("SELECT id FROM asignacion_consultorios 
                           WHERE medico_id = ? AND fecha = ? AND turno = ?");
    $stmt->bind_param("iss", $medico_id, $fecha, $turno);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false, 
            'error' => 'El médico ya está asignado en esta fecha y turno'
        ]);
        exit();
    }
    
    // Insertar nueva asignación
    $stmt = $conn->prepare("INSERT INTO asignacion_consultorios 
                           (medico_id, servicio_id, fecha, turno) 
                           VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $medico_id, $servicio_id, $fecha, $turno);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Asignación guardada exitosamente',
            'id' => $conn->insert_id
        ]);
    } else {
        throw new Exception('Error al insertar la asignación: ' . $stmt->error);
    }
    
} catch (Exception $e) {
    error_log("Error en guardar_asignacion.php: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'error' => 'Error interno del servidor: ' . $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>