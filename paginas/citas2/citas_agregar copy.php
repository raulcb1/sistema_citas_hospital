<?php
// © Archivo: citas_agregar.php
// Función: Formulario para crear o editar cita maestra con múltiples servicios.
// Detecta si está en modo edición si existe un parametro `id` en GET.
session_start();
include 'config.php';

$modo_edicion = isset($_GET['id']) && is_numeric($_GET['id']);
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="m-2 text-dark"><?= $modo_edicion ? 'Editar Cita' : 'Generar Citas' ?></h2>
            </div>
        </div>
    </div>
    <form id="formCitasMultiples">
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Datos del Paciente</h3>
        </div>
        <div class="card-body">
            <!-- 1. Selección / creación de paciente -->
            <div class="form-group">
                <label for="dni_busqueda">Buscar Paciente (DNI):</label>
                <div class="input-group">
                    <input type="text" id="dni_busqueda" class="form-control" placeholder="Ingrese DNI del paciente">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" id="btnBuscarPaciente">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
            <!-- Resultado -->
            <div id="resultadoPaciente" class="mt-3"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Servicios a registrar</h3>
        </div>
        <div class="card-body">

            <!-- Motivo general de la cita -->
            <div class="form-group">
                <label for="motivo_cita">Motivo general:</label>
                <input type="text" id="motivo_cita" name="motivo" class="form-control"
                    placeholder="Motivo de atención (opcional)">
            </div>

            <!-- Fila 1: Selección de servicio y mes -->
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="servicio_select">Servicio:</label>
                    <select id="servicio_select" class="form-control">
                        <option value="">-- Seleccione servicio --</option>
                        <!-- Se debe poblar dinámicamente -->
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="mes_cita">Mes:</label>
                    <input type="month" id="mes_cita" class="form-control">
                </div>
                <div class="form-group col-md-3 align-self-end">
                    <button type="button" id="btnBuscarDisponibilidad" class="btn btn-info btn-block">
                        <i class="fas fa-calendar-check"></i> Ver disponibilidad
                    </button>
                </div>
            </div>

            <!-- Fila 2: Calendario -->
            <div class="form-row">
                <div class="col-12">
                    <div id="calendar_disponibilidad"></div>
                </div>
            </div>

            <!-- Fila 3: Tabla de citas seleccionadas -->
            <div class="table-responsive mt-4">
                <table id="tabla-borrador-citas" class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Se agregan dinámicamente -->
                    </tbody>
                </table>
            </div>

            <!-- Fila 4: Botón final -->
            <div class="form-group text-right mt-3">
                <button type="button" id="btnRegistrarCitas" class="btn btn-success">
                    <i class="fas fa-save"></i> Registrar todas las citas
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Nuevo Paciente -->
    <div class="modal fade" id="modalNuevoPaciente" tabindex="-1" aria-labelledby="modalNuevoPacienteLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="modalNuevoPacienteLabel">Registrar Nuevo Paciente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoPaciente">
                        <div class="form-group">
                            <label for="dni_nuevo">DNI:</label>
                            <input type="text" id="dni_nuevo" name="dni" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre_nuevo">Nombre:</label>
                            <input type="text" id="nombre_nuevo" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido_p_nuevo">Apellido Paterno:</label>
                            <input type="text" id="apellido_p_nuevo" name="apellido_p" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido_m_nuevo">Apellido Materno:</label>
                            <input type="text" id="apellido_m_nuevo" name="apellido_m" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_nac_nuevo">Fecha de Nacimiento:</label>
                            <input type="date" id="fecha_nac_nuevo" name="fecha_nac" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="telefono_nuevo">Teléfono:</label>
                            <input type="text" id="telefono_nuevo" name="telefono" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardarPaciente" class="btn btn-primary">Guardar Paciente</button>
                </div>
            </div>
        </div>
    </div>

