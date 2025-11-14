<?php
// Página: programacion_horarios.php
// Funcionalidad: Visualizar horarios programados de médicos por servicio y mes.

session_start();
include 'config.php';
?>
<section class="content">

    <div class="wrapper">

        <!-- Contenido principal -->
        <section class="content pt-3">
            <div class="container-fluid">

                <!-- Card: Filtros -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de búsqueda</h3>
                    </div>
                    <div class="card-body">
                        <form id="formFiltros">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="filtro_medico">Médico</label>
                                    <select id="filtro_medico" class="form-control">
                                        <option value="">-- Selecciones Médico --</option>
                                        <?php
                                        $sql = "SELECT u.id, u.apellido_p, u.apellido_m, u.nombre from usuarios u 
                                                inner join medicos m on u.id = m.usuario_id 
                                                where u.activo = 1 
                                                order by u.apellido_p, u.apellido_m, u.nombre;";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='{$row['id']}'>{$row['apellido_p']} {$row['apellido_m']}, {$row['nombre']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="filtro_servicio">Servicio</label>
                                    <select id="filtro_servicio" class="form-control">
                                        <option value="">-- Seleccione Servicio --</option>
                                        <?php
                                        $sql = "SELECT id, nombre FROM servicios WHERE activo = 1 and ups_id= " . UPS_ACTIVA_ID . " ORDER BY nombre";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="filtro_mes">Mes</label>
                                    <input type="month" class="form-control" id="filtro_mes">
                                </div>
                                <div class="form-group col-md-2 align-self-end">
                                    <button type="button" id="btnBuscarHorarios" class="btn btn-success btn-block">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card: Calendario -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Calendario de Programación</h3>
                    </div>
                    <div class="card-body">
                        <div id="calendario_horarios"></div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</section>

<!-- Scripts necesarios -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/fullcalendar/main.min.js"></script>
<script src="plugins/fullcalendar/locales-all.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- Script personalizado -->
<script src="js/programacion_horarios.js?v=<?= time(); ?>"></script>