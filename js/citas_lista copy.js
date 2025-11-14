// citas_lista.js
// Función: Maneja el DataTable de la página citas_lista.php, con filtros manuales y acciones

$(document).ready(function () {
  // 1. Inicialización de la tabla con DataTables
  const tabla = $('#tablaCitas').DataTable({
    processing: true,         // Muestra indicador "Procesando..."
    serverSide: true,         // Habilita procesamiento en el servidor
    responsive: true,         // Se adapta a pantallas pequeñas
    pageLength: 10,           // Número de registros por página
    order: [[0, 'desc']],     // Orden por defecto: ID descendente
    ajax: {
      url: 'funciones/get_citas_lista.php',
      type: 'GET',
      data: function (d) {
        // Añadir los valores de los filtros al request GET
        d.dni = $('#filtro_dni').val();
        d.fecha = $('#filtro_fecha').val();
        d.servicio_id = $('#filtro_servicio').val();
        d.estado = $('#filtro_estado').val();
      }
    },
    columns: [
      { data: 'id' },
      { data: 'fecha_cita' },
      { data: 'paciente' },
      { data: 'dni' },
      { data: 'motivo' },
      //{ data: 'telefono' },
      { data: 'estado' },
      {
        data: null,
        orderable: false,
        render: function (data, type, row) {
          // Botones de acción por cada cita
          return `
            <button class="btn btn-sm btn-info btnVer" data-id="${row.id}">
              <i class="fas fa-eye"></i> Ver
            </button>
            <button class="btn btn-sm btn-warning btnEditar" data-id="${row.id}">
              <i class="fas fa-edit"></i> Editar
            </button>
            <button class="btn btn-sm btn-danger btnCancelar" data-id="${row.id}">
              <i class="fas fa-times"></i> Cancelar
            </button>
          `;
        }
      }
    ],
    language: {
      url: 'plugins/datatables/es_es.json' // Asegúrate de tener este archivo para español
    }
  });

  // 2. Buscar manual (refresca la tabla)
  $('#btnBuscar').on('click', function () {
    tabla.ajax.reload(); // Recarga la tabla con los nuevos filtros
  });

  // 3. Limpiar filtros
  $('#btnLimpiar').on('click', function () {
    $('#formFiltros')[0].reset(); // Limpia formulario
    tabla.ajax.reload();          // Recarga con datos sin filtros
  });


// 🔁 [DESACTIVADO] Acción antigua: Redirigir a página de edición (ahora se usa modal)
//// $('#tablaCitas').on('click', '.btnEditar', function () {
////   const id = $(this).data('id');
////   window.location.href = `citas_editar.php?id=${id}`;
//// });


  // 6. Acción: Cancelar cita
  $('#tablaCitas').on('click', '.btnCancelar', function () {
    const id = $(this).data('id');

    Swal.fire({
      title: '¿Cancelar esta cita?',
      text: 'Esta acción es irreversible. ¿Desea continuar?',
      icon: 'warning',
      input: 'text',
      inputLabel: 'Motivo de cancelación',
      inputPlaceholder: 'Ingrese el motivo...',
      showCancelButton: true,
      confirmButtonText: 'Sí, cancelar',
      cancelButtonText: 'No'
    }).then(result => {
      if (result.isConfirmed) {
        const motivo = result.value.trim();

        if (!motivo) {
          Swal.fire('Atención', 'Debe ingresar un motivo.', 'warning');
          return;
        }

        // Aquí harías un fetch o AJAX a un backend para cancelar la cita
        fetch('funciones/cancelar_cita.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id, motivo })
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire('Cancelado', 'La cita fue cancelada.', 'success');
              tabla.ajax.reload();
            } else {
              Swal.fire('Error', data.error || 'No se pudo cancelar.', 'error');
            }
          })
          .catch(() => {
            Swal.fire('Error', 'Error de red.', 'error');
          });
      }
    });
  });
});