<?php
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    ?>
    <div id="debugBox" style="margin-top: 1em;">
        <label><strong>DEBUG:</strong></label>
        <textarea id="debugText" rows="10" class="form-control" readonly></textarea>
        <button id="clearDebugBtn" class="btn btn-sm btn-warning mt-2">Limpiar</button>
    </div>
    <script>
    window.debug_log = function(msg) {
        const area = document.getElementById('debugText');
        if (area) {
            area.value += (typeof msg === 'object' ? JSON.stringify(msg, null, 2) : msg) + "\n\n";
            area.scrollTop = area.scrollHeight; // Auto scroll al final
        }
    };

    document.getElementById('clearDebugBtn').addEventListener('click', function () {
        document.getElementById('debugText').value = '';
    });
    </script>
    <?php
}
?>
</section>

    <script>
        const DEBUG_MODE = <?= DEBUG_MODE ? 'true' : 'false' ?>;
    </script>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.css"> -->
    <script src="plugins/fullcalendar/6.1.8/locales-all.global.min.js"></script>
    <script src="plugins/fullcalendar/6.1.8/index.global.min.js"></script>
    <script src="plugins/fullcalendar/6.1.8/daygrid/index.global.min.js"></script>
    <script src="plugins/fullcalendar/6.1.8/timegrid/index.global.min.js"></script>
    <script src="plugins/fullcalendar/6.1.8/interaction/index.global.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <!-- Incluir SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2@11.js"></script>

    <?php //Script para registrar paciente desde modal ?>
    <script src="js/registrar_paciente_modal.js"></script>

    <?php //Script para obtener los horarios disponibles en el mes del servicio ?>

    <?php //Script para obtener las citas existentes por servicio ?>
    <script src="js/calendario_citas_disponibles.js?v=<?= time(); ?>"></script>

    <?php //Script para obtener los horarios disponibles en el mes por servicio ?>
    <script src="js/calendario_servicios_disponible_mes.js?v=<?= time(); ?>"></script>

    <?php //Script para configurar el calendario y mostrar la información en el ?>
     <script src="js/calendario_config.js?v=<?= time(); ?>"></script> 
    <!--<script src="js/calendario_config_2.js?v=<?= time(); ?>"></script>-->





    <?php //Script para obtener los horarios disponibles del servicio ?>
    <!-- <script src="js/horas_disponibles_ui.js"></script> -->

    <?php //Script para enviar las citas a grabar ?>
    <script src="js/enviar_citas.js?v=<?= time(); ?>"></script>


    <script>
    $(document).ready(function() {
        $('#btnBuscarPaciente').on('click', function() {
            const dni = $('#dni_busqueda').val().trim();

            if (!dni || dni.length < 6) {
                Swal.fire('Atención', 'Ingrese un DNI válido (mínimo 6 dígitos).', 'warning');
                return;
            }

            $.ajax({
                url: 'funciones/get_paciente.php',
                method: 'GET',
                data: {
                    dni: dni
                },
                dataType: 'json',
                success: function(response) {
                    const contenedor = $('#resultadoPaciente');
                    contenedor.empty();

                    if (response.success && response.data) {
                        const paciente = response.data;

                        const html = `
            <div class="card bg-light">
              <div class="card-header">
                <strong>Paciente encontrado:</strong>
              </div>
              <div class="card-body">
                <p><strong>Nombre:</strong> ${paciente.nombre_completo}</p>
                <p><strong>DNI:</strong> ${paciente.dni}</p>
                <p><strong>Edad:</strong> ${paciente.edad} años</p>
                <p><strong>Teléfono:</strong> ${paciente.telefono || '—'}</p>
                <input type="hidden" id="paciente_id" name="paciente_id" value="${paciente.id}">
              </div>
            </div>
          `;
                        contenedor.html(html);
                    } else {
                        contenedor.html(`
            <div class="alert alert-danger">
              No se encontró paciente con DNI <strong>${dni}</strong>.
              <br>
              <button type="button" class="btn btn-sm btn-success mt-2" data-toggle="modal" data-target="#modalNuevoPaciente">
                <i class="fas fa-user-plus"></i> Registrar nuevo paciente
              </button>
            </div>
          `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    Swal.fire('Error', 'No se pudo realizar la búsqueda.', 'error');
                }
            });
        });
    });

    // Cargar servicios disponibles al iniciar
    function cargarServicios() {
        $.ajax({
            url: 'funciones/get_servicios.php',
            method: 'GET',
            success: function(data) {
                const select = $('#servicio_select');
                select.empty().append('<option value="">-- Seleccione servicio --</option>');
                data.forEach(servicio => {
                    select.append(`<option value="${servicio.id}">${servicio.nombre}</option>`);
                });
            },
            error: function() {
                Swal.fire('Error', 'No se pudo cargar la lista de servicios.', 'error');
            }
        });
    }

    // Ejecutar al cargar la página
    $(document).ready(function() {
        cargarServicios();
    });
    </script>