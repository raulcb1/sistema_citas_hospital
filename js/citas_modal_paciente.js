// citas_modal_paciente.js
// Funcionalidad: Mostrar detalles de una cita (paciente + servicios) en un modal.
// Este archivo se puede reutilizar en páginas que necesiten visualizar una cita registrada.

$(document).ready(function () {

  // Evento delegado: cuando se hace clic en un botón con clase .btnVer
  $('#tablaCitas').on('click', '.btnVer', function () {
    const citaId = $(this).data('id');

    // Llamada al backend que devuelve los datos de la cita y sus servicios
    fetch('funciones/obtener_datos_citas_imprimir.php?id=' + citaId)
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          Swal.fire('Error', data.error || 'No se pudo obtener los datos de la cita', 'error');
          return;
        }

        const cita = data.cita;
        const servicios = data.servicios;
        const usuario = data.usuario;

        // Llenar campos del modal con los datos del paciente y cita
        $('#modalVerCita #verPacienteNombre').text(cita.nombre + ' ' + cita.apellido_p + ' ' + cita.apellido_m);
        $('#modalVerCita #verPacienteDNI').text(cita.dni);
        $('#modalVerCita #verPacienteTelefono').text(cita.telefono || '—');
        $('#modalVerCita #verCitaFecha').text(cita.fecha_cita);
        $('#modalVerCita #verCitaMotivo').text(cita.motivo || '—');
        $('#modalVerCita #verCitaId').text(cita.id);
        $('#modalVerCita #verCitaEstado').text(cita.estado);
        $('#modalVerCita #verUsuarioGenerador').text(usuario.nombre + ' ' + usuario.apellido_p);
        $('#footer_modalVerCita #btnImprimirComprobante').attr('href', `paginas/citas2/cita_imprimir.php?id=`+cita.id);

        // Limpiar tabla de servicios
        const tbody = $('#tablaServiciosCita tbody');
        tbody.empty();

        servicios.forEach(serv => {
          const fila = `
            <tr>
              <td>${serv.servicio}</td>
              <td>${serv.fecha_cita}</td>
              <td>${serv.hora}</td>
              <!-- <td class="text-center">
                <button class="btn btn-sm btn-danger btnDesactivarServicio" data-servicio="${serv.servicio}" data-id="${serv.id}">
                  <i class="fas fa-trash"></i>
                </button>
              </td> -->
            </tr>`;
          tbody.append(fila);
        });

        // Mostrar el modal
        $('#modalVerCita').modal('show');
      })
      .catch(error => {
        console.error('Error al obtener datos de la cita:', error);
        Swal.fire('Error', 'Error al obtener datos del servidor.', 'error');
      });
  });
});