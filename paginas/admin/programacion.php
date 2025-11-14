<?php
//include 'config.php';

/*if ($_SESSION['rol'] != 'admin') {
    header("Location: sin_permisos.php");
    exit();
}*/

$servicios = $conn->query("SELECT id, nombre FROM servicios WHERE activo = 1");

?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <h1>Programación de Servicios</h1>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vista General</h3>
                    </div>
                    <div class="card-body">
                        <div id="calendario" style="width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="row mb-2">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Programar servicios:</h3>
                        </div>
                        <div class="card-body">
                            <form action="index.php" method="post">
                                <input type="hidden" name="pagina" value="admin_prog_cons">
                                <div class="form-group">
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <select name="servicio_id" class="form-control" required>
                                                <?php while ($servicio = $servicios->fetch_assoc()): ?>
                                                <option value="<?php echo $servicio['id']; ?>">
                                                    <?php echo $servicio['nombre']; ?>
                                                </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div></div>
                                        <div class="row mb-2">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">Programar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Nueva sección para ver programación por servicio -->
                    <div class="card md-4">
                        <div class="card-header">
                            <h3 class="card-title">Ver Programación por Servicio:</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <label for="servicio_filtro">Seleccionar Servicio:</label>
                                        <select id="servicio_filtro" class="form-control">
                                            <option value="">-- Seleccionar Servicio --</option>
                                            <?php $servicios->data_seek(0); while ($servicio_filtro = $servicios->fetch_assoc()): ?>
                                            <option value="<?php echo $servicio_filtro['id']; ?>">
                                                <?php echo $servicio_filtro['nombre']; ?>
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="fecha_inicio">Desde:</label>
                                        <input type="date" id="fecha_inicio" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="fecha_fin">Hasta:</label>
                                        <input type="date" id="fecha_fin" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <button type="button" id="btnVerProgramacion" class="btn btn-info btn-block">
                                            Ver Programación
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Asignar Servicios -->
    <div class="modal fade" id="modalAsignacion">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Asignar Servicios</h5>
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
                            <select id="servicio_id" class="form-control" required>
                                <?php
                                    $servicios = $conn->query("SELECT id, nombre FROM servicios WHERE activo = 1");
                                    while ($servicio = $servicios->fetch_assoc()) {
                                        echo "<option value='{$servicio['id']}'>{$servicio['nombre']}</option>";
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

    <!-- Modal para mostrar detalles del evento -->
    <div class="modal fade" id="modalEvento" tabindex="-1" aria-labelledby="modalEventoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="modalEventoLabel">Detalles del Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Servicio:</strong> <span id="eventoServicio"></span></p>
                    <p><strong>Médico:</strong> <span id="eventoMedico"></span></p>
                    <p><strong>Turno:</strong> <span id="eventoTurno"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar detalles del evento -->
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


    <!-- Nuevo Modal para mostrar programación por servicio -->
    <div class="modal fade" id="modalProgramacionServicio" tabindex="-1" aria-labelledby="modalProgramacionServicioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalProgramacionServicioLabel">Programación del Servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="contenidoProgramacionServicio">
                        <!-- Aquí se mostrará la programación del servicio -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>




    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.css"> -->
    <script src="plugins/fullcalendar/6.1.8/locales-all.global.min.js"></script>
    <script src="plugins/fullcalendar/6.1.8/index.global.min.js"></script>
    <script src="plugins/fullcalendar/6.1.8/daygrid/index.global.min.js"></script>
    <script src="plugins/fullcalendar/6.1.8/interaction/index.global.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <!-- Incluir SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2@11.js"></script>


</section>
<script>
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
                    html: `<b>Servicio:</b> ${info.event.extendedProps.servicio}<br>
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
                servicio_id: $('#servicio_id').val(),
                fecha: $('#fechaSeleccionada').val(),
                turno: $('#turno').val()
            },
            success: function(response) {
                if (response.success) {
                    calendar.refetchEvents();
                    $('#modalAsignacion').modal('hide');
                } else {
                    //alert(response.error);
                    alert(JSON.stringify(response.error));
                }
            }
        });
    });

    $('#servicio_id, #fechaSeleccionada, #turno').change(function() {
        // Validación en tiempo real
    });

    // Nuevo evento para el botón de ver programación
    $('#btnVerProgramacion').click(function() {
        var servicioId = $('#servicio_filtro').val();
        var servicioNombre = $('#servicio_filtro option:selected').text();
        const fechaInicio = $('#fecha_inicio').val();
        const fechaFin = $('#fecha_fin').val();
        
        if (servicioId === '') {
            Swal.fire({
                title: 'Advertencia',
                text: 'Por favor, selecciona un servicio.',
                icon: 'warning'
            });
            return;
        }

        cargarProgramacionServicio(servicioId, servicioNombre, fechaInicio, fechaFin);
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
    $('#modalEventos .modal-body').html(contenido);
    $('#modalEventos').modal('show');
}

// Nueva función para cargar programación por servicio
function cargarProgramacionServicio(servicioId, servicioNombre, fechaInicio, fechaFin) {
    $.ajax({
        url: 'funciones/get_programacion_servicio.php',
        method: 'GET',
        data: {
            servicio_id: servicioId,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        },
        success: function(response) {
            mostrarProgramacionServicio(response, servicioNombre);
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar programación del servicio:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error al cargar la programación del servicio.',
                icon: 'error'
            });
        }
    });
}

function mostrarProgramacionServicio(datos, servicioNombre) {
    // Actualizar el título del modal
    $('#modalProgramacionServicioLabel').text(`Programación del Servicio: ${servicioNombre}`);
    
    let contenido = '';
    
    if (datos.length === 0) {
        contenido = '<div class="alert alert-info">No hay programación registrada para este servicio.</div>';
    } else {
        // Agrupar por fecha
        const programacionPorFecha = {};
        datos.forEach(item => {
            if (!programacionPorFecha[item.fecha]) {
                programacionPorFecha[item.fecha] = [];
            }
            programacionPorFecha[item.fecha].push(item);
        });

        // Construir el contenido
        contenido += '<div class="table-responsive">';
        contenido += '<table class="table table-striped table-bordered">';
        contenido += '<thead class="thead-dark">';
        contenido += '<tr><th>Fecha</th><th>Médico</th><th>Turno</th><th>Consultorio</th></tr>';
        contenido += '</thead>';
        contenido += '<tbody>';

        // Ordenar fechas
        const fechasOrdenadas = Object.keys(programacionPorFecha).sort();
        
        fechasOrdenadas.forEach(fecha => {
            const fechaFormateada = new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            const items = programacionPorFecha[fecha];
            items.forEach((item, index) => {
                contenido += '<tr>';
                if (index === 0) {
                    contenido += `<td rowspan="${items.length}" class="align-middle font-weight-bold">${fechaFormateada}</td>`;
                }
                contenido += `<td>${item.medico}</td>`;
                contenido += `<td><span class="badge badge-${item.turno === 'mañana' ? 'primary' : 'danger'}">${item.turno.toUpperCase()}</span></td>`;
                contenido += `<td>${item.consultorio || 'No asignado'}</td>`;
                contenido += '</tr>';
            });
        });
        
        contenido += '</tbody>';
        contenido += '</table>';
        contenido += '</div>';
    }

    // Mostrar el modal
    $('#contenidoProgramacionServicio').html(contenido);
    $('#modalProgramacionServicio').modal('show');
}

</script>