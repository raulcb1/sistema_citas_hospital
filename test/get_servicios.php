<?php
header('Content-Type: application/json');
include '../config.php';

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$sql = "SELECT id, nombre, descripcion
        FROM servicios
        WHERE estado = 'activo'
        ORDER BY nombre";

$result = $conn->query($sql);

$servicios = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'] ?? ''
        ];
    }
}

echo json_encode($servicios);
$conn->close();
?>