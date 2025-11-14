<?php
include '../../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = $_GET['id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante de Cita Médica</title>
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
      <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

    <style>
    body {
        background: #f8f9fa;
    }

    .comprobante {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .comprobante h3 {
        margin-bottom: 1rem;
    }

    @media print {
        .no-print {
            display: none;
        }

        body {
            background: white;
        }

        .comprobante {
            border: none;
            box-shadow: none;
        }
    }
    </style>


</head>

<body>
    <div class="wrapper">
        <div class="comprobante">
            <section class="invoice">
                <!-- Membrete superior -->
                <div class="row">
                    <!-- Columna izquierda: Logo y texto -->
                    <div class="col-sd-4 text-md-left invoice-col">
                        <img src="ruta_al_logo.png" alt="Logo" style="max-height: 60px;">
                        <div><small>Red de Salud Gran Chimú</small></div>
                    </div>
                    <!-- Columna central: Título del documento -->
                    <div class="col-sd-4 text-center invoice-col">
                        <h4 class="mb-0">COMPROBANTE DE CITA MÉDICA</h4>
                    </div>
                    <!-- Columna derecha: Datos del sistema -->
                    <div class="col-sd-4 text-center text-md-right invoice-col">
                        <div><strong>Amawta Salud</strong></div>
                        <div><small>Versión 2.2</small></div>
                        <div><small>Impreso el: <span id="fechaImpresion"></span></small></div>
                    </div>
                </div>
                <!-- Fin Membrete superior -->
                <!-- Datos de Paciente y Cita -->
                <div class="row invoice-info">
                    <div id="infoCita">
                        <div class="row mb-2">
                            <div class="col-md-6  invoice-col">
                                <p><strong>Paciente:</strong> <span id="pacienteNombre"></span></p>
                                <p><strong>DNI:</strong> <span id="pacienteDNI"></span></p>
                                <p><strong>Teléfono:</strong> <span id="pacienteTelefono"></span></p>
                            </div>
                            <div class="col-md-6  invoice-col">
                                <p><strong>Fecha de Cita:</strong> <span id="fechaCita"></span></p>
                                <p><strong>Motivo:</strong> <span id="motivoCita"></span></p>
                                <p><strong>ID Cita:</strong> <span id="idCita"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Datos de Paciente y Cita -->

                <!-- Tabla de servicios -->
                <div class="mb-4">
                    <table id="tablaServicios" class="table table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Recomendaciones y Datos del Usuario -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Recomendaciones:</strong><br>Preséntese 10 minutos antes de su cita.</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p><strong>Generado por:</strong><br><span id="usuarioNombre"></span></p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="text-center mt-4 no-print">
                    <button class="btn btn-primary" onclick="window.print();"><i class="fas fa-print"></i>
                        Imprimir</button>
                    <button class="btn btn-secondary" onclick="window.close();">Cerrar</button>
                </div>

        </div>
        </section>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const params = new URLSearchParams(window.location.search);
        const id = params.get('id');

        if (!id) return alert("ID de cita no válido.");

        const res = await fetch(`../../funciones/obtener_datos_citas_imprimir.php?id=${id}`);
        const data = await res.json();

        if (!data.success) return alert("Error: " + data.error);

        document.getElementById('pacienteNombre').textContent =
            `${data.cita.nombre} ${data.cita.apellido_p} ${data.cita.apellido_m}`;
        document.getElementById('pacienteDNI').textContent = data.cita.dni;
        document.getElementById('pacienteTelefono').textContent = data.cita.telefono || '—';
        document.getElementById('fechaCita').textContent = data.cita.fecha_cita;
        document.getElementById('motivoCita').textContent = data.cita.motivo || '—';
        document.getElementById('idCita').textContent = data.cita.id;
        document.getElementById('usuarioNombre').textContent =
            `${data.usuario.nombre} ${data.usuario.apellido_p}`;
        document.getElementById('fecha_impresion').textContent = new Date().toLocaleDateString();

        const tbody = document.querySelector('#tablaServicios tbody');
        data.servicios.forEach(serv => {
            const fila = document.createElement('tr');
            fila.innerHTML =
                `<td>${serv.servicio}</td><td>${serv.fecha_cita}</td><td>${serv.hora}</td>`;
            tbody.appendChild(fila);
        });

        $('#tablaServicios').DataTable({
            paging: false,
            searching: false,
            ordering: false,
            info: false
        });
    });
    </script>

</body>

</html>