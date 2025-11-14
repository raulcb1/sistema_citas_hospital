<?php
// Página: citas_lista.php
// Funcionalidad: Mostrar lista de citas médicas maestras con filtros, usando AdminLTE y DataTables
session_start();
include 'config.php';

$_SESSION['datatable'] = 'tablaCitas';

?>
<section class="content">
    <div class="wrapper">

        <!-- Contenido principal -->
        <section class="content pt-3">
            <div class="container-fluid">

                <!-- Botón Agregar Cita -->
                <div class="row mb-2">
                    <div class="col-md-2 text-left">
                        <form action="index.php" method="post">
                            <input type="hidden" name="pagina" value="citas2">
                            <button type="submit" class="btn btn-block btn-success btn-sm"><i
                                    class="fas fa-plus-circle"></i> Agregar Cita</button>
                        </form>
                    </div>
                </div>


                <!-- Card: Filtros de búsqueda -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title"><i class="fas fa-search"></i> Buscar Citas</h3>
                    </div>
                    <div class="card-body">
                        <form id="formFiltros">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="filtro_dni">DNI</label>
                                    <input type="text" class="form-control" id="filtro_dni"
                                        placeholder="DNI del paciente">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filtro_fecha">Fecha de cita</label>
                                    <input type="date" class="form-control" id="filtro_fecha">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filtro_servicio">Servicio</label>
                                    <select class="form-control" id="filtro_servicio">
                                        <option value="">Todos</option>
                                        <!-- Se poblará dinámicamente con JS si es necesario -->
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filtro_estado">Estado</label>
                                    <select class="form-control" id="filtro_estado">
                                        <option value="">Todos</option>
                                        <option value="activa">Activa</option>
                                        <option value="cancelada">Cancelada</option>
                                        <option value="atendida">Atendida</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-info" id="btnBuscar"><i class="fas fa-search"></i>
                                    Buscar</button>
                                <button type="button" class="btn btn-secondary" id="btnLimpiar"><i
                                        class="fas fa-eraser"></i> Limpiar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card: Tabla de resultados -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title"><i class="fas fa-list"></i> Resultados de la búsqueda</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablaCitas" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID Cita</th>
                                        <th>Fecha</th>
                                        <th>Paciente</th>
                                        <th>DNI</th>
                                        <th>Motivo</th>
                                        <!-- <th>Teléfono</th> -->
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables llenará dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>


    <!-- MODAL: Ver detalles de la cita -->
    <div class="modal fade" id="modalVerCita" tabindex="-1" role="dialog" aria-labelledby="modalVerCitaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-primary">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalVerCitaLabel"><i class="fas fa-eye"></i> Detalle de la Cita</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Datos del Paciente -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Paciente</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Nombre:</strong> <span id="verPacienteNombre"></span></p>
                            <p><strong>DNI:</strong> <span id="verPacienteDNI"></span></p>
                            <p><strong>Edad:</strong> <span id="verPacienteEdad"></span></p>
                            <p><strong>Teléfono:</strong> <span id="verPacienteTelefono"></span></p>
                            <button id="btnEditarPaciente" class="btn btn-outline-primary btn-sm mt-2"><i
                                    class="fas fa-user-edit"></i> Editar paciente</button>
                        </div>
                    </div>

                    <!-- Datos de la Cita -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Cita</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>ID Cita:</strong> <span id="verCitaId"></span></p>
                            <p><strong>Fecha:</strong> <span id="verCitaFecha"></span></p>
                            <p><strong>Motivo:</strong> <span id="verCitaMotivo"></span></p>
                            <p><strong>Estado:</strong> <span id="verCitaEstado"></span></p>
                        </div>
                    </div>

                    <!-- Servicios Asociados -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong>Servicios Asociados</strong>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-striped mb-0" id="tablaServiciosCita">
                                <thead>
                                    <tr>
                                        <th>Servicio</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Se llena dinámicamente con JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light" id="footer_modalVerCita">
                    <a href="#" id="btnImprimirComprobante" class="btn btn-primary" target="_blank">
                        <i class="fas fa-print"></i> Imprimir comprobante
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Editar Servicios de Cita -->
    <div class="modal fade" id="modalEditarServicios" tabindex="-1" aria-labelledby="modalEditarServiciosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Cabecera del modal -->
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalEditarServiciosLabel">
                        <i class="fas fa-edit"></i> Editar Servicios de Cita
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Cuerpo del modal -->
                <div class="modal-body">
                    <!-- Datos generales -->
                    <div class="mb-3">
                        <p><strong>ID Cita:</strong> <span id="editarCitaId">—</span></p>
                        <p><strong>Paciente:</strong> <span id="editarPacienteNombre">—</span></p>
                        <p><strong>Fecha de cita:</strong> <span id="editarCitaFecha">—</span></p>
                    </div>

                    <!-- Tabla de servicios -->
                    <div class="table-responsive">
                        <table id="tablaEditarServicios" class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Servicio</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llenará dinámicamente desde JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pie del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>    

    <!-- Script personalizado para esta vista -->
    <script src="js/citas_modal_editar_serv.js?v=<?= time(); ?>"></script>
    <script src="js/citas_lista.js?v=<?= time(); ?>"></script>
    <script src="js/citas_modal_paciente.js?v=<?= time(); ?>"></script>
    <!-- Incluir SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2@11.js"></script>
