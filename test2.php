<?php
// Conexión a la base de datos
include('config.php');

// Consulta principal de citas
$query = "SELECT c.id, p.apellido_p, p.apellido_m, p.nombre, p.dni, c.fecha_cita, c.motivo as motivo
          FROM cita c
          JOIN pacientes p ON c.paciente_id = p.id
          JOIN servicio_ups s ON c.servicio_ups_id = s.id
          JOIN servicios sv ON servicio_ups_id = sv.id
          ORDER BY c.fecha_cita DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado de Citas</title>
    <style>
        .citas-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .citas-table th, .citas-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        
        .citas-table th {
            background-color: #f8f9fa;
        }
        
        .expand-row {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .expand-row:hover {
            background-color: #f1f1f1;
        }
        
        .detalles-servicios {
            background-color: #f8f9fa;
        }
        
        .sub-table {
            width: 95%;
            margin: 10px auto;
            border-collapse: collapse;
        }
        
        .sub-table th {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <h2>Listado de Citas</h2>
    
    <table class="citas-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>DNI</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            <?php while($cita = $result->fetch_assoc()): ?>
            <tr class="expand-row" onclick="toggleDetalles(<?= $cita['id'] ?>)">
                <td><?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></td>
                <?php $nombre_paciente = $cita['apellido_p'] . ' ' . $cita['apellido_m'] . ', ' . $cita['nombre']; ?>
                <td><?= htmlspecialchars($nombre_paciente) ?></td>
                <td><?= $cita['dni'] ?></td>
                <td><?= htmlspecialchars($cita['motivo']) ?></td>
            </tr>
            <tr id="detalles-<?= $cita['id'] ?>" class="detalles-servicios" style="display: none;">
                <td colspan="6">
                    <?php
                    // Consulta de servicios asignados
                    $query_servicios = "SELECT s.nombre, a.fecha_cita , ec.estado
                                        FROM asignacion_citas a
                                        JOIN servicios s ON a.servicio_id = s.id
                                        join estado_cita ec ON a.estado_cita_id = ec.id
                                        WHERE a.cita_id = {$cita['id']}";
                    
                    $servicios = $conn->query($query_servicios);
                    ?>
                    
                    <table class="sub-table">
                        <thead>
                            <tr>
                                <th>Servicio Asignado</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($servicio = $servicios->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($servicio['nombre']) ?></td>
                                <td><?= date('d/m/Y', strtotime($servicio['fecha_cita'])) ?></td>
                                <td>Programado</td>
                                <td>
                                    <a href="?eliminar=<?= $servicio['id'] ?>" class="btn btn-danger btn-xs">Eliminar</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
    function toggleDetalles(id) {
        const detalleRow = document.getElementById(`detalles-${id}`);
        detalleRow.style.display = detalleRow.style.display === 'none' ? 'table-row' : 'none';
        
        // Rotar ícono (opcional)
        const mainRow = detalleRow.previousElementSibling;
        mainRow.classList.toggle('expanded');
    }
    </script>
</body>
</html>