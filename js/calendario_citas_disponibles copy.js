document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar_disponibilidad');
    console.log(calendarEl); // Verificar si el contenedor está presente
    let eventosDisponibles = []; // Lista temporal de horarios cargados
    let servicio_id = null; // Variable de control para carga manual

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // Puedes usar dayGridMonth también
        locale: 'es',    
        allDaySlot: false,
        slotDuration: '00:30:00', // Intervalos de 30 minutos
        slotLabelInterval: '00:30',
        slotMinTime: '07:30:00',
        slotMaxTime: '20:00:00',
        selectable: true,
        height: 'auto',
        nowIndicator: false,

        /*dateClick: function(info) {
            Swal.fire({
                title: '¿Nuevo evento?',
                text: `Has hecho clic en: ${info.dateStr}`,
                icon: 'info'
            });
            },  */

        // Evento al cargar todos los eventos (horarios)
        events: function (info, successCallback, failureCallback) {
             // No hacer nada si no se ha activado manualmente desde botón
            const servicio_id = document.getElementById('servicio_select').value;
            if (!servicio_id) {
                successCallback([]);
                return;
            }
            
            const servicioId = document.getElementById('servicio_select').value;
            const fechaMes = document.getElementById('mes_cita').value; // formato YYYY-MM
            
            console.log('Cargando eventos para servicio:', servicioId, 'y mes:', fechaMes);

            if (!servicioId || !fechaMes) {
                Swal.fire('Atención', 'Debe seleccionar el servicio y el mes.', 'warning');
                return failureCallback('Parámetros incompletos');
            }

            const [anio, mes] = fechaMes.split('-');
            
            console.log('Solicitando disponibilidad con:', {
                servicioId,
                fechaMes,
                anio,
                mes
            });

            fetch(`funciones/get_horas_ocupadas_mes.php?servicio_id=${servicioId}&anio=${anio}&mes=${mes}`)
                .then(async res => {
                    const text = await res.text();
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Respuesta no es JSON válido:', text);
                        throw new Error('Respuesta inesperada del servidor');
                    }
                })
                .then(data => {
                    if (data.success) {
                        successCallback(data.data);
                        if (DEBUG_MODE && document.getElementById('debugJson')) {
                            document.getElementById('debugJsonBox').style.display = 'block';
                            document.getElementById('debugJson').value = JSON.stringify(data, null, 2);
                        }
                        
                    } else {
                        failureCallback(data.error || 'Error al obtener disponibilidad');
                    }
                })
                .catch(err => {
                    Swal.fire('Error', err.message || 'Error de red', 'error');
                    failureCallback(err);
                });
        },

        // Al hacer clic en un evento (hora)
        /*
        eventClick: function (info) {
            const evento = info.event;
            const estado = evento.extendedProps.estado;
            console.log(evento.extendedProps); // solo si DEBUG está activo

            if (estado === 'Ocupado') {
                Swal.fire('Ocupado', 'Esta hora ya está ocupada.', 'error');
                return;
            }

            const fecha = evento.extendedProps.fecha;
            const hora = evento.extendedProps.hora;
            const servicioId = document.getElementById('servicio_select').value;
            const servicioNombre = document.getElementById('servicio_select')
                .selectedOptions[0].text;

            // Verificar si ya está agregada
            const filas = document.querySelectorAll('#tabla-borrador-citas tbody tr');
            for (const fila of filas) {
                if (
                    fila.dataset.servicio === servicioId &&
                    fila.querySelector('.col-fecha').textContent === formatearFecha(fecha) &&
                    fila.querySelector('.col-hora').textContent === hora
                ) {
                    Swal.fire('Duplicado', 'Ya agregaste esta hora para este servicio.', 'info');
                    return;
                }
            }

            // Agregar a la tabla
            const nuevaFila = document.createElement('tr');
            nuevaFila.setAttribute('data-servicio', servicioId);
            nuevaFila.innerHTML = `
        <td class="col-servicio">${servicioNombre}</td>
        <td class="col-fecha">${formatearFecha(fecha)}</td>
        <td class="col-hora">${hora}</td>
        <td>
          <button type="button" class="btn btn-danger btn-sm btnEliminarFila">
            <i class="fas fa-trash-alt"></i> Quitar
          </button>
        </td>
      `;
            document.querySelector('#tabla-borrador-citas tbody').appendChild(nuevaFila);
        },
        */

        select: function(info) {
            // Verifica si el rango seleccionado se cruza con algún evento de tipo background
            const bloqueOcupado = calendar.getEvents().some(event => {
                return (
                event.display === 'background' &&
                info.start < event.end &&
                info.end > event.start
                );
            });

            if (bloqueOcupado) {
                Swal.fire('Ocupado', 'Ese horario ya está reservado. Intenta con otro.', 'error');
                return;
            }

            const fecha = info.start.toISOString().slice(0, 10);
            const hora = info.start.toTimeString().slice(0, 5);
            const servicioId = document.getElementById('servicio_select').value;
            const servicioNombre = document.getElementById('servicio_select')
                .selectedOptions[0].text;

            // Verificación de duplicados
            const filas = document.querySelectorAll('#tabla-borrador-citas tbody tr');
            for (const fila of filas) {
                if (
                fila.dataset.servicio === servicioId &&
                fila.querySelector('.col-fecha').textContent === formatearFecha(fecha) &&
                fila.querySelector('.col-hora').textContent === hora
                ) {
                Swal.fire('Duplicado', 'Ya agregaste esta hora para este servicio.', 'info');
                return;
                }
            }

            // Confirmación con SweetAlert
            Swal.fire({
                title: '¿Agregar cita?',
                html: `Servicio: <b>${servicioNombre}</b><br>Fecha: <b>${formatearFecha(fecha)}</b><br>Hora: <b>${hora}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, agregar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                const nuevaFila = document.createElement('tr');
                nuevaFila.setAttribute('data-servicio', servicioId);
                nuevaFila.innerHTML = `
                    <td class="col-servicio">${servicioNombre}</td>
                    <td class="col-fecha">${formatearFecha(fecha)}</td>
                    <td class="col-hora">${hora}</td>
                    <td>
                    <button type="button" class="btn btn-danger btn-sm btnEliminarFila">
                        <i class="fas fa-trash-alt"></i> Quitar
                    </button>
                    </td>
                `;
                document.querySelector('#tabla-borrador-citas tbody').appendChild(nuevaFila);
                }
            });
            }
    });

    // Botón para cargar el calendario
    document.getElementById('btnBuscarDisponibilidad').addEventListener('click', () => {
        calendar.removeAllEvents(); // Limpiar anteriores
        calendar.refetchEvents();   // Volver a consultar
    });

    // Eliminar fila desde tabla
    document.querySelector('#tabla-borrador-citas tbody').addEventListener('click', e => {
        if (e.target.closest('.btnEliminarFila')) {
            e.target.closest('tr').remove();
        }
    });

    // Formato helper
    function formatearFecha(fechaIso) {
        const partes = fechaIso.split("-");
        return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    // Inicializar calendario
    calendar.render();
});