<?php
//include 'config.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: sin_permisos.php");
    exit();
}

$serv_programar = $_POST['servicio_id'] ?? null;
if (!$serv_programar) die("Servicio no especificado");

//obtenemos datos del servicio que vamos a programar
$datos_servicio = $conn->query("SELECT * FROM servicios WHERE id = $serv_programar");
$datos_servicio = $datos_servicio->fetch_assoc();
$nombre_servicio = $datos_servicio['nombre'];

$servicios = $conn->query("SELECT id, nombre FROM servicios WHERE activo = 1 AND id = $serv_programar");

?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <h1>PROGRAMACIÓN DEL SERVICIO DE <?php echo $nombre_servicio; ?></h1>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Botón para abrir el modal de programación recurrente -->
            <button class="btn btn-success" data-toggle="modal" data-target="#modalProgramacionRecurrente">
                <i class="fas fa-calendar-alt"></i> Programación Recurrente
            </button>
        </div>

        <div class="row mb-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Calendario Programado para el Servicio de <?php echo $nombre_servicio; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="calendario"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Programación Recurrente -->
        <div class="modal fade" id="modalProgramacionRecurrente">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">Programación Recurrente</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="formProgramacionRecurrente">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Médico:</label>
                                        <select name="medico_id" class="form-control" required>
                                            <?php
                                                $medicos = $conn->query("SELECT m.id, u.nombre 
                                                                       FROM medicos m 
                                                                       INNER JOIN usuarios u ON m.usuario_id = u.id");
                                                while ($medico = $medicos->fetch_assoc()): ?>
                                            <option value="<?= $medico['id'] ?>"><?= $medico['nombre'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Turno:</label>
                                        <select name="turno" class="form-control" required>
                                            <option value="mañana">Mañana</option>
                                            <option value="tarde">Tarde</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Selector de Frecuencia -->
                            <div class="form-group">
                                <label>Frecuencia:</label>
                                <select id="frecuencia" class="form-control" required>
                                    <option value="unica">Única</option>
                                    <option value="diaria">Diaria</option>
                                    <option value="semanal">Semanal</option>
                                </select>
                            </div>

                            <!-- Selectores de Fecha -->
                            <div id="fechaUnica">
                                <input type="date" class="form-control" name="fecha_inicio" value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>
                            <div id="rangoFechas" style="display:none;">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="fecha_inicio"
                                        value="<?= date('Y-m-d')?>" required>
                                    <span class="input-group-text">al</span>
                                    <input type="date" class="form-control" name="fecha_fin"
                                        value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>

                            <!-- Días de la semana -->
                            <div id="diasSemana" class="mt-3" style="display:none;">
                                <label>Días:</label>
                                <div class="row">
                                    <?php 
                                        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                                        foreach ($dias as $dia): ?>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dias[]"
                                                value="<?= $dia ?>">
                                            <label class="form-check-label"><?= $dia ?></label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="guardar_recurrente" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Programación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/fullcalendar/6.1.8/locales-all.global.min.js"></script>
<script src="plugins/fullcalendar/6.1.8/index.global.min.js"></script>
<script src="plugins/fullcalendar/6.1.8/daygrid/index.global.min.js"></script>
<script src="plugins/fullcalendar/6.1.8/interaction/index.global.min.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2@11.js"></script> <!-- Incluir SweetAlert2 -->

<script>
//Script para el modal de programacion recurrente. 
$(document).ready(function() {
    // Establecer el idioma en español para moment.js
    moment.locale('es');

    // Lógica del Modal
    $('#frecuencia').change(function() {
        const freq = $(this).val();
        $('#fechaUnica, #rangoFechas, #diasSemana').hide();

        if (freq === 'unica') {
            $('#fechaUnica').show();
        } else {
            $('#rangoFechas').show();
            if (freq === 'semanal') $('#diasSemana').show();
        }
    });

    // Mostrar/ocultar campos según la frecuencia
    /*$('#frecuencia').change(function() {
        const frecuencia = $(this).val();
        if (frecuencia === 'unica') {
            $('#rangoFechas').hide(); // Ocultar rango de fechas
            $('#diasSemana').hide(); // Ocultar días de la semana
            $('#fechaUnica').show(); // Mostrar solo fecha_inicio

            // Ajustar atributos 'required'
            $('input[name="fecha_inicio"]').prop('required', true);
            $('input[name="fecha_fin"]').prop('required', false);
            $('input[name="dias[]"]').prop('required', false);
        } else if (frecuencia === 'semanal') {
            $('#fechaUnica').hide(); // Ocultar fecha única
            $('#rangoFechas').show(); // Mostrar rango de fechas
            $('#diasSemana').show(); // Mostrar días de la semana

            // Ajustar atributos 'required'
            $('input[name="fecha_inicio"]').prop('required', true);
            $('input[name="fecha_fin"]').prop('required', true);
            $('input[name="dias[]"]').prop('required', true);
        } else if (frecuencia === 'diaria') {
            $('#fechaUnica').hide(); // Ocultar fecha única
            $('#rangoFechas').show(); // Mostrar rango de fechas
            $('#diasSemana').hide(); // Ocultar días de la semana

            // Ajustar atributos 'required'
            $('input[name="fecha_inicio"]').prop('required', true);
            $('input[name="fecha_fin"]').prop('required', true);
            $('input[name="dias[]"]').prop('required', false);
        }
    });*/

    // Inicializar el modal con frecuencia "Única"
    $('#modalProgramacionRecurrente').on('show.bs.modal', function() {
        $('#frecuencia').val('unica').trigger('change');
    });

    // Enviar datos al servidor
    /*$('#formProgramacionRecurrente').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serializeArray();

        // Log para depuración
        console.log('Datos del formulario:', formData);

        // Calcular fechas según la frecuencia
        const fechaInicio = moment(formData.find(f => f.name === 'fecha_inicio').value);
        const fechaFin = moment(formData.find(f => f.name === 'fecha_fin').value);
        const frecuencia = formData.find(f => f.name === 'frecuencia').value;
        const dias = formData.filter(f => f.name === 'dias[]').map(d => d.value);

        let fechas = [];
        let currentDate = fechaInicio.clone();

        while (currentDate <= fechaFin) {
            switch (frecuencia) {
                case 'diaria':
                    fechas.push(currentDate.format('YYYY-MM-DD'));
                    break;
                case 'semanal':
                    if (dias.includes(currentDate.format('dddd'))) {
                        fechas.push(currentDate.format('YYYY-MM-DD'));
                    }
                    break;
                case 'mensual':
                    fechas.push(currentDate.format('YYYY-MM-DD'));
                    currentDate.add(1, 'month');
                    continue; // Saltar el día siguiente
                default: // Única
                    fechas.push(fechaInicio.format('YYYY-MM-DD'));
                    break;
            }
            currentDate.add(1, 'day');
        }

        // Enviar fechas al servidor
        $.ajax({
            url: 'funciones/guardar_asignaciones_recurrentes.php',
            method: 'POST',
            data: {
                medico_id: formData.find(f => f.name === 'medico_id').value,
                servicio_id: formData.find(f => f.name === 'servicio_id').value,
                turno: formData.find(f => f.name === 'turno').value,
                fechas: fechas
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('¡Éxito!', 'Asignaciones guardadas correctamente.',
                        'success');
                    $('#modalProgramacionRecurrente').modal('hide');
                    calendar.refetchEvents(); // Recargar el calendario
                } else {
                    Swal.fire('Error', response.error, 'error');
                }
            }
        });
    });*/

    // Envío del Formulario
    $('#formProgramacionRecurrente').submit(async function(e) {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(this));
        const {
            medico_id,
            turno,
            fecha_inicio,
            fecha_fin
        } = formData;
        const frecuencia = $('#frecuencia').val();
        const dias = $('input[name="dias[]"]:checked').map((i, el) => el.value).get();

        // Validación de fechas
        const start = moment(fecha_inicio);
        const end = frecuencia === 'unica' ? start : moment(fecha_fin);
        if (end < start) {
            Swal.fire('Error', 'La fecha final no puede ser anterior a la inicial', 'error');
            return;
        }

        // Generar fechas
        let fechas = [];
        let current = start.clone();

        while (current <= end) {
            if (
                frecuencia === 'unica' ||
                frecuencia === 'diaria' ||
                (frecuencia === 'semanal' && dias.includes(current.format('dddd')))) {
                fechas.push(current.format('YYYY-MM-DD'));
            }
            current.add(1, 'day');
        }

        // Verificar antes del envío del formulario
        const verificarConflictos = await fetch('funciones/verificar_conflicto.php', {
            method: 'POST',
            body: JSON.stringify({
                servicio_id,
                fecha,
                turno
            })
        });

        if (!verificarConflictos.ok) {
            throw new Error('Error en verificación de conflictos');
        }

        const conflictos = await verificarConflictos.json();
        if (conflictos.existe) {
            // Mostrar diálogo de confirmación
        }

        // Enviar al servidor
        try {
            const {
                value: confirmar
            } = await Swal.fire({
                title: '¿Guardar programación?',
                html: `Se crearán ${fechas.length} asignaciones`,
                showCancelButton: true,
                confirmButtonText: 'Guardar'
            });

            if (!confirmar) return;

            const response = await fetch('funciones/guardar_asignaciones_recurrentes_2.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    medico_id,
                    servicio_id: <?= $serv_programar ?>,
                    turno,
                    fechas,
                    sobreescribir: false
                }),
            });

            const result = await response.json();

            if (result.conflictos) {
                const {
                    isConfirmed
                } = await Swal.fire({
                    title: 'Conflictos detectados',
                    html: `Fechas ocupadas:<br>${result.conflictos.join('<br>')}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sobreescribir'
                });

                if (isConfirmed) {
                    await fetch('funciones/guardar_asignaciones_recurrentes_2.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            medico_id,
                            servicio_id: <?= $serv_programar ?>,
                            turno,
                            fechas,
                            sobreescribir: true
                        })
                    });
                }
            }

            Swal.fire('¡Éxito!', 'Programación guardada', 'success');
            calendar.refetchEvents();
            $('#modalProgramacionRecurrente').modal('hide');

        } catch (error) {
            Swal.fire('Error', error.message, 'error');
        }
    });




});
</script>

<script>
//Script para el calendario de programacion de consultorios.
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');
    console.log(calendarEl); // Verificar si el contenedor está presente
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            //plugins: [ 'interaction', 'dayGrid' ],
            locale: 'es',
            initialView: 'dayGridMonth',
            timeZone: 'America/Lima', // Establecer la zona horaria de Lima, Perú
            aspectRatio: 2, // Ajusta esta proporción según tus necesidades
            themeSystem: 'bootstrap6',
            headerToolbar: {
                start: "today",
                center: "title",
                end: "prev,next"
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            dayMaxEvents: true, // allow "more" link when too many events
            selectable: true,
            /*dateClick: function(info) {
                $('#fechaSeleccionada').val(info.dateStr);
                $('#modalAsignacion').modal('show');
            },*/
            /*eventClick: function(info) {
                // Actualizar los elementos del modal con los detalles del evento
                $('#eventoServicio').text(info.event.extendedProps.servicio);
                $('#eventoMedico').text(info.event.extendedProps.medico);
                $('#eventoTurno').text(info.event.extendedProps.turno);
                // Mostrar el modal
                $('#modalEvento').modal('show');
            },*/
            dateClick: function(info) {
                // Llamar a una función para cargar los eventos del día seleccionado
                cargarEventosDelDia(info.dateStr);
            },

            eventClick: function(info) {
                Swal.fire({
                    title: 'Asignación Existente',
                    html: `<b>Consultorio:</b> ${info.event.extendedProps.servicio}<br>
                               <b>Médico:</b> ${info.event.extendedProps.medico}<br>
                               <b>Turno:</b> ${info.event.extendedProps.turno}`,
                    icon: 'info'
                });
            },
            /*
            events: 'funciones/get_asignaciones.php?servicio_id=<?php echo $serv_programar; ?>',
            */
            events: function(fetchInfo, successCallback, failureCallback) {
                // Obtener el ID del servicio desde algún lugar (por ejemplo, un select)
                const servicioId = <?php echo json_encode($serv_programar); ?>;

                // Construir la URL con el parámetro servicio_id
                const url =
                    `funciones/get_asignaciones.php?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}&servicio_id=${servicioId}`;

                // Hacer la solicitud fetch
                fetch(url)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => failureCallback(error));
            },
        });
        calendar.render();
    } else {
        console.error("Contenedor del calendario no encontrado");
    }

    $('#servicio_id, #fechaSeleccionada, #turno').change(function() {
        // Validación en tiempo real
    });
});

function cargarEventosDelDia(fecha) {
    $.ajax({
        url: 'funciones/get_eventos_dia.php', // Ruta para obtener eventos del día
        method: 'GET',
        data: {
            fecha: fecha
        },
        success: function(response) {
            mostrarEventosEnModal(response); // Mostrar los eventos en un modal
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar eventos:', error);
        }
    });
}

function mostrarEventosEnModal(eventos) {
    // Agrupar eventos por turno
    const eventosPorTurno = {};
    eventos.forEach(evento => {
        if (!eventosPorTurno[evento.turno]) {
            eventosPorTurno[evento.turno] = [];
        }
        eventosPorTurno[evento.turno].push(evento);
    });

    // Construir el contenido del modal
    let contenido = '';
    for (const turno in eventosPorTurno) {
        contenido += `<h4>Turno: ${turno}</h4>`;
        contenido += '<ul>';
        eventosPorTurno[turno].forEach(evento => {
            contenido += `<li>${evento.servicio} - ${evento.medico}</li>`;
        });
        contenido += '</ul>';
    }

    // Mostrar el modal
    $('#modalEventos.modal-body').html(contenido);
    $('#modalEventos').modal('show');
}
</script>