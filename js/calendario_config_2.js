//5
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar_disponibilidad');
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    locale: 'es',
    allDaySlot: false,
    slotMinTime: '07:30:00',
    slotMaxTime: '20:00:00',
    slotDuration: '00:30:00',
    selectable: true,
    nowIndicator: false,
    height: 'auto',

    // Consulta de eventos combinados (citas ocupadas + días programados)
    events: async function (info, successCallback, failureCallback) {
      const servicioId = document.getElementById('servicio_select').value;
      const fechaMes = document.getElementById('mes_cita').value;

      if (!servicioId || !fechaMes) {
        Swal.fire('Atención', 'Debe seleccionar un servicio y un mes.', 'warning');
        return failureCallback('Faltan parámetros');
      }

      const [anio, mes] = fechaMes.split('-');

      try {
        const [citas, programacion] = await Promise.all([
          obtenerCitasOcupadas(info, servicioId, anio, mes),
          obtenerDiasProgramados(info, servicioId, anio, mes)
        ]);

        const eventos = [...programacion, ...citas];

        if (DEBUG_MODE) debug_log('✅ Eventos combinados:\n' + JSON.stringify(eventos, null, 2));
        successCallback(eventos);
      } catch (error) {
        failureCallback(error.message || 'Error al cargar eventos');
      }
    },

    // Al seleccionar un intervalo (clic en hora)
    select: function (info) {
      // Verificar si se pisa un bloque de fondo (turno no programado)
      const bloqueNoPermitido = calendar.getEvents().some(event => {
        return event.display === 'background' && (
          info.start < event.end && info.end > event.start
        );
      });

      if (!bloqueNoPermitido) {
        Swal.fire('No disponible', 'Esta hora no está dentro de un turno programado.', 'error');
        return;
      }

      const fecha = info.start.toISOString().slice(0, 10);
      const hora = info.start.toTimeString().slice(0, 5);
      const servicioId = document.getElementById('servicio_select').value;
      const servicioNombre = document.getElementById('servicio_select').selectedOptions[0].text;

      // Evitar duplicados en la tabla
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

      // Confirmación
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

  // Botón para recargar disponibilidad
  document.getElementById('btnBuscarDisponibilidad').addEventListener('click', () => {
    calendar.removeAllEvents();
    calendar.refetchEvents();
  });

  // Eliminar citas de la tabla
  document.querySelector('#tabla-borrador-citas tbody').addEventListener('click', e => {
    if (e.target.closest('.btnEliminarFila')) {
      e.target.closest('tr').remove();
    }
  });

  // Helper para fecha en formato DD/MM/YYYY
  function formatearFecha(fechaIso) {
    const partes = fechaIso.split("-");
    return `${partes[2]}/${partes[1]}/${partes[0]}`;
  }

  calendar.render();
});