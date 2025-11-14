document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('btnRegistrarCitas').addEventListener('click', async () => {
    // Obtener ID del paciente desde campo oculto
    const pacienteId = document.getElementById('paciente_id')?.value;
    if (!pacienteId) {
      Swal.fire('Error', 'Debe buscar y seleccionar un paciente antes de registrar citas.', 'warning');
      return;
    }

    // Obtener motivo general para la cita maestra
    const motivo = document.getElementById('motivo_cita')?.value || '';

    // Obtener todas las filas de la tabla de citas (borrador)
    const filas = document.querySelectorAll('#tabla-borrador-citas tbody tr');
    if (filas.length === 0) {
      Swal.fire('Atención', 'Debe agregar al menos un servicio a la cita.', 'info');
      return;
    }

    // Construir array de servicios asignados a la cita
    const citas = [];
    filas.forEach(fila => {
      const servicioId = fila.dataset.servicio;
      const fecha = fila.querySelector('.col-fecha')?.textContent.trim();
      const hora = fila.querySelector('.col-hora')?.textContent.trim();

      // Validar datos mínimos
      if (servicioId && fecha && hora) {
        citas.push({
          servicio_ups_id: servicioId,
          fecha_cita: fecha,
          hora: hora
        });
      }
    });

    if (citas.length === 0) {
      Swal.fire('Error', 'No se pudo leer correctamente las citas del borrador.', 'error');
      return;
    }

    // Confirmar antes de registrar
    const confirmar = await Swal.fire({
      title: '¿Confirmar registro?',
      html: `Se guardará 1 cita con ${citas.length} servicios.`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Registrar'
    });

    if (!confirmar.isConfirmed) return;

    // Si DEBUG_MODE está activo, mostrar datos que se enviarán
    if (DEBUG_MODE && typeof debug_log === 'function') {
      debug_log('➡ Enviando a guardar_citas.php:\n' + JSON.stringify({
        paciente_id: pacienteId,
        motivo: motivo,
        citas: citas
      }, null, 2));
    }

  try {
      const response = await fetch('funciones/guardar_citas.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          paciente_id: pacienteId,
          motivo: motivo,
          citas: citas
        })
      });

      const result = await response.json();

      // Si DEBUG_MODE está activo, mostrar datos que se RECIBEN
      if (DEBUG_MODE && typeof debug_log === 'function') {
        debug_log('⬅ Respuesta de guardar_citas.php:\n' + JSON.stringify(result, null, 2));
      }

      if (result.success) {
        Swal.fire({
          title: 'Cita registrada',
          html: `ID: ${result.cita_id}<br>¿Desea imprimir el comprobante?`,
          icon: 'success',
          showCancelButton: true,
          confirmButtonText: 'Imprimir',
          cancelButtonText: 'Cerrar'
        }).then(r => {
          if (r.isConfirmed) {
            window.open(`cita_imprimir.php?id=${result.cita_id}`, '_blank');
          }
        });


        if (DEBUG_MODE && result.debug) {
          debug_log("DEBUG desde PHP:\n" + result.debug.join('\n'));
        }


        // Limpiar formulario
        document.getElementById('formCitasMultiples').reset();
        document.getElementById('resultadoPaciente').innerHTML = '';
        document.querySelector('#tabla-borrador-citas tbody').innerHTML = '';
        document.getElementById('motivo_cita').value = '';
      } else {
        const errorMsg = result.error || 'Ocurrió un error al registrar la cita.';
        Swal.fire('Error', errorMsg, 'error');
      }

      if (DEBUG_MODE && result.debug) {
        debug_log("DEBUG PHP:\n" + result.debug.join('\n'));
      }


    } catch (error) {
      console.error('Error al registrar cita:', error);
      Swal.fire('Error', 'Error inesperado. Consulte consola.', 'error');

      if (DEBUG_MODE && typeof debug_log === 'function') {
        debug_log('❌ Error en fetch o servidor:\n' + error);
      }
    }
  });
});