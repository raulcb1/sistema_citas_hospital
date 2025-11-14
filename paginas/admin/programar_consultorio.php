<?php
session_start();
include 'config.php';

// Validar permisos
if ($_SESSION['rol'] != 'admin') {
    header("Location: sin_permisos.php");
    exit();
}

// Obtener servicio a programar
$serv_programar = $_POST['servicio_id'] ?? null;
if (!$serv_programar) die("Servicio no especificado");

// Obtener datos del servicio
$datos_servicio = $conn->query("SELECT * FROM servicios WHERE id = $serv_programar");
$datos_servicio = $datos_servicio->fetch_assoc();
$nombre_servicio = $datos_servicio['nombre'];
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <h1>PROGRAMACIÓN DE <?= strtoupper($nombre_servicio) ?></h1>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid"  style="height: 100%;">
        <!-- Botón programación recurrente -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#modalProgramacionRecurrente">
            <i class="fas fa-calendar-alt"></i> Programación Recurrentee
        </button>

        <!-- Calendario -->
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Calendario Programado para el Servicio de <?php echo $nombre_servicio; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="calendario" style="width: 70%;"></div>
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
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Turno:</label>
                                        <select name="turno" class="form-control" required>
                                            <option value="mañana">Mañana</option>
                                            <option value="tarde">Tarde</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Frecuencia:</label>
                                <select id="frecuencia" name="frecuencia" class="form-control" required>
                                    <option value="unica">Única</option>
                                    <option value="diaria">Diaria</option>
                                    <option value="semanal">Semanal</option>
                                </select>
                            </div>

                            <div id="fechaUnica">
                                <input type="date" class="form-control" name="fecha_inicio"
                                    value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div id="rangoFechas" style="display:none;">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="fecha_inicio"
                                        value="<?= date('Y-m-d') ?>" required>
                                    <span class="input-group-text">al</span>
                                    <input type="date" class="form-control" name="fecha_fin"
                                        value="<?= date('Y-m-d') ?>" required>
                                </div>
                            </div>

                            <div id="diasSemana" class="mt-3" style="display:none;">
                                <label>Días:</label>
                                <div class="row">
                                    <?php $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']; ?>
                                    <?php foreach ($dias as $dia): ?>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                    name="dias[]" value="<?= $dia ?>">
                                                <label class="form-check-label"><?= $dia ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="modalEventos" tabindex="-1" aria-labelledby="modalEventosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEventosLabel">Programación del día</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Aquí se mostrarán los eventos -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</section>
