<?php
// Página: citas_lista.php
// Propósito: Mostrar un listado filtrable y ordenable de las citas médicas registradas en el sistema.
// Funcionalidad: Usa DataTables con AJAX hacia `get_citas_lista.php`.
// Requiere: Bootstrap, jQuery, DataTables, y SweetAlert2.

include '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Citas</title>
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
</head>
<body>
<div class="container mt-4">
  <h3>📋 Lista de Citas Registradas</h3>

  <!-- Filtros -->
  <div class="card mt-3">
    <div class="card-header bg-primary text-white">🔎 Filtros de búsqueda</div>
    <div class="card-body">
      <form id="formFiltros">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label>DNI Paciente</label>
            <input type="text" id="filtro_dni" name="dni" class="form-control" placeholder="Ej. 12345678">
          </div>
          <div class="form-group col-md-3">
            <label>Nombre o Apellido</label>
            <input type="text" name="nombre" class="form-control" placeholder="Ej. Juan">
          </div>
          <div class="form-group col-md-3">
            <label>Fecha Cita</label>
            <input type="date" id="filtro_fecha" name="fecha" class="form-control">
          </div>
          <div class="form-group col-md-3">
            <label for="filtro_servicio">Servicio:</label>
            <select id="filtro_servicio" class="form-control">
              <option value="">Todos</option>
              <!-- Opciones se cargarán dinámicamente -->
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3 align-self-end">
            <button type="submit" class="btn btn-info btn-block"><i class="fas fa-search"></i> Buscar</button>
          </div>
        </div>

      </form>
    </div>
  </div>

  <!-- Tabla de resultados -->
  <div class="card mt-3">
    <div class="card-body table-responsive">
      <table id="tablaCitas" class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Paciente</th>
            <th>DNI</th>
            <th>Fecha Cita</th>
            <th>Motivo</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- JS rellenará -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal: Ver detalles de la cita (llenado dinámico) -->
  <div class="modal fade" id="modalVerCita" tabindex="-1" aria-labelledby="modalVerCitaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="modalVerCitaLabel">Detalles de la Cita</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="contenidoModalCita">
          <!-- Aquí se cargan los datos con JS (cita + paciente + servicios) -->
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Librerías JS necesarias -->

<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2@11.js"></script>

<!-- Script principal -->
<script src="js/citas_lista.js?v=<?= time(); ?>"></script>

</body>
</html>