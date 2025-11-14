<?php
/**
 * registrar_paciente.php
 * 
 * Este script recibe los datos de un nuevo paciente vía POST (JSON),
 * los valida y registra en la base de datos.
 * 
 * Retorna:
 * - success: true + id del paciente si se registró correctamente.
 * - success: false + mensaje de error si ocurre un problema.
 */

header('Content-Type: application/json');
include '../config.php';

// Asegurar método POST y contenido tipo JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

// Verificar si se recibió JSON válido
if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Entrada inválida (JSON no válido)']);
    exit;
}

// Validar campos obligatorios
$dni         = trim($input['dni'] ?? '');
$nombre      = trim($input['nombre'] ?? '');
$apellido_p  = trim($input['apellido_p'] ?? '');
$apellido_m  = trim($input['apellido_m'] ?? '');
$fecha_nac   = trim($input['fecha_nac'] ?? '');
$telefono    = trim($input['telefono'] ?? null); // opcional

if (empty($dni) || empty($nombre) || empty($apellido_p) || empty($apellido_m)) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios']);
    exit;
}

// Validar que el DNI no esté ya registrado
$stmt_check = $conn->prepare("SELECT id FROM pacientes WHERE dni = ?");
$stmt_check->bind_param('s', $dni);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'El DNI ya está registrado']);
    exit;
}
$stmt_check->close();

// Insertar paciente
$sql = "INSERT INTO pacientes (dni, nombre, apellido_p, apellido_m, fecha_nac, telefono, activo)
        VALUES (?, ?, ?, ?, ?, ?, 1)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssss', $dni, $nombre, $apellido_p, $apellido_m, $fecha_nac, $telefono);

if ($stmt->execute()) {
    $nuevo_id = $stmt->insert_id;
    echo json_encode([
        'success' => true,
        'paciente_id' => $nuevo_id,
        'message' => 'Paciente registrado correctamente'
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al registrar paciente']);
}

$stmt->close();
$conn->close();
// Fin del script
?>