<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- <script src="plugins/fullcalendar/6.1.8/index.min.js"></script> -->
<script src="plugins/fullcalendar/6.1.8/locales-all.global.min.js"></script>
<script src="plugins/fullcalendar/6.1.8/index.global.min.js"></script>
<script src="plugins/fullcalendar/6.1.8/daygrid/index.global.min.js"></script>
<script src="plugins/fullcalendar/6.1.8/interaction/index.global.min.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Configurar calendario
        const calendarEl = document.getElementById('calendario');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',
            initialView: 'dayGridMonth',
            selectable: true,
            //events: `funciones/get_asignaciones.php?servicio_id=<?= $serv_programar ?>`,
            eventDidMount: function(info) {
                info.el.style.backgroundColor = info.event.extendedProps.turno === 'tarde' ? '#dc3545' : '#007bff';
            },
            dateClick: function(info) {
                cargarEventosDelDia(info.dateStr);
                console.error('Detectó el clic en:', info.dateStr);
                //window.location.href = `funciones/get_asignaciones.php?start=${info.dateStr}&servicio_id=<?= $serv_programar ?>`;
            },
            eventClick: function(info) {
                Swal.fire({
                    title: 'Asignación Existente',
                    html: `<b>Consultorio:</b> ${info.event.extendedProps.servicio}<br>
                               <b>Médico:</b> ${info.event.extendedProps.medico}<br>
                               <b>Turno:</b> ${info.event.extendedProps.turno}`,
                    icon: 'info'
                })
            },

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
                //console.error('URL Generada:', url);
            }
        });
        calendar.render();
    });

    

    // Manejar frecuencia
    $('#frecuencia').on('change', function() {
        const freq = $(this).val();
        $('#fechaUnica, #rangoFechas, #diasSemana').hide();
        freq === 'unica' ? $('#fechaUnica').show() : $('#rangoFechas').show();
        if (freq === 'semanal') $('#diasSemana').show();
    });

    // Enviar programación
    $('#formProgramacionRecurrente').submit(async (e) => {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(e.target));

        try {
            // Validar fechas
            const start = moment(formData.fecha_inicio);
            const end = formData.fecha_fin ? moment(formData.fecha_fin) : start;

            if (end < start) throw new Error('Fecha final no puede ser anterior');

            // Generar fechas
            let fechas = [];
            let current = start.clone();
            const dias = $('input[name="dias[]"]:checked').map((i, el) => el.value).get();

            const mapDay = {
                'Monday': 'Lunes', 'Tuesday': 'Martes',
                'Wednesday': 'Miércoles', 'Thursday': 'Jueves',
                'Friday': 'Viernes', 'Saturday': 'Sábado',
                'Sunday': 'Domingo'
                };

            while (current <= end) {
                if (
                    formData.frecuencia === 'unica' ||
                    formData.frecuencia === 'diaria' ||
                    (formData.frecuencia === 'semanal' && dias.includes(mapDay[current.format('dddd')]))
                ) {
                    fechas.push(current.format('YYYY-MM-DD'));
                }
                current.add(1, 'day');
            }

            // Enviar al servidor
            /*const {
                value: confirmar
            } = await Swal.fire({
                title: '¿Confirmar programación?',
                html: `Se crearán ${fechas.length} asignaciones`,
                showCancelButton: true
            }); */

            // … después de generar `fechas`, justo antes de mostrar el modal:
            console.log('formData:', formData);
            console.log('start:', start.format('YYYY-MM-DD'));
            console.log('end:',   end.format('YYYY-MM-DD'));
            console.log('dias seleccionados:', dias);
            console.log('fechas calculadas:', fechas);

            const { value: confirmar } = await Swal.fire({
            title: '¿Confirmar programación?',
            width: 600,
            html: `
                <p>Frecuencia: <b>${formData.frecuencia}</b></p>
                <p>Fecha inicio: <b>${start.format('YYYY-MM-DD')}</b><br>
                Fecha fin:     <b>${end.format('YYYY-MM-DD')}</b></p>
                <p>Días seleccionados: <b>${dias.join(', ') || '— ninguno —'}</b></p>
                <p>Fechas generadas (${fechas.length}):<br>
                <small style="max-height:100px;display:block;overflow:auto;">
                    ${fechas.length 
                    ? fechas.map(d=>`<code>${d}</code>`).join(' ') 
                    : '<i>(ninguna)</i>'}
                </small>
                </p>
                <p>Se crearán <b>${fechas.length}</b> asignaciones.</p>
            `,
            showCancelButton: true,
            confirmButtonText: 'Sí, continúa',
            cancelButtonText: 'Cancelar'
            });

            if (!confirmar) return;

            const response = await fetch('funciones/guardar_asignaciones_recurrentes_2.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    medico_id: formData.medico_id,
                    servicio_id: <?= $serv_programar ?>,
                    turno: formData.turno,
                    fechas: fechas,
                    sobreescribir: false
                })
            });

            const result = await response.json();

            if (result.conflictos) {
                const {
                    isConfirmed
                } = await Swal.fire({
                    title: 'Conflictos detectados',
                    html: `Existen programaciones en:<br>${result.conflictos.join('<br>')}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sobreescribir',
                    cancelButtonText: 'Cancelar'
                });

                if (isConfirmed) {
                    await fetch('funciones/guardar_asignaciones_recurrentes_2.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            ...result.data,
                            sobreescribir: true
                        })
                    });
                }
            }

            Swal.fire('¡Éxito!', 'Programación guardada', 'success');
            calendar.refetchEvents();
            $('#modalProgramacionRecurrente').modal('hide');

        /* } catch (error) {
            Swal.fire('Error', error.message, 'error');
            console.error('Detalles:', error);
        } */

        } catch (error) {
            // Preparamos datos de depuración
            const debugHtml = `
                <p><b>Mensaje:</b> ${error.message}</p>
                <p><b>Stack trace:</b><pre style="text-align:left; white-space:pre-wrap;">${error.stack}</pre></p>
                <p><b>Tipo de <code>calendar</code>:</b> ${typeof calendar}</p>
                <p><b>Contenido de <code>calendar</code> (window.calendar):</b><pre style="text-align:left; white-space:pre-wrap;">${
                typeof window.calendar !== 'undefined'
                    ? JSON.stringify(window.calendar, null, 2)
                    : 'no existe'
                }</pre></p>
                <p><b>Result servidor:</b><pre>${JSON.stringify(result, null,2)}</pre></p>

                <p><b>Fechas generadas:</b><pre>${fechas.join(', ')}</pre></p>
                <p><b>Datos enviados al servidor:</b><pre>${JSON.stringify({
                    medico_id: formData.medico_id,
                    servicio_id: <?= $serv_programar ?>,
                    turno: formData.turno,
                    fechas: fechas,
                    sobreescribir: false
                }, null, 2)}</pre></p>
            `;

            // Mostrar modal ampliado
            await Swal.fire({
                title: '❌ Error Detallado',
                html: debugHtml,
                icon: 'error',
                width: 800,
                customClass: {
                popup: 'swal2-scrollbar'  // para que sea scrollable si es muy largo
                }
            });

            // También lo dejamos en consola
            console.error('Error completo:', error);
            console.log('window.calendar =', window.calendar);
            }

    });

    function cargarEventosDelDia(fecha) {
        console.error('Entro a cargar eventos del día:', fecha);
        $.ajax({
            url: 'funciones/get_eventos_dia.php', // Ruta para obtener eventos del día
            method: 'GET',
            data: {
                fecha: fecha
            },
            success: function(response) {
                mostrarEventosEnModal(response); // Mostrar los eventos en un modal
                console.error('Eventos del día:', response);
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar eventos:', error);
            }
        })
    }

    function mostrarEventosEnModal(eventos) {
        console.error('Entró al modal. Eventos a mostrar:', eventos);
        // Agrupar eventos por turno
        try {
            const eventosPorTurno = {};
            eventos.forEach(evento => {
                if (!eventosPorTurno[evento.turno]) {
                    eventosPorTurno[evento.turno] = [];
                }
                eventosPorTurno[evento.turno].push(evento);
                console.error('Eventos por turno:', eventosPorTurno);
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
                console.error('Contenido:', contenido);
            }

            // Mostrar el modal
            $('#modalEventos.modal-body').html(contenido);
            $('#modalEventos').modal('show');
        } catch (error) {
            console.error('Error al mostrar eventos Modal:', error);
        }
    }
</script>