<?php
session_start();
include '../../config.php';

// Verificar permisos
if ($_SESSION['rol'] != 'admin' && $_SESSION['rol'] != 'recepcion') {
    header("Location: sin_permisos.php");
    exit();
}

// Obtener pacientes y servicios activos
$pacientes = $conn->query("SELECT id, CONCAT(nombre, ' ', apellido_p) AS nombre FROM pacientes");
$servicios = $conn->query("SELECT id, nombre FROM servicios WHERE activo = 1");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Nueva Cita</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/fullcalendar/main.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="container-fluid mt-6">
        <h1>Registrar Nueva Cita</h1>

        <form id="formCita">
            <!-- Campo oculto para almacenar ID del paciente -->
            <div id="pacienteIdSeleccionado"></div>

            <div class="form-group">
                <div class="row mb-6">
                    <label for="dni_paciente">DNI del Paciente:</label>
                    <input type="text" id="dni_paciente" name="dni_paciente" class="form-control" required>
                    <button type="button" class="btn btn-primary mt-2" onclick="getDatosPaciente()">Buscar</button>
                </div>
                <div class="row mb-6">
                    <div id="paciente-info" class="mt-3"></div>
                </div>

                <!-- Servicios Seleccionados -->
                <div class="row mb-12">
                    <h4>Servicios Agendados</h4>
                    <table class="table table-bordered" id="tblServicios">
                        <thead class="bg-light">
                            <tr>
                                <th>Servicio</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="row mb-12">
                    <!-- Selector de Servicios -->
                    <div class="card mb-12">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Selección de Servicios y Horarios</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="row mb-4">
                                        <div class="form-group">
                                            <label>Seleccionar Servicio:</label>
                                            <select id="selectServicio" class="form-control">
                                                <?php while($servicio = $servicios->fetch_assoc()): ?>
                                                <option value="<?= $servicio['id'] ?>"><?= $servicio['nombre'] ?>
                                                </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Calendario -->
                                    <div class="row mb-4">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Haz clic en una fecha para seleccionar un
                                            horario
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <div id="calendario" style="min-height: 600px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Guardar Cita Completa
            </button>
        </form>
    </div>

    <!-- Modal Horarios -->
    <div class="modal fade" id="modalHorarios">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Horarios Disponibles</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="listaHorarios">
                    <!-- Contenido dinámico de horarios -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarHora">Seleccionar Hora</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/fullcalendar/6.1.8/locales-all.global.min.js"></script>
    <script src="../../plugins/fullcalendar/6.1.8/index.global.min.js"></script>
    <script src="../../plugins/fullcalendar/6.1.8/daygrid/index.global.min.js"></script>
    <script src="../../plugins/fullcalendar/6.1.8/interaction/index.global.min.js"></script>

    <script>
    function getDatosPaciente() {
        const dniPaciente = $('#dni_paciente').val();
        console.log('Buscando paciente con DNI:', dniPaciente);

        $.ajax({
            url: '../../funciones/get_paciente.php',
            method: 'POST',
            data: {
                dni_paciente: dniPaciente
            },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta búsqueda paciente:', response);
                if (response.success) {
                    const pacienteInfo = `
                        <div class="alert alert-success">
                            <strong>Paciente encontrado:</strong><br>
                            ${response.data.nombre} ${response.data.apellido_p}<br>
                            DNI: ${response.data.dni}
                        </div>
                        <input type="hidden" id="paciente_id" name="paciente_id" value="${response.data.id}">`;
                    $('#paciente-info').html(pacienteInfo);
                } else {
                    $('#paciente-info').html(
                        '<div class="alert alert-danger">Paciente no encontrado</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en búsqueda paciente:', error);
                $('#paciente-info').html(`<div class="alert alert-danger">Error: ${error}</div>`);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        let calendar;
        let serviciosSeleccionados = [];
        let servicioActualId = $('#selectServicio').val();
        let fechaSeleccionada = null;

        // Inicializar calendario
        const inicializarCalendario = (servicioId) => {
            if (calendar) calendar.destroy();

            calendar = new FullCalendar.Calendar(document.getElementById('calendario'), {
                initialView: 'dayGridMonth',
                locale: 'es',
                dateClick: handleDateClick,
                events: function(fetchInfo, successCallback) {
                    const url =
                        `../../funciones/get_asignaciones.php?servicio_id=${servicioId}&start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`;
                    console.log('Fetching eventos para calendario:', url);

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Eventos recibidos:', data);
                            successCallback(data);
                        })
                        .catch(error => console.error('Error fetching eventos:', error));
                }
            });

            calendar.render();
        };

        // Manejar clic en fecha
        const handleDateClick = (info) => {
            fechaSeleccionada = info.dateStr;
            console.log('Fecha seleccionada:', fechaSeleccionada);
            fetchHorariosDisponibles(fechaSeleccionada, servicioActualId);
        };

        // Obtener horarios disponibles
        const fetchHorariosDisponibles = (fecha, servicioId) => {
            console.log(`Fetching horarios para servicio ${servicioId} en ${fecha}`);

            $('#listaHorarios').html(
                '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
            $('#modalHorarios').modal('show');

            fetch(`../../funciones/get_horarios_disponibles.php?servicio_id=${servicioId}&fecha=${fecha}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(horarios => {
                    console.log('Horarios recibidos:', horarios);
                    let html = '<div class="form-group">';

                    if (horarios.error) {
                        html += `<div class="alert alert-danger">${horarios.error}</div>`;
                    } else if (horarios.length === 0) {
                        html += '<div class="alert alert-warning">No hay horarios disponibles</div>';
                    } else {
                        html += '<label>Seleccione un horario:</label>';
                        html += '<select class="form-control" id="selectHora">';
                        horarios.forEach(hora => {
                            html += `<option value="${hora}">${hora}</option>`;
                        });
                        html += '</select>';
                    }

                    html += '</div>';
                    $('#listaHorarios').html(html);
                })
                .catch(error => {
                    console.error('Error fetching horarios:', error);
                    $('#listaHorarios').html(`<div class="alert alert-danger">${error.message}</div>`);
                });
        };

        // Confirmar selección de hora
        $('#btnConfirmarHora').click(function() {
            const hora = $('#selectHora').val();
            const servicioNombre = $('#selectServicio option:selected').text();

            console.log('Intentando agregar servicio:', {
                servicio_id: servicioActualId,
                fecha: fechaSeleccionada,
                hora: hora
            });

            // Validar duplicados
            const existe = serviciosSeleccionados.some(s =>
                s.servicio_id == servicioActualId &&
                s.fecha == fechaSeleccionada &&
                s.hora == hora
            );

            if (existe) {
                Swal.fire('Error', 'Este horario ya fue seleccionado', 'error');
                return;
            }

            // Agregar a la tabla
            $('#tblServicios tbody').append(`
                <tr data-servicio="${servicioActualId}" data-fecha="${fechaSeleccionada}" data-hora="${hora}">
                    <td>${servicioNombre}</td>
                    <td>${fechaSeleccionada}</td>
                    <td>${hora}</td>
                    <td>
                        <button class="btn btn-danger btn-sm btn-quitar">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `);

            serviciosSeleccionados.push({
                servicio_id: servicioActualId,
                fecha: fechaSeleccionada,
                hora: hora
            });

            $('#modalHorarios').modal('hide');
        });

        // Quitar servicio
        $(document).on('click', '.btn-quitar', function() {
            const row = $(this).closest('tr');
            const index = serviciosSeleccionados.findIndex(s =>
                s.servicio_id == row.data('servicio') &&
                s.fecha == row.data('fecha') &&
                s.hora == row.data('hora')
            );

            if (index > -1) {
                serviciosSeleccionados.splice(index, 1);
                row.remove();
            }
        });

        // Cambiar servicio
        $('#selectServicio').change(function() {
            servicioActualId = $(this).val();
            console.log('Servicio cambiado a:', servicioActualId);
            inicializarCalendario(servicioActualId);
        });

        // Enviar formulario
        $('#formCita').submit(function(e) {
            e.preventDefault();
            console.log('Enviando formulario con datos:', {
                paciente_id: $('#paciente_id').val(),
                servicios: serviciosSeleccionados
            });

            if (serviciosSeleccionados.length === 0) {
                Swal.fire('Error', 'Debe seleccionar al menos un servicio', 'error');
                return;
            }

            if (!$('#paciente_id').val()) {
                Swal.fire('Error', 'Debe seleccionar un paciente', 'error');
                return;
            }

            fetch('guardar_cita.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        paciente_id: $('#paciente_id').val(),
                        servicios: serviciosSeleccionados
                    })
                })
                .then(response => response.json())
                .then(result => {
                    console.log('Respuesta del servidor:', result);
                    if (result.success) {
                        window.location.href = 'citas.php';
                    } else {
                        Swal.fire('Error', result.error || 'Error desconocido', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error en el envío:', error);
                    Swal.fire('Error', 'Error de conexión', 'error');
                });
        });

        // Inicializar calendario
        inicializarCalendario(servicioActualId);
    });
    </script>
</body>

</html>