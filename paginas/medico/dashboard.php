<?php
include 'config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'medico') {
    header("Location: ../../login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$fecha_actual = date('Y-m-d');

// Obtener consultorios asignados hoy
$sql_consultorios = "SELECT c.nombre, ac.turno 
                     FROM asignacion_consultorios ac
                     INNER JOIN medicos m ON ac.medico_id = m.id
                     INNER JOIN consultorios c ON ac.consultorio_id = c.id
                     WHERE m.usuario_id = $usuario_id 
                     AND ac.fecha = '$fecha_actual'";
$consultorios = $conn->query($sql_consultorios);
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Consultorios Asignados - <?php echo date('d/m/Y'); ?></h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if ($consultorios->num_rows > 0): ?>
                <?php while ($consultorio = $consultorios->fetch_assoc()): ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5><?php echo $consultorio['nombre']; ?> (Turno <?php echo ucfirst($consultorio['turno']); ?>)</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Paciente</th>
                                        <th>DNI</th>
                                        <th>Hora Cita</th>
                                        <th>Servicio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    /* query para obtener los pacientes que tiene atencion el dia de hoy que van a ser 
                                    atendidos por el medico que esta usando el sistema:
                                    SELECT p.nombre, p.dni, acita.fecha_cita, s.nombre AS servicio
                                    FROM asignacion_citas acita
                                    INNER JOIN cita ci ON acita.cita_id = ci.id 
                                    INNER JOIN pacientes p ON acita.paciente_id = p.id
                                    INNER JOIN servicios s ON acita.servicio_id = s.id
                                    INNER JOIN asignacion_consultorios ac ON acita.servicio_id = ac.servicio_id
                                    WHERE ac.medico_id = (SELECT id FROM medicos WHERE usuario_id = 23) and DATE(acita.fecha_cita) = CURDATE()
                                    */
                                    $sql_citas = "SELECT p.nombre, p.dni, cit.hora_cita, s.nombre AS servicio
                                                    FROM cita cit
                                                    INNER JOIN pacientes p ON cit.paciente_id = p.id
                                                    INNER JOIN servicios s ON cit.servicio_id = s.id
                                                    INNER JOIN asignacion_consultorios ac ON cit.asignacion_consultorio_id = ac.id
                                                    WHERE ac.medico_id = (SELECT id FROM medicos WHERE usuario_id = $usuario_id)
                                                    AND ac.fecha = '$fecha_actual'";
                                    $citas = $conn->query($sql_citas);
                                    if ($citas->num_rows > 0) {
                                        while ($cita = $citas->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$cita['nombre']}</td>
                                                    <td>{$cita['dni']}</td>
                                                    <td>{$cita['fecha_cita']}</td>
                                                    <td>{$cita['servicio']}</td>
                                                    </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No hay citas programadas</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info">No tienes consultorios asignados hoy.</div>
            <?php endif; ?>
        </div>
    </section>
</div>
