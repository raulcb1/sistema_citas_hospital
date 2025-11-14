<?php
header('Content-Type: application/json');
include '../config.php';

$servicio_id   = isset($_GET['servicio_id']) ? intval($_GET['servicio_id']) : 0;
$fecha_inicio  = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin     = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

if (!$servicio_id || !$fecha_inicio || !$fecha_fin) {
    $mensaje = 'Parámetros incompletos: ';
    if (DEBUG_MODE) {
        $mensaje .= "servicio_id=[$servicio_id], fecha_inicio=[$fecha_inicio], fecha_fin=[$fecha_fin]";
    }
    echo json_encode([ 'success' => false, 'error' => $mensaje ]);
    exit();
}


$inicio = new DateTime($fecha_inicio);
$fin    = new DateTime($fecha_fin);
$horas_disponibles = [];

while ($inicio <= $fin) {
    $dia_semana = strtolower($inicio->format('l')); // monday, tuesday...
    $fecha_actual = $inicio->format('Y-m-d');

    // Obtener horarios para ese día
    $sql = "SELECT turno, hora_inicio, hora_fin, intervalo, capacidad_por_intervalo
            FROM horarios_servicio
            WHERE servicio_ups_id = ? AND dias_semana LIKE ?";

    $like_dia = "%$dia_semana%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $servicio_id, $like_dia);
    $stmt->execute();

    if ($stmt === false) {
        $mensaje = 'Error al preparar consulta SQL.';
        if (DEBUG_MODE) {
            $mensaje .= ' SQL: ' . $conn->error;
        }
        echo json_encode([ 'success' => false, 'error' => $mensaje ]);
        exit();
    }




    $result = $stmt->get_result();

    $turnos = [ 'mañana' => [], 'tarde' => [] ];

    while ($row = $result->fetch_assoc()) {
        $hora_inicio = new DateTime($row['hora_inicio']);
        $hora_fin    = new DateTime($row['hora_fin']);
        $intervalo   = intval($row['intervalo']);

        while ($hora_inicio < $hora_fin) {
            $hora_str = $hora_inicio->format('H:i');

            // Verificar cuántas citas hay para esa hora
            $sqlCitas = "SELECT COUNT(*) as total FROM asignacion_citas
                         WHERE servicio_ups_id = ? AND fecha = ? AND hora = ?";
            $stmt2 = $conn->prepare($sqlCitas);
            $stmt2->bind_param("iss", $servicio_id, $fecha_actual, $hora_str);
            $stmt2->execute();
            $resCitas = $stmt2->get_result()->fetch_assoc();
            $total_citas = intval($resCitas['total']);
            $stmt2->close();

            if ($total_citas < $row['capacidad_por_intervalo']) {
                $turnos[$row['turno']][] = $hora_str;
            }

            $hora_inicio->modify("+{$intervalo} minutes");
        }
    }

    if (!empty($turnos['mañana']) || !empty($turnos['tarde'])) {
        $horas_disponibles[$fecha_actual] = $turnos;
    }

    $inicio->modify('+1 day');
}

echo json_encode([ 'success' => true, 'data' => $horas_disponibles ]);