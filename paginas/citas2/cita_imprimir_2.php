<?php
include '../../config.php';

$cita_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$cita_id) {
  die("ID de cita no válido.");
}

// Obtener datos de la cita maestra
$sql = "SELECT c.id, c.fecha_cita, c.motivo, 
               CONCAT(p.nombre, ' ', p.apellido_p, ' ', p.apellido_m) AS paciente,
               p.dni, p.telefono
        FROM cita c
        INNER JOIN pacientes p ON p.id = c.paciente_id
        WHERE c.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cita_id);
$stmt->execute();
$cita = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$cita) {
  die("Cita no encontrada.");
}

// Obtener los servicios asignados
$sql2 = "SELECT s.nombre AS servicio, ac.fecha_cita, ac.hora
         FROM asignacion_citas ac
         INNER JOIN servicios s ON ac.servicio_id = s.id
         WHERE ac.cita_id = ?
         ORDER BY ac.fecha_cita, ac.hora";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $cita_id);
$stmt2->execute();
$servicios = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comprobante de Cita</title>
  <link rel="stylesheet" href="../plugins/bootstrap/css/bootstrap.min.css">
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
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
  <div class="comprobante">
    <div class="text-center mb-4">
      <h4 class="text-primary mb-0">Red de Salud Gran Chimú</h4>
      <small>Comprobante de Cita Médica</small>
      <hr>
    </div>

    <div class="mb-3">
      <p><strong>Paciente:</strong> <?= htmlspecialchars($cita['paciente']) ?></p>
      <p><strong>DNI:</strong> <?= htmlspecialchars($cita['dni']) ?></p>
      <p><strong>Teléfono:</strong> <?= htmlspecialchars($cita['telefono'] ?: '—') ?></p>
      <p><strong>Fecha de Registro:</strong> <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?></p>
      <p><strong>Motivo de la cita:</strong> <?= htmlspecialchars($cita['motivo'] ?: '—') ?></p>
    </div>

    <h5 class="mb-2">Servicios Programados:</h5>
    <table class="table table-bordered">
      <thead class="thead-light">
        <tr>
          <th>Servicio</th>
          <th>Fecha</th>
          <th>Hora</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($s = $servicios->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($s['servicio']) ?></td>
            <td><?= date('d/m/Y', strtotime($s['fecha_cita'])) ?></td>
            <td><?= substr($s['hora'], 0, 5) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="text-right mt-4 no-print">
      <button class="btn btn-primary" onclick="window.print()">
        <i class="fas fa-print"></i> Imprimir
      </button>
      <a href="citas_agregar.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
      </a>
    </div>
  </div>

  <script src="../plugins/jquery/jquery.min.js"></script>
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
