<?php
/*
header('Content-Type: application/json');
include '../config.php';

$servicio_id = $_POST['servicio_id'];
$fecha = $_POST['fecha'];
$turno = $_POST['turno'];

$sql = "SELECT u.nombre AS medico, ac.turno 
        FROM asignacion_consultorios ac
        INNER JOIN medicos m ON ac.medico_id = m.id
        INNER JOIN usuarios u ON m.usuario_id = u.id
        WHERE ac.servicio_id = ? AND ac.fecha = ? AND ac.turno = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $servicio_id, $fecha, $turno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['existe' => true, 'medico' => $row['medico'], 'turno' => $row['turno']]);
} else {
    echo json_encode(['existe' => false]);
}

$conn->close();

*/

header('Content-Type: application/json');
include '../config.php';

$servicio_id = $_POST['servicio_id'];
$fecha = $_POST['fecha'];
$turno = $_POST['turno'];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $required = ['servicio_id', 'fecha', 'turno'];
    foreach ($required as $field) {
        if (!isset($data[$field])) throw new Exception("Campo $field requerido");
    }

    $sql = "SELECT u.nombre AS medico, ac.turno 
        FROM asignacion_consultorios ac
        INNER JOIN medicos m ON ac.medico_id = m.id
        INNER JOIN usuarios u ON m.usuario_id = u.id
        WHERE ac.servicio_id = ? AND ac.fecha = ? AND ac.turno = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $data['servicio_id'], $data['fecha'], $data['turno']);
    $stmt->execute();
    
    echo json_encode($stmt->get_result()->num_rows > 0 ? ['conflicto' => true] : ['conflicto' => false]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>