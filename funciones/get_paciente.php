<?php
/**
 * get_paciente.php
 *
 * Funcionalidad:
 * Devuelve la información de un paciente (nombre completo, DNI, teléfono, edad, etc.)
 * según un parámetro GET: puede ser por `dni` o por `id`.
 *
 * Uso desde JS o HTML:
 *   - Por DNI: fetch('get_paciente.php?dni=12345678')
 *   - Por ID : fetch('get_paciente.php?id=15')
 */

header('Content-Type: application/json');
include '../config.php';

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Error en la conexión: ' . $conn->connect_error]);
    exit();
}

// Obtener parámetros GET
$dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';
$id  = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validar entrada mínima
if (empty($dni) && $id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Debe proporcionar DNI o ID']);
    exit();
}

// Construir consulta SQL según el parámetro disponible
if (!empty($dni)) {
    $sql = "SELECT id, dni, nombre, apellido_p, apellido_m, fecha_nac, telefono
            FROM pacientes
            WHERE dni = ?
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $dni);
} else {
    $sql = "SELECT id, dni, nombre, apellido_p, apellido_m, fecha_nac, telefono
            FROM pacientes
            WHERE id = ?
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Paciente no encontrado']);
    exit();
}

$row = $result->fetch_assoc();

// Calcular edad si se proporcionó fecha_nac
$edad = null;
if (!empty($row['fecha_nac'])) {
    $fecha_nac = new DateTime($row['fecha_nac']);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha_nac)->y;
}

// Armar respuesta JSON
$response = [
    'success' => true,
    'data' => [
        'id'              => $row['id'],
        'dni'             => $row['dni'],
        'nombre'          => $row['nombre'],
        'apellido_p'      => $row['apellido_p'],
        'apellido_m'      => $row['apellido_m'],
        'nombre_completo' => trim($row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']),
        'telefono'        => $row['telefono'] ?? '',
        'edad'            => $edad
    ]
];

echo json_encode($response);