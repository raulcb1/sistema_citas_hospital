<?php
header('Content-Type: application/json');
include '../config.php';

// ✅ 1. Leer parámetros GET
$servicio_ups_id = isset($_GET['servicio_id']) ? intval($_GET['servicio_id']) : 0;
$anio            = isset($_GET['anio'])        ? intval($_GET['anio'])        : 0;
$mes             = isset($_GET['mes'])         ? intval($_GET['mes'])         : 0;

if (!$servicio_ups_id || !$anio || !$mes) {
    echo json_encode([
        'success' => false,
        'error'   => 'Faltan parámetros: servicio_id, anio o mes'
    ]);
    exit();
}

// 🐞 DEBUG opcional
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    error_log(">>> get_horas_ocupadas_mes.php");
    error_log("Parámetros recibidos: servicio_id = $servicio_ups_id, anio = $anio, mes = $mes");
}

// ✅ 2. Traer capacidad e intervalo del servicio
$sqlConfig = "
  SELECT hs.capacidad_por_intervalo, hs.intervalo
  FROM horarios_servicio hs
  JOIN servicios s     ON hs.servicio_id = s.id
  JOIN servicio_ups su ON su.servicio_id = s.id
  WHERE su.id = ? AND su.ups_id = " . UPS_ACTIVA_ID . "
  LIMIT 1
";

$stmtCfg = $conn->prepare($sqlConfig);
$stmtCfg->bind_param("i", $servicio_ups_id);
$stmtCfg->execute();
$resCfg = $stmtCfg->get_result();

if ($resCfg->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'error' => 'No se encontró configuración horaria para este servicio.'
    ]);
    exit();
}

$config = $resCfg->fetch_assoc();
$capacidad = intval($config['capacidad_por_intervalo']);
$intervalo = intval($config['intervalo']);
$stmtCfg->close();

if (DEBUG_MODE) {
    error_log("Capacidad: $capacidad - Intervalo: $intervalo minutos");
}

// ✅ 3. Obtener todas las citas del mes agrupadas por fecha y hora
$sqlCitas = "
  SELECT fecha_cita, hora, COUNT(*) AS ocupadas
  FROM asignacion_citas
  WHERE servicio_id = ?
    AND MONTH(fecha_cita) = ?
    AND YEAR(fecha_cita) = ?
  GROUP BY fecha_cita, hora
";

$stmtC = $conn->prepare($sqlCitas);
$stmtC->bind_param("iii", $servicio_ups_id, $mes, $anio);
$stmtC->execute();
$resC = $stmtC->get_result();

$eventos_ocupados = [];

while ($row = $resC->fetch_assoc()) {
    $ocupadas = intval($row['ocupadas']);

    if ($ocupadas >= $capacidad) {
        $fecha = $row['fecha_cita'];
        $hora  = $row['hora'];
        $hora_adicional = date('H:i', strtotime($hora) + 30 * 60); // Sumar 30 minutos

        $eventos_ocupados[] = [
            'title'  => 'Ocupado',
            'start'  => "$fecha" . 'T' . $hora,
            'end'  => "$fecha" . 'T' . $hora_adicional,
            'color'  => '#e74c3c',
            'editable' => false,
            'extendedProps' => [
                'fecha'     => $fecha,
                'hora'      => $hora,
                'capacidad' => $capacidad,
                'ocupadas'  => $ocupadas,
                'estado'  => 'Ocupado'
            ]
        ];
    }
}

$stmtC->close();

// ✅ 4. Responder en formato JSON para FullCalendar
echo json_encode([
    'success' => true,
    'data'    => $eventos_ocupados
]);

if (DEBUG_MODE) {
    error_log("Eventos ocupados generados: " . count($eventos_ocupados));
}