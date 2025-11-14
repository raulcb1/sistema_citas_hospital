<?php
include '../../config.php';

// Verificar permisos
if ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'recepcion') {
    header("Location: sin_permisos.php");
    exit();
}

// Obtener pacientes y servicios
$pacientes = $conn->query("SELECT id, CONCAT(nombre, ' ', apellido_p) AS nombre FROM pacientes");
$servicios = $conn->query("SELECT id, nombre FROM servicios WHERE activo = 1");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Nueva Cita</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>

<body>
    <div class="container-fluid">
        <h1 class="mt-4">Registrar Nueva Cita</h1>

        <form id="formCita" action="guardar_cita.php" method="POST">
            <!-- Selección de Paciente -->
            <div class="form-group">
                <label>Paciente:</label>
                <select name="paciente_id" class="form-control" required>
                    <?php while ($paciente = $pacientes->fetch_assoc()): ?>
                    <option value="<?= $paciente['id'] ?>"><?= $paciente['nombre'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Selector de Servicios -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Seleccionar Servicios</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <select id="selectServicio" class="form-control">
                                <?php while ($servicio = $servicios->fetch_assoc()): ?>
                                <option value="<?= $servicio['id'] ?>"><?= $servicio['nombre'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="button" id="btnAgregarServicio" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar Servicio
                        </button>
                    </div>

                    <!-- Calendario -->
                    <div id="calendario" class="mt-4"></div>

                    <!-- Servicios Seleccionados -->
                    <div class="mt-4">
                        <h5>Servicios a Agendar:</h5>
                        <table class="table" id="tblServicios">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Guardar Cita</button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/fullcalendar/6.1.8/locales-all.global.min.js"></script>
    <script src="../../plugins/fullcalendar/6.1.8/index.global.min.js"></script>
    <script src="../../plugins/fullcalendar/6.1.8/daygrid/index.global.min.js"></script>
    <script src="../../plugins/fullcalendar/6.1.8/interaction/index.global.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendario');
        let calendar;
        let serviciosSeleccionados = [];

        // Inicializar calendario
        function initCalendar(servicioId) {
            if (calendar) calendar.destroy();

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                dateClick: function(info) {
                    const servicio = $("#selectServicio option:selected").text();
                    const fecha = info.dateStr;

                    // Añadir a la tabla
                    $('#tblServicios tbody').append(`
                        <tr data-servicio="${servicioId}" data-fecha="${fecha}">
                            <td>${servicio}</td>
                            <td>${fecha}</td>
                            <td>
                                <button class="btn btn-sm btn-danger quitarServicio">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `);

                    // Guardar en array
                    serviciosSeleccionados.push({
                        servicio_id: servicioId,
                        fecha: fecha
                    });
                },
                events: `funciones/get_fechas_disponibles.php?servicio_id=${servicioId}`
            });
            calendar.render();
        }

        // Cambiar servicio
        $('#selectServicio').change(function() {
            initCalendar($(this).val());
        });

        // Agregar servicio
        $('#btnAgregarServicio').click(function() {
            initCalendar($('#selectServicio').val());
        });

        // Quitar servicio
        $(document).on('click', '.quitarServicio', function() {
            const row = $(this).closest('tr');
            const index = serviciosSeleccionados.findIndex(s =>
                s.servicio_id == row.data('servicio') &&
                s.fecha == row.data('fecha')
            );

            serviciosSeleccionados.splice(index, 1);
            row.remove();
        });

        // Enviar formulario
        $('#formCita').submit(function(e) {
            e.preventDefault();

            const formData = {
                paciente_id: $('[name="paciente_id"]').val(),
                servicios: serviciosSeleccionados
            };

            $.ajax({
                url: 'guardar_cita.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'citas.php';
                    } else {
                        alert(response.error);
                    }
                }
            });
        });
    });
    </script>
</body>

</html>