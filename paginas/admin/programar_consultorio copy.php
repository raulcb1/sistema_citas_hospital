<?php
include 'config.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: sin_permisos.php");
    exit();
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Programación de Consultorios</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div id="calendario"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal para Asignar Consultorio -->
<div class="modal fade" id="modalAsignacion">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">Asignar Consultorio</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formAsignacion">
                <div class="modal-body">
                    <div id="alerta-conflicto"></div>
                    <input type="hidden" id="fechaSeleccionada">
                    <div class="form-group">
                        <label>Médico:</label>
                        <select id="medico_id" class="form-control" required>
                            <?php
                            $medicos = $conn->query("SELECT m.id, u.nombre FROM medicos m INNER JOIN usuarios u ON m.usuario_id = u.id WHERE m.activo = 1");
                            while ($medico = $medicos->fetch_assoc()) {
                                echo "<option value='{$medico['id']}'>{$medico['nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Servicios:</label>
                        <select id="consultorio_id" class="form-control" required>
                            <?php
                            $consultorios = $conn->query("SELECT id, nombre FROM servicios WHERE activo = 1");
                            while ($consultorio = $consultorios->fetch_assoc()) {
                                echo "<option value='{$consultorio['id']}'>{$consultorio['nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Turno:</label>
                        <select id="turno" class="form-control" required>
                            <option value="mañana">Mañana</option>
                            <option value="tarde">Tarde</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/index.global.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.css">
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');
    console.log(calendarEl); // Verificar si el contenedor está presente
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            selectable: true,
            eventDidMount: function(info) {
                if (info.event.extendedProps.conflicto) {
                    info.el.style.border = '2px solid #FF0000';
                }
            },
            dateClick: function(info) {
                $('#fechaSeleccionada').val(info.dateStr);
                $('#modalAsignacion').modal('show');
            },
            eventClick: function(info) {
                Swal.fire({
                    title: 'Asignación Existente',
                    html: `<b>Consultorio:</b> ${info.event.extendedProps.consultorio}<br>
                            <b>Médico:</b> ${info.event.extendedProps.medico}<br>
                            <b>Turno:</b> ${info.event.extendedProps.turno}`,
                    icon: 'info'
                });
            },
            events: 'funciones/get_asignaciones.php'
        });
        calendar.render();
    } else {
        console.error("Contenedor del calendario no encontrado");
    }


    $('#formAsignacion').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'funciones/guardar_asignacion.php',
            method: 'POST',
            data: {
                medico_id: $('#medico_id').val(),
                consultorio_id: $('#consultorio_id').val(),
                fecha: $('#fechaSeleccionada').val(),
                turno: $('#turno').val()
            },
            success: function(response) {
                if (response.success) {
                    calendar.refetchEvents();
                    $('#modalAsignacion').modal('hide');
                } else {
                    alert(response.error);
                }
            }
        });
    });

    $('#consultorio_id, #fechaSeleccionada, #turno').change(function() {
        // Validación en tiempo real
    });
});
</script>