<?php
include '../../config.php';

// Obtener ID de cita
$cita_id = $_GET['id'] ?? die("Cita no especificada");

// Obtener datos de la cita
$cita = $conn->query("
    SELECT c.*, p.nombre AS paciente 
    FROM cita c
    INNER JOIN pacientes p ON c.paciente_id = p.id
    WHERE c.id = $cita_id
")->fetch_assoc();

// Obtener servicios asignados
$servicios = $conn->query("
    SELECT a.*, s.nombre AS servicio 
    FROM asignacion_citas a
    INNER JOIN servicios s ON a.servicio_id = s.id
    WHERE a.cita_id = $cita_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalle de Cita</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body>
    <div class="container-fluid">
        <h1 class="mt-4">Cita #<?= $cita_id ?> - <?= $cita['paciente'] ?></h1>
        
        <!-- Formulario de edición -->
        <form action="actualizar_cita.php" method="POST">
            <input type="hidden" name="cita_id" value="<?= $cita_id ?>">

            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($servicio = $servicios->fetch_assoc()): ?>
                            <tr>
                                <td><?= $servicio['servicio'] ?></td>
                                <td>
                                    <input type="date" name="fechas[<?= $servicio['id'] ?>]" 
                                           value="<?= $servicio['fecha_cita'] ?>" class="form-control">
                                </td>
                                <td>
                                    <select name="estados[<?= $servicio['id'] ?>]" class="form-control">
                                        <option value="pendiente" <?= $servicio['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="completado" <?= $servicio['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
                                        <option value="cancelado" <?= $servicio['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm eliminar-servicio" 
                                            data-id="<?= $servicio['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
            <button type="button" id="btnCancelarCita" class="btn btn-danger mt-3">
                Cancelar Cita Completa
            </button>
        </form>
    </div>

    <script>
    // Eliminar servicio
    $('.eliminar-servicio').click(function() {
        const servicio_id = $(this).data('id');
        if (confirm('¿Eliminar este servicio de la cita?')) {
            $.post('acciones/eliminar_servicio_cita.php', { id: servicio_id }, function() {
                location.reload();
            });
        }
    });

    // Cancelar cita completa
    $('#btnCancelarCita').click(function() {
        if (confirm('¿Cancelar toda la cita?')) {
            $.post('acciones/cancelar_cita.php', { id: <?= $cita_id ?> }, function() {
                window.location.href = 'citas.php';
            });
        }
    });
    </script>
</body>
</html>