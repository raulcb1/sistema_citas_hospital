// citas_modal_editar_serv.js
// Funcionalidad: Mostrar los servicios de una cita en un modal para permitir su edición o eliminación.

$(document).ready(function () {

    // Evento delegado: Clic en botón "Editar Servicios"
    $('#tablaCitas').on('click', '.btnEditar', function () {
        const citaId = $(this).data('id');

        fetch('funciones/obtener_datos_citas_imprimir.php?id=' + citaId)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    Swal.fire('Error', data.error || 'No se pudo obtener los datos de la cita', 'error');
                    return;
                }

                const cita = data.cita;
                const servicios = data.servicios;

                // Mostrar ID y fecha en el encabezado del modal
                $('#modalEditarServicios #editarCitaId').text(cita.id);
                $('#modalEditarServicios #editarCitaFecha').text(cita.fecha_cita);
                $('#modalEditarServicios #editarPacienteNombre').text(`${cita.nombre} ${cita.apellido_p} ${cita.apellido_m}`);

                // Limpiar tabla
                const tbody = $('#tablaEditarServicios tbody');
                tbody.empty();

                // Poblar tabla con servicios actuales
                servicios.forEach((servicio, index) => {
                let acciones = '';

                // Solo mostrar los botones si el servicio está pendiente y activo
                const estadoValido = servicio.estado === 'pendiente' && servicio.activo == 1;

                if (estadoValido) {
                    acciones = `
                    <button class="btn btn-sm btn-warning btnEditarServicio" data-index="${index}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btnEliminarServicio" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>`;
                }

                const fila = `
                    <tr>
                    <td>${servicio.servicio}</td>
                    <td>${servicio.fecha_cita}</td>
                    <td>${servicio.hora}</td>
                    <td>${servicio.estado}</td>
                    <td class="text-center">${acciones}</td>
                    </tr>`;

                tbody.append(fila);
                });


                // Guardar temporalmente datos en el modal para acceso posterior
                $('#modalEditarServicios').data('cita-id', cita.id);
                $('#modalEditarServicios').data('servicios', servicios);

                // Mostrar modal
                $('#modalEditarServicios').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar la información.', 'error');
            });
    });

    // Evento: Eliminar servicio
    $('#tablaEditarServicios').on('click', '.btnEliminarServicio', async function () {
        const index = $(this).data('index');
        const servicios = $('#modalEditarServicios').data('servicios') || [];
        const citaId = $('#modalEditarServicios').data('cita-id');
        const servicio = servicios[index];

        // 🔍 Contar servicios activos y atendidos
        const activos = servicios.filter(s => s.estado === 'pendiente' && s.activo == 1);
        const atendidos = servicios.filter(s => s.estado === 'atendido');

        const esUltimoActivo = activos.length === 1 && activos[0].id === servicio.id;

        let textoConfirmacion = `¿Desea eliminar este servicio?\n${servicio.servicio} - ${servicio.fecha_cita} ${servicio.hora}`;
        if (esUltimoActivo) {
            textoConfirmacion += atendidos.length > 0
                ? '\n⚠️ Al eliminar este último servicio pendiente, la cita será finalizada.'
                : '\n⚠️ Al eliminar este último servicio pendiente, la cita será cancelada.';
        }

        // 🔒 Cerrar el modal para evitar conflicto de foco con el input de Swal
        $('#modalEditarServicios').modal('hide');

        // Esperar a que se cierre completamente el modal
        setTimeout(async () => {
            const { isConfirmed, value: motivo } = await Swal.fire({
                title: 'Confirmar eliminación',
                text: textoConfirmacion,
                icon: 'warning',
                input: 'text',
                inputLabel: 'Motivo',
                inputPlaceholder: 'Ingrese motivo de eliminación',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false,
                focusConfirm: false,
                inputValidator: (value) => {
                    if (!value.trim()) return 'Debe ingresar un motivo.';
                }
            });

            if (!isConfirmed) return;



            const payload = {
                id_asignacion: servicio.id,
                motivo: motivo,
                cita_id: citaId,
                es_ultima: esUltimoActivo,
                tiene_atendidos: atendidos.length > 0
            };

            console.log('Payload enviado a eliminar_servicio.php:', payload);

            const res = await fetch('funciones/eliminar_servicio.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });






            // 🔄 Enviar al backend
            try {
                const res = await fetch('funciones/eliminar_servicio.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id_asignacion: servicio.id,
                        motivo: motivo,
                        cita_id: citaId,
                        es_ultima: esUltimoActivo,
                        tiene_atendidos: atendidos.length > 0
                    })
                });

                const data = await res.json();

                if (data.success) {
                    await Swal.fire('Eliminado', 'El servicio fue eliminado correctamente.', 'success');
                    $('#tablaCitas').DataTable().ajax.reload(); // Refrescar tabla principal
                } else {
                    Swal.fire('Error', data.error || 'No se pudo eliminar.', 'error');
                }
            } catch (err) {
                console.error('Error al eliminar servicio:', err);
                Swal.fire('Error', 'Error de red o servidor.', 'error');
            }
        }, 400); // Tiempo suficiente para que se oculte el modal completamente
    });




    // Evento: Editar servicio → Redirige a citas_agregar.php en modo edición (pendiente implementación)
    $('#tablaEditarServicios').on('click', '.btnEditarServicio', function () {
        const citaId = $('#modalEditarServicios').data('cita-id');
        // Redirigir con ID para edición (puede ampliarse para identificar servicio específico)
        window.location.href = `index.php?pagina=citas2&id=${citaId}&editar_servicio=1`;
    });
});