<?php
header('Content-Type: application/json');
include '../config.php';

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$sql = "SELECT m.id, u.nombre, u.apellidos, s.nombre as especialidad
        FROM medicos m
        INNER JOIN usuarios u ON m.usuario_id = u.id
        LEFT JOIN servicios s ON m.especialidad_id = s.id
        WHERE u.estado = 'activo'
        ORDER BY u.nombre, u.apellidos";

$result = $conn->query($sql);

$medicos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nombre_completo = trim($row['nombre'] . ' ' . ($row['apellidos'] ?? ''));
        if ($row['especialidad']) {
            $nombre_completo .= ' - ' . $row['especialidad'];
        }
        
        $medicos[] = [
            'id' => $row['id'],
            'nombre' => $nombre_completo
        ];
    }
}

echo json_encode($medicos);
$conn->close();
?>