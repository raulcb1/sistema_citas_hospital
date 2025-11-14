// Función principal: consulta al servidor las horas disponibles por servicio y rango de fechas
async function cargarHorasDisponiblesRango(servicioId, fechaInicio, fechaFin) {
  // Validar que todos los parámetros estén definidos
  if (!servicioId || !fechaInicio || !fechaFin) {
    Swal.fire('Error', 'Debe seleccionar servicio y rango de fechas.', 'warning');
    return;
  }

  try {
    // Hacer la llamada al endpoint PHP con los parámetros requeridos
    const response = await fetch(`funciones/get_horas_disponibles_rango.php?servicio_id=${servicioId}&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`);
    const result = await response.json();

    // Si hubo un error en la respuesta, mostrarlo
    if (!result.success) {
      let mensaje = result.error || 'No se pudo cargar disponibilidad.';
      if (result.debug) {
        mensaje += '<br><small style="color:gray">' + result.debug + '</small>';
      }
      Swal.fire('Error', mensaje, 'error');
      return;
    }

    const datos = result.data;
    const contenedor = document.getElementById('contenedorHorasDisponibles');
    contenedor.innerHTML = '';

    // Si no hay datos devueltos, mostrar mensaje informativo
    if (Object.keys(datos).length === 0) {
      contenedor.innerHTML = '<div class="alert alert-info">No hay horarios disponibles en el rango indicado.</div>';
      return;
    }

    // Construir dinámicamente las tarjetas con fechas y turnos
    let html = '';
    for (const [fecha, turnos] of Object.entries(datos)) {
      // Formatear fecha en formato legible (español)
      const fechaFormat = new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
      });

      html += `
        <div class="card mb-2">
          <div class="card-header bg-secondary text-white">${fechaFormat}</div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Mañana:</h6>
                ${renderizarHoras(fecha, 'mañana', turnos.mañana)}
              </div>
              <div class="col-md-6">
                <h6>Tarde:</h6>
                ${renderizarHoras(fecha, 'tarde', turnos.tarde)}
              </div>
            </div>
          </div>
        </div>`;
    }

    // Insertar el HTML generado en el contenedor
    contenedor.innerHTML = html;

  } catch (error) {
    console.error('Error cargando horas:', error);
    Swal.fire('Error', 'Error al consultar disponibilidad34123123.', 'error');
  }
}

// Función auxiliar: genera botones para cada hora disponible
function renderizarHoras(fecha, turno, horas) {
  if (!horas || horas.length === 0) {
    return '<p class="text-muted">Sin disponibilidad</p>';
  }

  // Por cada hora, crear un botón que al hacer clic seleccionará esa hora
  return horas.map(hora => `
    <button type="button" class="btn btn-sm btn-outline-primary m-1"
      onclick="seleccionarHoraDisponible('${fecha}', '${turno}', '${hora}')">
      ${hora}
    </button>
  `).join('');
}

// Función que se llama cuando el usuario hace clic en una hora disponible
function seleccionarHoraDisponible(fecha, turno, hora) {
  // Asignar la fecha y el turno al formulario principal de citas
  document.getElementById('fecha_cita').value = fecha;
  document.getElementById('turno_cita').value = turno;

  // Llenar el select de hora con la hora seleccionada
  const selectHora = document.getElementById('hora_cita');
  selectHora.innerHTML = `<option value="${hora}" selected>${hora}</option>`;

  // Mostrar mensaje de confirmación
  Swal.fire('Hora seleccionada', `Cita el ${fecha} a las ${hora} (${turno})`, 'success');
}

// Asignar evento al botón de búsqueda cuando el documento esté listo
document.addEventListener('DOMContentLoaded', () => {
  const btnBuscar = document.getElementById('btnBuscarDisponibilidad');

  if (btnBuscar) {
    btnBuscar.addEventListener('click', () => {
      // Obtener los valores del formulario
      const servicioId = document.getElementById('servicio_select').value;
      const fechaInicio = document.getElementById('fecha_inicio').value;
      const fechaFin = document.getElementById('fecha_fin').value;

      // Llamar a la función principal con esos valores
      cargarHorasDisponiblesRango(servicioId, fechaInicio, fechaFin);
    });
  }
});