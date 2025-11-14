<?php
header('Content-Type: application/json');
include '../config.php';

// Obtener datos enviados por POST (formulario)
$dni         = isset($_POST['dni']) ? trim($_POST['dni']) : '';
$nombre      = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido_p  = isset($_POST['apellido_p']) ? trim($_POST['apellido_p']) : '';
$apellido_m  = isset($_POST['apellido_m']) ? trim($_POST['apellido_m']) : '';
$fecha_nac   = isset($_POST['fecha_nac']) ? $_POST['fecha_nac'] : null;
$telefono    = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;

// Validaciones básicas
if (empty($dni) || empty($nombre) || empty($apellido_p)) {
  echo json_encode([ 'success' => false, 'error' => 'Faltan datos obligatorios' ]);
  exit();
}

// Verificar si el DNI ya existe
$sqlCheck = "SELECT id FROM pacientes WHERE dni = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  echo json_encode([ 'success' => false, 'error' => 'El DNI ya está registrado' ]);
  exit();
}

// Insertar paciente
$sqlInsert = "INSERT INTO pacientes (dni, nombre, apellido_p, apellido_m, fecha_nac, telefono)
              VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sqlInsert);
$stmt->bind_param("ssssss", $dni, $nombre, $apellido_p, $apellido_m, $fecha_nac, $telefono);

if ($stmt->execute()) {
  $nuevo_id = $stmt->insert_id;
  echo json_encode([
    'success' => true,
    'data' => [
      'id' => $nuevo_id,
      'nombre_completo' => "$nombre $apellido_p $apellido_m"
    ]
  ]);
} else {
    $mensaje = 'Error al guardar paciente. ';
    if (DEBUG_MODE) {
            $mensaje .= ' SQL: ' . $conn->error;
        }

    echo json_encode([ 'success' => false, 'error' => $mensaje ]);
    exit();
}

$stmt->close();
$conn->close();