<?php
session_start();
include '../../config.php';

// Recibir parámetros
$fecha_inicio = $_GET['fecha_inicio'] ?? date("Y-m-01"); // Primer día del mes si no se envía fecha
$fecha_fin = $_GET['fecha_fin'] ?? date("Y-m-t"); // Último día del mes
$servicio_id = $_GET['servicio_id'] ?? ''; // Servicio opcional

// Construcción de la consulta
$sql = "SELECT s.nombre AS servicio, COUNT(ac.id) AS total_citas
        FROM asignacion_consultorios ac
        INNER JOIN servicios s ON ac.servicio_id = s.id
        WHERE ac.fecha BETWEEN ? AND ?";

if (!empty($servicio_id)) {
    $sql .= " AND ac.servicio_id = ?";
}

$sql .= " GROUP BY s.id";

$stmt = $conn->prepare($sql);
if (!empty($servicio_id)) {
    $stmt->bind_param("ssi", $fecha_inicio, $fecha_fin, $servicio_id);
} else {
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Servicios</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Reporte de Servicios del Hospital</h2>
    <table>
        <tr>
            <th>Servicio</th>
            <th>Total de Citas</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['servicio']); ?></td>
            <td><?php echo $row['total_citas']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
