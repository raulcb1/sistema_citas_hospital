// Script para enviar la lista de citas al backend y registrarlas
$(document).ready(function () {
  $('#btnRegistrarCitas').on('click', async function () {
    // Obtener ID del paciente seleccionado previamente
    const pacienteId = $('#paciente_id').val();

    if (!pacienteId) {
      Swal.fire('Error', 'Debe seleccionar o registrar un paciente.', 'warning');
      return;
    }

    // Obtener lista de citas desde la tabla
    const citas = [];
    $('#tabla-borrador-citas tbody tr').each(function () {
      const fila = $(this);
      const servicioId = fila.data('servicio');
      const fecha = fila.find('.col-fecha').text().trim();
      const turno = fila.find('.col-turno').data('valor');
      const hora = fila.find('.col-hora').text().trim();
      const motivo = fila.find('.col-motivo').text().trim();

      citas.push({
        servicio_ups_id: servicioId,
        fecha_cita: fecha,
        turno: turno,
        hora: hora,
        motivo: motivo
      });
    });

    if (citas.length === 0) {
      Swal.fire('Error', 'No hay citas en la lista para registrar.', 'info');
      return;
    }

    // Confirmación del usuario
    const confirmacion = await Swal.fire({
      title: '¿Confirmar registro?',
      html: `Se registrarán <strong>${citas.length}</strong> citas para el paciente.`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, registrar',
      cancelButtonText: 'Cancelar'
    });

    if (!confirmacion.isConfirmed) return;

    try {
      // Enviar al backend vía fetch
      const response = await fetch('funciones/guardar_citas.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ paciente_id: pacienteId, citas: citas })
      });

      const result = await response.json();

      if (result.success) {
        let mensaje = `Se registraron ${result.total_registradas} citas correctamente.`;
        if (result.errores.length > 0) {
          mensaje += '<br><br>Las siguientes citas no se pudieron registrar:<ul>';
          result.errores.forEach(e => mensaje += `<li>${e}</li>`);
          mensaje += '</ul>';
        }

        Swal.fire('Éxito', mensaje, 'success');

        // Opcional: limpiar la tabla y formulario
        $('#tabla-borrador-citas tbody').empty();
        $('#formCitasMultiples')[0].reset();
        $('#paciente_id').val('');
        $('#resultadoPaciente').empty();
      } else {
        Swal.fire('Error', result.error || 'No se pudieron registrar las citas.', 'error');
      }
    } catch (error) {
      console.error('Error al registrar citas:', error);
      Swal.fire('Error', 'Error de conexión con el servidor.', 'error');
    }
  });
});