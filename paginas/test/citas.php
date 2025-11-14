<?php
include '../../config.php';

// Verificar permisos
if ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'recepcion') {
    header("Location: sin_permisos.php");
    exit();
}

// Obtener todas las citas
$citas = $conn->query("
    SELECT c.*, p.nombre AS paciente 
    FROM cita c
    INNER JOIN pacientes p ON c.paciente_id = p.id
    ORDER BY c.fecha_cita DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Citas</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body>
    <div class="container-fluid">
        <h1 class="mt-4">Citas Registradas</h1>
        
        <!-- Botón nueva cita -->
        <a href="crear_cita.php" class="btn btn-success mb-4">
            <i class="fas fa-plus"></i> Nueva Cita
        </a>

        <!-- Tabla de citas -->
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Fecha Cita</th>
                            <th>Servicios</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cita = $citas->fetch_assoc()): ?>
                        <tr>
                            <td><?= $cita['paciente'] ?></td>
                            <td><?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></td>
                            <td>
                                <?php 
                                $servicios = $conn->query("
                                    SELECT s.nombre 
                                    FROM asignacion_citas a
                                    JOIN servicios s ON a.servicio_id = s.id
                                    WHERE a.cita_id = {$cita['id']}
                                ");
                                //echo implode(', ', $servicios->fetch_all(PDO::FETCH_COLUMN));
                                echo implode(', ', array_column($servicios->fetch_all(MYSQLI_ASSOC), 'nombre'));
                                ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= 
                                    $cita['estado'] == 'activa' ? 'success' : 
                                    ($cita['estado'] == 'cancelada' ? 'danger' : 'warning') 
                                ?>">
                                    <?= ucfirst($cita['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="detalle_cita.php?id=<?= $cita['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>