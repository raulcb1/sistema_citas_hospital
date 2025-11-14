// programacion_horarios.js
// Funcionalidad: Muestra en un calendario los horarios médicos programados según servicio, médico y mes.

document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendario_horarios');
  const selectMedico = document.getElementById('filtro_medico');
  const selectServicio = document.getElementById('filtro_servicio');
  const inputMes = document.getElementById('filtro_mes');

  // 🗓 Asignar mes actual si no hay valor
  if (!inputMes.value) {
    const hoy = new Date();
    const mesActual = hoy.toISOString().slice(0, 7); // Formato yyyy-mm
    inputMes.value = mesActual;
  }

  // 📅 Inicializar FullCalendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
    //initialView: 'timeGridWeek',
    locale: 'es',
    height: 'auto',
    slotDuration: '00:30:00',
    slotMinTime: '07:30:00',
    slotMaxTime: '19:30:00',
    allDaySlot: false,
    //eventBackgroundColor: 'green',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'timeGridWeek,timeGridDay,dayGridMonth,listWeek,turnoDia'
    },
    views: {
      turnoDia: {
        type: 'timeGrid',
        duration: { days: 1 },
        buttonText: 'Turnos Día',
        slotLabelFormat: {
          hour: 'numeric',
          minute: '2-digit',
          hour12: true
        }
      }
    },

    events: [], // Se cargará dinámicamente
    initialView: 'dayGridMonth'
  });

  calendar.render();

  // 🔍 Función para cargar los horarios desde el backend
  function cargarHorarios() {
    const medicoId = selectMedico.value;
    const servicioId = selectServicio.value;
    const mes = inputMes.value;

    if (!mes) {
      Swal.fire('Atención', 'Debe seleccionar al menos un mes.', 'warning');
      return;
    }

    // Construir URL con los parámetros disponibles
    let url = `funciones/get_horarios_medicos.php?mes=${encodeURIComponent(mes)}`;
    if (medicoId) url += `&medico_id=${encodeURIComponent(medicoId)}`;
    if (servicioId) url += `&servicio_id=${encodeURIComponent(servicioId)}`;

    // 📨 Llamar al backend
    fetch(url)
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          Swal.fire('Error', data.error || 'No se encontraron horarios.', 'error');
          return;
        }

        // 🧼 Limpiar eventos previos
        calendar.removeAllEvents();

        // ➕ Cargar nuevos eventos
        data.eventos.forEach(evento => {
          calendar.addEvent(evento);
        });

        // 📍 Mover calendario al mes seleccionado
        const fecha = new Date(mes + '-01');
        calendar.gotoDate(fecha);
      })
      .catch(err => {
        console.error('Error al obtener horarios:', err);
        Swal.fire('Error', 'Ocurrió un error al cargar los horarios.', 'error');
      });
  }

  // ▶️ Botón de búsqueda manual
  document.getElementById('btnBuscarHorarios').addEventListener('click', function () {
    cargarHorarios();
  });

  // 🔄 Cargar automáticamente al inicio
  cargarHorarios();
});