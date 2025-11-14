// calendario_config.js
document.addEventListener('DOMContentLoaded', () => {

  // Referencia al contenedor del calendario
  const calendarEl = document.getElementById('calendar_disponibilidad');

  // Crear la instancia del calendario FullCalendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',     // Vista inicial: semana con horarios por bloques
    locale: 'es',                    // Idioma español
    allDaySlot: false,               // Oculta la fila "todo el día"
    slotDuration: '00:30:00',        // Intervalos de 30 minutos por celda
    slotLabelInterval: '00:30',      // Mostrar etiquetas de tiempo cada 30 minutos
    slotMinTime: '07:30:00',         // Hora mínima visible
    slotMaxTime: '20:00:00',         // Hora máxima visible
    selectable: true,                // Permitir seleccionar un horario
    height: 'auto',                  // Altura dinámica
    nowIndicator: false,             // No mostrar línea de "hora actual"

    // 🔄 Cargar eventos dinámicamente
    events: async function (info, successCallback, failureCallback) {
      const servicioId = document.getElementById('servicio_select')?.value;
      const fechaMes = document.getElementById('mes_cita')?.value;

      // Si no hay datos, no cargar eventos
      if (!servicioId || !fechaMes) {
        successCallback([]); // No mostrar error aún
        return;
      }

      const [anio, mes] = fechaMes.split('-');

      try {
        // ⏳ Cargar citas ocupadas y días programados al mismo tiempo
        const [citas, programados] = await Promise.all([
          obtenerCitasOcupadas(info, servicioId, anio, mes),
          obtenerDiasProgramados(info, servicioId, anio, mes)
        ]);

        // ✅ Mostrar eventos en el calendario
        successCallback([...citas, ...programados]);

        // 🐞 Mostrar en textarea JSON si está el modo debug activado
        if (DEBUG_MODE && document.getElementById('debugJson')) {
          document.getElementById('debugJsonBox').style.display = 'block';
          document.getElementById('debugJson').value = JSON.stringify([...citas, ...programados], null, 2);
        }

      } catch (err) {
        failureCallback('Error al cargar eventos: ' + err.message);
        if (DEBUG_MODE) debug_log('❌ Error en calendario_config.js: ' + err.message);
      }
    },

    // ✅ Al seleccionar una celda en el calendario
    select: function (info) {

      // 🚫 Verificar si el rango está fuera de los bloques programados
      const bloqueNoPermitido = calendar.getEvents().some(event => {
        return event.display === 'background' && (
          info.start < event.end && info.end > event.start
        );
      });

      if (!bloqueNoPermitido) {
        Swal.fire('No disponible', 'Esta hora no está dentro de un turno programado.', 'error');
        return;
      }

      // Extraer info de la selección
      const fecha = info.start.toISOString().slice(0, 10);
      const hora = info.start.toTimeString().slice(0, 5);
      const servicioId = document.getElementById('servicio_select').value;
      const servicioNombre = document.getElementById('servicio_select')?.selectedOptions[0]?.text;

      // ❌ Verificar si ya se agregó esa cita
      const yaExiste = Array.from(document.querySelectorAll('#tabla-borrador-citas tbody tr')).some(fila =>
        fila.dataset.servicio === servicioId &&
        fila.querySelector('.col-fecha').textContent === formatearFecha(fecha) &&
        fila.querySelector('.col-hora').textContent === hora
      );

      if (yaExiste) {
        Swal.fire('Duplicado', 'Ya agregaste esta hora para este servicio.', 'info');
        return;
      }

      // ✅ Confirmación visual al usuario
      Swal.fire({
        title: '¿Agregar cita?',
        html: `Servicio: <b>${servicioNombre}</b><br>Fecha: <b>${formatearFecha(fecha)}</b><br>Hora: <b>${hora}</b>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, agregar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Agregar nueva fila a la tabla de citas pendientes
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
            </td>`;
          document.querySelector('#tabla-borrador-citas tbody').appendChild(nuevaFila);
        }
      });
    },

    // 🎯 Personalización de los eventos al montarse en el DOM
    eventDidMount: function(info) {
      if (info.event.extendedProps.tipo === 'programado') {
        const medico   = info.event.extendedProps.medico   || '—';
        const servicio = info.event.extendedProps.servicio || '—';

        const horaInicio = new Date(info.event.start).toLocaleTimeString([], {
          hour: '2-digit',
          minute: '2-digit'
        });

        const horaFin = new Date(info.event.end).toLocaleTimeString([], {
          hour: '2-digit',
          minute: '2-digit'
        });

        // Contenido para el tooltip
        const contenidoTooltip = `
          Servicio: ${servicio}
          \n Médico: ${medico}
          \n Horario: ${horaInicio} – ${horaFin}
        `;

        // Configurar tooltip (usando Bootstrap)
        info.el.setAttribute('title', contenidoTooltip);
        info.el.setAttribute('data-toggle', 'tooltip');
        $(info.el).tooltip(); // Activar Bootstrap tooltip
      }
    }
  });

  // 🔁 Botón para actualizar eventos del calendario
  document.getElementById('btnBuscarDisponibilidad').addEventListener('click', () => {
    calendar.removeAllEvents();  // Limpiar eventos anteriores
    calendar.refetchEvents();   // Recargar eventos desde el servidor
  });

  // 🗑️ Eliminar cita desde la tabla
  document.querySelector('#tabla-borrador-citas tbody').addEventListener('click', e => {
    if (e.target.closest('.btnEliminarFila')) {
      e.target.closest('tr').remove();
    }
  });

  // 🟢 Renderizar el calendario en pantalla
  calendar.render();

  // ⚠️ Inicializar tooltips de Bootstrap
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  // Helper para formatear fecha tipo DD/MM/YYYY
  function formatearFecha(fechaIso) {
    const partes = fechaIso.split("-");
    return `${partes[2]}/${partes[1]}/${partes[0]}`;
  }

});
