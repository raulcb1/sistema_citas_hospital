document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar_disponibilidad');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        slotMinTime: '07:30:00',
        slotMaxTime: '20:00:00',
        slotDuration: '00:30:00',
        height: 'auto',
        allDaySlot: false,
        nowIndicator: false,

        // Fuente de eventos: días programados
        events: function (info, successCallback, failureCallback) {
            const servicioId = document.getElementById('servicio_select').value;
            const fechaMes = document.getElementById('mes_cita').value;

            if (!servicioId || !fechaMes) {
                Swal.fire('Atención', 'Seleccione un servicio y mes.', 'warning');
                return failureCallback('Parámetros incompletos');
            }

            const [anio, mes] = fechaMes.split('-');

            fetch(`funciones/get_dias_programados_mes.php?servicio_id=${servicioId}&anio=${anio}&mes=${mes}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        successCallback(data.data);
                    } else {
                        failureCallback(data.error || 'Error al cargar eventos');
                    }

                    if (DEBUG_MODE && typeof debug_log === 'function') {
                        debug_log('🎯 Eventos de programación recibidos:\n' + JSON.stringify(data, null, 2));
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Error de red o servidor', 'error');
                    failureCallback(err);
                });
        }
    });

    // Botón para cargar los días programados
    document.getElementById('btnBuscarDisponibilidad').addEventListener('click', () => {
        calendar.removeAllEvents();
        calendar.refetchEvents();
    });

    calendar.render();
});