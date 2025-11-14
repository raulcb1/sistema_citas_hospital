// Script para registrar paciente desde modal
$(document).ready(function () {
  $('#btnGuardarPaciente').on('click', function () {
    const form = $('#formNuevoPaciente');
    const formData = form.serialize();

    const dni = $('#dni_nuevo').val().trim();
    const nombre = $('#nombre_nuevo').val().trim();
    const apellido_p = $('#apellido_p_nuevo').val().trim();

    if (!dni || !nombre || !apellido_p) {
      Swal.fire('Campos obligatorios', 'DNI, nombre y apellido paterno son obligatorios.', 'warning');
      return;
    }

    $.ajax({
      url: 'funciones/guardar_paciente.php',
      method: 'POST',
      data: formData,
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          const paciente = response.data;

          $('#modalNuevoPaciente').modal('hide');
          form[0].reset();

          $('#resultadoPaciente').html(`
            <div class="card bg-light">
              <div class="card-header">
                <strong>Paciente registrado:</strong>
              </div>
              <div class="card-body">
                <p><strong>Nombre:</strong> ${paciente.nombre_completo}</p>
                <p><strong>DNI:</strong> ${dni}</p>
                <input type="hidden" id="paciente_id" name="paciente_id" value="${paciente.id}">
              </div>
            </div>
          `);

          Swal.fire('¡Éxito!', 'Paciente registrado correctamente.', 'success');
        } else {
          Swal.fire('Error', response.error || 'No se pudo registrar el paciente.', 'error');
        }
      },
      error: function (xhr, status, error) {
        console.error('Error AJAX:', error);
        Swal.fire('Error', 'Error al registrar el paciente.', 'error');
      }
    });
  });
});