<?php
// get_horarios_medicos.php
// Funcionalidad: Devuelve horarios médicos programados para el calendario, en formato JSON.

// 🧱 Configuración base
require_once '../config.php'; // Incluye conexión y UPS_ACTIVA_ID
header('Content-Type: application/json');

// 📥 Parámetros
$mes         = $_GET['mes'] ?? null;
$medico_id   = $_GET['medico_id'] ?? null;
$servicio_id = $_GET['servicio_id'] ?? null;

// 🔍 Validar parámetro mínimo (mes)
if (!$mes) {
  echo json_encode(['success' => false, 'error' => 'Debe seleccionar un mes.']);
  exit;
}

// 📅 Obtener primer y último día del mes
$fecha_inicio = date('Y-m-01', strtotime($mes));
$fecha_fin    = date('Y-m-t', strtotime($mes));

// 🧩 Armar consulta SQL
$sql = "
  SELECT ac.*, 
         s.nombre AS servicio_nombre, 
         u.nombre AS medico_nombre, 
         u.apellido_p AS medico_apellido_p, 
         u.apellido_m AS medico_apellido_m,
         s.color AS color_servicio
  FROM asignacion_consultorios ac
  INNER JOIN servicio_ups su ON ac.servicio_id = su.servicio_id
  INNER JOIN servicios s ON ac.servicio_id = s.id
  INNER JOIN medicos m ON ac.medico_id = m.id
  INNER JOIN usuarios u ON m.usuario_id = u.id
  WHERE su.ups_id = ?
    AND ac.fecha BETWEEN ? AND ?
";

// 📌 Agregar filtros dinámicos
$params = [UPS_ACTIVA_ID, $fecha_inicio, $fecha_fin];
$types  = "iss";

if ($medico_id) {
  $sql .= " AND ac.medico_id = ?";
  $params[] = $medico_id;
  $types   .= "i";
}

if ($servicio_id) {
  $sql .= " AND ac.servicio_id = ?";
  $params[] = $servicio_id;
  $types   .= "i";
}

$sql .= " ORDER BY ac.fecha, ac.turno";

// 🔌 Ejecutar consulta
$stmt = $conn->prepare($sql);
if (!$stmt) {
  echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta.']);
  exit;
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// 📦 Armar eventos para el calendario
$eventos = [];

while ($row = $result->fetch_assoc()) {
  $hora_inicio = $row['turno'] === 'mañana' ? '07:30:00' : '14:00:00';
  $hora_fin    = $row['turno'] === 'mañana' ? '13:30:00' : '18:30:00';

  $eventos[] = [
    'title' => "{$row['turno']} - {$row['servicio_nombre']} - Dr(a). {$row['medico_apellido_p']}",
    'start' => "{$row['fecha']}",
    //'start' => "{$row['fecha']}T{$hora_inicio}",
    //'end'   => "{$row['fecha']}T{$hora_fin}",
    //'backgroundColor' => $row['color_servicio'] ?: '#007bff' // Color definido por el servicio o azul por defecto
    //'borderColor'     => $row['color_servicio'] ?: '#007bff',
    'color'           => $row['color_servicio'] ?: '#007bff',
    //'textColor'       => '#fff',
    /*'extendedProps' => [
      'medico'   => "{$row['medico_nombre']} {$row['medico_apellido_p']} {$row['medico_apellido_m']}",
      'turno'    => $row['turno'],
      'fecha'    => $row['fecha'],
      //'color'           => $row['color_servicio'] ?: '#007bff',
    ]*/
  ];
}

// ✅ Respuesta final
echo json_encode(['success' => true, 'eventos' => $eventos]);
