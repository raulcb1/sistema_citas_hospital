<?php
include '../../config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Citas</title>
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
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
            <input type="text" name="dni" class="form-control" placeholder="Ej. 12345678">
          </div>
          <div class="form-group col-md-3">
            <label>Nombre o Apellido</label>
            <input type="text" name="nombre" class="form-control" placeholder="Ej. Juan">
          </div>
          <div class="form-group col-md-3">
            <label>Fecha Cita</label>
            <input type="date" name="fecha" class="form-control">
          </div>
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

<!-- Modal para VER la cita -->
<div class="modal fade" id="modalDetalleCita" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">🩺 Detalle de la Cita</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">

        <div id="detallePaciente" class="mb-3">
          <h6>👤 Datos del Paciente</h6>
          <p><strong>Nombre:</strong> <span id="verNombrePaciente"></span></p>
          <p><strong>DNI:</strong> <span id="verDniPaciente"></span></p>
          <p><strong>Teléfono:</strong> <span id="verTelefonoPaciente"></span></p>
          <button id="btnEditarPaciente" class="btn btn-sm btn-outline-primary">Editar Paciente</button>
        </div>

        <div id="detalleCita">
          <h6>🗓️ Servicios Asociados</h6>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>Servicio</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody id="tablaServiciosDetalle">
              <!-- JS rellenará -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnImprimirComprobante" class="btn btn-success"><i class="fas fa-print"></i> Imprimir</button>
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para motivo de desactivación -->
<div class="modal fade" id="modalMotivoDesactivacion" tabindex="-1">
  <div class="modal-dialog">
    <form id="formMotivoDesactiva" class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">🛑 Motivo de Desactivación</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="cita_id">
        <input type="hidden" name="servicio_id">
        <div class="form-group">
          <label>Explique el motivo:</label>
          <textarea name="motivo" class="form-control" required rows="3"></textarea>
        </div>
        <div class="alert alert-warning" id="alertaEliminarUltimo" style="display: none;">
          Esta acción desactivará toda la cita porque no quedarán servicios activos.
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Confirmar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="js/citas_lista.js?v=<?= time() ?>"></script>

</body>
</html>
