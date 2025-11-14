<?php
header('Content-Type: application/json');
include '../config.php';

// Validar sesión y rol
if ($_SESSION['rol'] != 'admin') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado.']);
    exit();
}

$medico_id = $_POST['medico_id'];
$servicio_id = $_POST['servicio_id'];
$fecha = $_POST['fecha'];
$turno = $_POST['turno'];

// Validar disponibilidad
$stmt = $conn->prepare("SELECT id FROM asignacion_consultorios WHERE medico_id = ? AND fecha = ? AND turno = ?");
$stmt->bind_param("iss", $medico_id, $fecha, $turno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'El médico ya está asignado en esta fecha y turno.']);
    exit();
}

// Insertar asignación
$sql_insert = "INSERT INTO asignacion_consultorios (medico_id, servicio_id, fecha, turno) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("iiss", $medico_id, $servicio_id, $fecha, $turno);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    $error_info = [
        'sql' => $sql_insert,
        'params' => [
            'medico_id' => $medico_id,
            'servicio_id' => $servicio_id,
            'fecha' => $fecha,
            'turno' => $turno
        ],
        'error' => $stmt->error
    ];
    echo json_encode(['success' => false, 'error' => $error_info]);
}

$conn->close();
?>