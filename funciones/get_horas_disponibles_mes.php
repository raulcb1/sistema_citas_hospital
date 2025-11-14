<?php
header('Content-Type: application/json');
include '../config.php';

// Parámetros entrantes
$servicio_ups_id = isset($_GET['servicio_id']) ? intval($_GET['servicio_id']) : 0;
$anio            = isset($_GET['anio'])        ? intval($_GET['anio'])        : 0;
$mes             = isset($_GET['mes'])         ? intval($_GET['mes'])         : 0;




// Validar
if (!$servicio_ups_id || !$anio || !$mes) {
    echo json_encode([
        'success' => false,
        'error'   => 'Faltan parámetros: servicio_id, anio o mes'
    ]);
    exit();
}

/*$data = [
    "Servicio_id" => $servicio_ups_id,
    "año" => $anio,
    "mes" => "si paso"
];
echo json_encode($data);
*/

// Crear rango de fechas para el mes
$fecha = DateTime::createFromFormat('Y-n-j', "$anio-$mes-1");
$fin   = clone $fecha;
$fin->modify('last day of this month');

// 1) Cargar UNA sola vez todos los rangos de horarios para este servicio_ups en la UPS activa
$sqlHorarios = "
  SELECT hs.hora_inicio, hs.hora_fin, hs.intervalo, hs.capacidad_por_intervalo, hs.dias_semana
  FROM horarios_servicio hs
  JOIN servicios s     ON hs.servicio_id   = s.id
  JOIN servicio_ups su ON su.servicio_id    = s.id
  WHERE su.id     = ?
    AND su.ups_id = " . UPS_ACTIVA_ID;
$stmtH = $conn->prepare($sqlHorarios);
$stmtH->bind_param("i", $servicio_ups_id);
$stmtH->execute();
$resH = $stmtH->get_result();

/* $consultaEjecutada = str_replace("?", $conn->real_escape_string($servicio_ups_id), $sqlHorarios);
echo $consultaEjecutada;

echo json_encode($consultaEjecutada);
*/



// Guardar la programación en memoria
$programacion = [];
while ($r = $resH->fetch_assoc()) {
    $programacion[] = [
        'hora_inicio'            => $r['hora_inicio'],
        'hora_fin'               => $r['hora_fin'],
        'intervalo'              => intval($r['intervalo']),
        'capacidad'              => intval($r['capacidad_por_intervalo']),
        'dias_semana'            => array_map('trim', explode(',', mb_strtolower($r['dias_semana'])))
    ];
}
$stmtH->close();

// 2) Cargar todas las citas del mes para este servicio_ups (agrupadas)
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

// Indexar por clave "YYYY‑MM‑DD_HH:MM"
$citas_ocupadas = [];
while ($c = $resC->fetch_assoc()) {
    $clave = $c['fecha_cita'] . '_' . $c['hora'];
    $citas_ocupadas[$clave] = intval($c['ocupadas']);
}
$stmtC->close();

// Mapa días inglés → español
$dias_map = [
    'monday'    => 'lunes',
    'tuesday'   => 'martes',
    'wednesday' => 'miércoles',
    'thursday'  => 'jueves',
    'friday'    => 'viernes',
    'saturday'  => 'sábado',
    'sunday'    => 'domingo'
];

// Preparar salida
$eventos_ocupados = [];

// Recorrer cada día del mes
while ($fecha <= $fin) {
    $dia_ing = strtolower($fecha->format('l'));
    $dia_es  = $dias_map[$dia_ing] ?? '';
    $hoy     = $fecha->format('Y-m-d');

    // Para cada rango programado
    foreach ($programacion as $prog) {
        // Verificar si aplica este rango al día
        if (!in_array($dia_es, $prog['dias_semana'], true)) {
            continue;
        }

        // Generar bloques entre hora_inicio y hora_fin
        $h = new DateTime($prog['hora_inicio']);
        $end = new DateTime($prog['hora_fin']);
        while ($h < $end) {
            $hora_str = $h->format('H:i');
            $clave    = $hoy . '_' . $hora_str;
            $ocup     = $citas_ocupadas[$clave] ?? 0;

            // Sólo si está completo según capacidad
            if ($ocup >= $prog['capacidad']) {
                $eventos_ocupados[] = [
                    'title'  => 'Ocupado',
                    'start'  => "$hoy" . 'T' . $hora_str,
                    'color'  => '#e74c3c',
                    'editable' => false,
                    'extendedProps' => [
                        'fecha'     => $hoy,
                        'hora'      => $hora_str,
                        'capacidad' => $prog['capacidad'],
                        'ocupadas'  => $ocup
                    ]
                ];
            }
            // Avanzar al siguiente bloque
            $h->modify("+{$prog['intervalo']} minutes");
        }
    }

    $fecha->modify('+1 day');
}

// Devolver sólo los bloques ocupados
echo json_encode([
    'success' => true,
    'data'    => $eventos_ocupados
]);