<?php
// Página: cita_imprimir.php
// Funcionalidad: Mostrar comprobante imprimible de una cita médica

include '../../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = $_GET['id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comprobante de Cita Médica</title>
  <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background: #f8f9fa;
    }

    .comprobante {
      max-width: 900px;
      margin: 2rem auto;
      padding: 2rem;
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    @media print {
      .no-print { display: none; }
      body { background: white; }
      .comprobante {
        border: none;
        box-shadow: none;
      }
    }
  </style>
</head>

<body>
  <div class="comprobante">
    <section class="invoice">
    <!-- Membrete superior -->
    <div class="row align-items-center mb-4">
      <!-- Logo + institución -->
      <div class="col-md-4 text-center text-md-left invoice-col">
        <img src="../../dist/img/logo_ris_02.png" alt="Logo" style="max-height: 60px;">
        <div><small>Red de Salud Gran Chimú</small></div>
      </div>

      <!-- Título -->
      <div class="col-md-4 text-center invoice-col">
        <h4 class="mb-0">COMPROBANTE DE CITA MÉDICA</h4>
      </div>

      <!-- Datos del sistema -->
      <div class="col-md-4 text-right text-md-right invoice-col">
        <strong>Amawta Salud</strong><br>
        <small>Versión 2.2</small><br>
        <small>Impreso el: <span id="fechaImpresion"></span></small>
      </div>
    </div>

    <!-- Datos del paciente y cita -->
    <div class="row align-items-left invoice-info">
      <div class="col-md-6 invoice-col">
        <p><strong>Paciente:</strong> <span id="pacienteNombre"></span></p>
        <p><strong>DNI:</strong> <span id="pacienteDNI"></span></p>
        <p><strong>Teléfono:</strong> <span id="pacienteTelefono"></span></p>
      </div>
      <div class="col-md-6 invoice-col">
        <p><strong>Fecha de Cita:</strong> <span id="fechaCita"></span></p>
        <p><strong>Motivo:</strong> <span id="motivoCita"></span></p>
        <p><strong>ID Cita:</strong> <span id="idCita"></span></p>
      </div>
    </div>

    <!-- Servicios -->
    <div class="mb-4">
      <h5 class="mb-3">Servicios asignados</h5>
      <table class="table table-bordered" id="tablaServicios">
        <thead class="thead-light">
          <tr>
            <th>Servicio</th>
            <th>Fecha</th>
            <th>Hora</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <!-- Recomendaciones y usuario -->
    <div class="row mb-4">
      <div class="col-md-6">
        <p><strong>Recomendación:</strong><br>
        Preséntese 10 minutos antes de su cita.</p>
      </div>
      <div class="col-md-6 text-right">
        <p><strong>Generado por:</strong><br><span id="usuarioNombre"></span></p>
      </div>
    </div>

    <!-- Botones -->
    <div class="text-center no-print mt-4">
      <button class="btn btn-primary" onclick="window.print();"><i class="fas fa-print"></i> Imprimir</button>
      <button class="btn btn-secondary" onclick="window.close();">Cerrar</button>
    </div>
    </section>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', async () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    if (!id) return alert("ID de cita no válido.");

    try {
      const res = await fetch(`../../funciones/obtener_datos_citas_imprimir.php?id=${id}`);
      const data = await res.json();

      if (!data.success) {
        alert("Error: " + data.error);
        return;
      }

      // Llenar datos de la cita
      const c = data.cita;
      const u = data.usuario;

      document.getElementById('pacienteNombre').textContent = `${c.nombre} ${c.apellido_p} ${c.apellido_m}`;
      document.getElementById('pacienteDNI').textContent = c.dni;
      document.getElementById('pacienteTelefono').textContent = c.telefono || '—';
      document.getElementById('fechaCita').textContent = c.fecha_cita;
      document.getElementById('motivoCita').textContent = c.motivo || '—';
      document.getElementById('idCita').textContent = c.id;
      document.getElementById('usuarioNombre').textContent = `${u.nombre} ${u.apellido_p}`;
      document.getElementById('fechaImpresion').textContent = new Date().toLocaleDateString();

      // Agregar filas a la tabla de servicios
      const tbody = document.querySelector('#tablaServicios tbody');
      data.servicios.forEach(s => {
        const fila = document.createElement('tr');
        fila.innerHTML = `<td>${s.servicio}</td><td>${s.fecha_cita}</td><td>${s.hora}</td>`;
        tbody.appendChild(fila);
      });

    } catch (err) {
      console.error('❌ Error al cargar datos:', err);
      alert("Error al obtener datos desde el servidor.");
    }
  });
  </script>
</body>
</html>
