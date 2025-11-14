<?php
header('Content-Type: application/json');
include '../config.php';

// Debug: Registrar parámetros recibidos
error_log("GET: " . print_r($_GET, true));

$servicio_id = isset($_GET['servicio_id']) ? intval($_GET['servicio_id']) : null;
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;

if (!$servicio_id || !$fecha) {
    echo json_encode(['error' => 'Parámetros incorrectos']);
    exit;
}

try {
    // 1. Obtener el día de la semana en español
    $fecha_dt = new DateTime($fecha);
    $dias_map = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    ];
    $nombre_dia = $dias_map[$fecha_dt->format('N')];

    // 2. Obtener horarios configurados para este día y servicio
    $sql_horarios = "SELECT 
        hora_inicio, 
        hora_fin, 
        intervalo_min, 
        capacidad_por_intervalo 
        FROM horarios_servicio 
        WHERE 
            servicio_id = ? AND 
            FIND_IN_SET(?, dias_semana) > 0 AND 
            activo = 1";
    
    $stmt = $conn->prepare($sql_horarios);
    $stmt->bind_param("is", $servicio_id, $nombre_dia);
    $stmt->execute();
    $horarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($horarios)) {
        echo json_encode([]);
        exit;
    }

    // 3. Generar todos los intervalos posibles
    $intervalos_disponibles = [];
    foreach ($horarios as $horario) {
        $inicio = new DateTime($horario['hora_inicio']);
        $fin = new DateTime($horario['hora_fin']);
        $intervalo = new DateInterval('PT'.$horario['intervalo_min'].'M');
        
        $periodo = new DatePeriod($inicio, $intervalo, $fin);
        
        foreach ($periodo as $hora) {
            $hora_str = $hora->format('H:i:s');
            
            // 4. Verificar disponibilidad
            $sql_ocupacion = "SELECT COUNT(*) AS ocupadas 
                FROM asignacion_citas 
                WHERE 
                    servicio_id = ? AND 
                    fecha_cita = ? AND 
                    hora = ?";
            
            $stmt_ocup = $conn->prepare($sql_ocupacion);
            $stmt_ocup->bind_param("iss", $servicio_id, $fecha, $hora_str);
            $stmt_ocup->execute();
            $ocupadas = $stmt_ocup->get_result()->fetch_assoc()['ocupadas'];
            
            if ($ocupadas < $horario['capacidad_por_intervalo']) {
                $intervalos_disponibles[] = $hora->format('H:i');
            }
        }
    }

    // Debug: Resultado final
    error_log("Horarios disponibles: " . print_r($intervalos_disponibles, true));
    
    echo json_encode($intervalos_disponibles);

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}