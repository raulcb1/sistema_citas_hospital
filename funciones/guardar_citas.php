<?php
header('Content-Type: application/json');
include '../config.php';

// Leer datos enviados desde JS
$input = json_decode(file_get_contents('php://input'), true);

$paciente_id = isset($input['paciente_id']) ? intval($input['paciente_id']) : 0;
$citas = isset($input['citas']) ? $input['citas'] : [];
$motivo = isset($input['motivo']) ? trim($input['motivo']) : '';
$ups_id = UPS_ACTIVA_ID;

// Validación básica de entrada
if (!$paciente_id || empty($citas)) {
  echo json_encode(['success' => false, 'error' => 'Faltan datos del paciente o lista de servicios.']);
  exit();
}

// Preparar debug si está activado
$debug_messages = [];
if (defined('DEBUG_MODE') && DEBUG_MODE) {
  $debug_messages[] = "✅ Iniciando DEBUG en guardar_citas.php";
}

// Usamos la fecha de creación actual como fecha de la cita principal
$fecha_creacion = date('Y-m-d');

$conn->begin_transaction();
$errores = [];
$ids_asignaciones = [];


if (defined('DEBUG_MODE') && DEBUG_MODE) {
  $debug_messages[] = "➡ Iniciando registro de cita para paciente ID: $paciente_id";
  $debug_messages[] = "Fecha de creación: $fecha_creacion";
  $debug_messages[] = "Motivo: $motivo";
}

try {
  // Insertar en la tabla principal 'cita' (cita maestra)
  $sqlCita = "INSERT INTO cita (paciente_id, servicio_ups_id, fecha_cita, estado_cita_id, tipo_atencion_id, motivo, estado)
              VALUES (?,". UPS_ACTIVA_ID .", ?, 1, 1, ?, 'activa')";
  $stmt = $conn->prepare($sqlCita);
  $stmt->bind_param("iss", $paciente_id, $fecha_creacion, $motivo);

  // 🧾 (opcional para DEBUG): Generar la SQL como string real con valores reemplazados
  $sqlCitaDebug = sprintf(
    "INSERT INTO cita (paciente_id, servicio_ups_id, fecha_cita, estado_cita_id, tipo_atencion_id, motivo, estado)
     VALUES (%d,". UPS_ACTIVA_ID .", '%s', 1, 1, '%s', 'activa')",
  $paciente_id,
  $fecha_creacion,
  $conn->real_escape_string($motivo));
  if (defined('DEBUG_MODE') && DEBUG_MODE) {
  $debug_messages[] = "📝 SQL INSERT cita: $sqlCitaDebug";
  }
  // fin debug

  $stmt->execute();
  if (defined('DEBUG_MODE') && DEBUG_MODE) {
      $debug_messages[] = "Se grabó la cita maestra.";
  }
  $cita_id = $stmt->insert_id;
  $stmt->close();
  if (defined('DEBUG_MODE') && DEBUG_MODE) {
    $debug_messages[] = "Cita maestra registrada con ID: $cita_id";
  }

  // Recorremos las asignaciones por servicio asociadas a esta cita maestra
  foreach ($citas as $cita) {
    $servicio_id = intval($cita['servicio_ups_id']);
    $fecha_formulario       = $cita['fecha_cita']; // formato recibido: DD/MM/YYYY
    $hora        = $cita['hora'];

    // Convertir fecha al formato YYYY-MM-DD
    $fecha_obj = DateTime::createFromFormat('d/m/Y', $fecha_formulario);
    if (!$fecha_obj) {
      $errores[] = "Fecha inválida: $fecha_formulario";
      continue;
    }
    $fecha_mysql = $fecha_obj->format('Y-m-d');

    // Verificar si ya existe una cita para ese servicio/hora
    $sqlVerifica = "SELECT COUNT(*) AS total
                    FROM asignacion_citas
                    WHERE servicio_id = ? AND fecha_cita = ? AND hora = ?";
    $stmt = $conn->prepare($sqlVerifica);
    $stmt->bind_param("iss", $servicio_id, $fecha, $hora);
    $stmt->execute();
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
    $debug_messages[] = "Se verificó duplicidad de cita: $servicio_id, $fecha, $hora";
    }
    $res = $stmt->get_result()->fetch_assoc();
    $citas_actuales = intval($res['total']);
    $stmt->close();

    // 🔄 Paso 1: Obtener el día de la semana en inglés para la fecha de la cita
    $dia_ingles = strtolower(date('l', strtotime($fecha_mysql))); // Ej: "thursday"

    // 🧭 Paso 2: Traducir el día al español (para que coincida con la columna 'dias_semana')
    $dias_traducir = [
      'monday'    => 'lunes',
      'tuesday'   => 'martes',
      'wednesday' => 'miércoles',
      'thursday'  => 'jueves',
      'friday'    => 'viernes',
      'saturday'  => 'sábado',
      'sunday'    => 'domingo',
    ];

    // ✅ Paso 3: Usar el día en español para el filtro SQL (LIKE '%jueves%')
    $dia_espanol = $dias_traducir[$dia_ingles] ?? $dia_ingles;
    $dia_semana = '%' . $dia_espanol . '%';

    // 🛠️ Paso 4: Consulta SQL para obtener la capacidad del horario correspondiente
    $sqlCapacidad = "SELECT capacidad_por_intervalo
                    FROM horarios_servicio
                    WHERE servicio_id = ? AND dias_semana LIKE ?
                    AND hora_inicio <= ? AND hora_fin > ?
                    LIMIT 1";

    // Preparar y ejecutar consulta
    $stmt = $conn->prepare($sqlCapacidad);
    $stmt->bind_param("isss", $servicio_id, $dia_semana, $hora, $hora);

    // 🧾 (opcional para DEBUG): Generar la SQL como string real con valores reemplazados
    $sqlCapacidadDebug = sprintf(
      "SELECT capacidad_por_intervalo
      FROM horarios_servicio
      WHERE servicio_id = %d AND dias_semana LIKE '%s'
      AND hora_inicio <= '%s' AND hora_fin > '%s'
      LIMIT 1",
      $servicio_id,
      $dia_semana,
      $hora,
      $hora
    );
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
      $debug_messages[] = "📝 SQL SELECT capacidad: $sqlCapacidadDebug";
    }
    // Fin del DEBUG
    
    $stmt->execute();
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
      $debug_messages[] = "Se verificó la capacidad.";
    }

    // Obtener resultado
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // 🧮 Obtener capacidad encontrada o valor 0 si no existe
    $capacidad = $res ? intval($res['capacidad_por_intervalo']) : 0;


    // Validar si hay cupo disponible
    if ($citas_actuales >= $capacidad) {
      $mensaje_error = "Sin cupo: $fecha $hora en servicio $servicio_id";
      if (defined('DEBUG_MODE') && DEBUG_MODE) {
        $mensaje_error .= " (ocupados: $citas_actuales / capacidad: $capacidad)";
      }
      $errores[] = $mensaje_error;
      continue;
    }

    // Insertar en asignacion_citas
    $sqlAsignacion = "INSERT INTO asignacion_citas (ups_id, cita_id, paciente_id, servicio_id, fecha_cita, hora, estado_cita_id, estado
      ) VALUES (?, ?, ?, ?, ?, ?, 1, 'pendiente')";
    $stmt = $conn->prepare($sqlAsignacion);
    $stmt->bind_param("iiiiss", $ups_id, $cita_id, $paciente_id, $servicio_id, $fecha_mysql, $hora);

    // 🧾 (opcional para DEBUG): Generar la SQL como string real con valores reemplazados
      $sqlAsignaciondebug = sprintf(
        "INSERT INTO asignacion_citas (ups_id, cita_id, paciente_id, servicio_id, fecha_cita, hora, estado_cita_id, estado)
        VALUES (%d, %d, %d, %d, '%s', '%s', 1, 'pendiente')",
        $ups_id,
        $cita_id,
        $paciente_id,
        $servicio_id,
        $fecha_mysql,
        $hora
      );
      if (defined('DEBUG_MODE') && DEBUG_MODE) {
        $debug_messages[] = "📝 SQL INSERT asignación: $sqlAsignaciondebug";
      }
    // Fin del DEBUG

    $stmt->execute();
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
      $debug_messages[] = "Se insertó el servicio: Cita_ID: $cita_id, Servicio_ID: $servicio_id, Fecha: $fecha, Hora: $hora.";
    }
    $ids_asignaciones[] = $stmt->insert_id;
    $stmt->close();
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
      $debug_messages[] = "Asignación registrada con ID: " . end($ids_asignaciones);
    }

  }

  // Si ninguna asignación fue válida, eliminar la cita principal
  if (empty($ids_asignaciones)) {
    $conn->rollback();
    if (DEBUG_MODE) {
      $debug_messages[] = "⚠️ No se pudo registrar ninguna asignación. Rollback.";
      $debug_messages = array_merge($debug_messages, $errores);
    }
    echo json_encode([
      'success' => false,
      'error' => 'No se pudo registrar ninguna asignación. Query SQL: ' . $sqlDebug1 . ' SQL Error: ' . $conn->error,
      'detalles' => $errores,
      'debug' => $debug_messages
    ]);
    exit();
  }

  // Todo correcto, confirmar transacción
  $conn->commit();

  $debug_messages = [];
  if (defined('DEBUG_MODE') && DEBUG_MODE) {
      $debug_messages[] = "Asignaciones exitosas: " . implode(',', $ids_asignaciones);
      $debug_messages = array_merge($debug_messages, $errores);
    };

  echo json_encode([
  'success' => true,
  'cita_id' => $cita_id,
  'asignaciones_registradas' => count($ids_asignaciones),
  'errores' => $errores,
  'debug' => $debug_messages
  ]);

  if (empty($ids_asignaciones)) {
  $conn->rollback();
  $debug_messages = [];
  if (defined('DEBUG_MODE') && DEBUG_MODE) {
    $debug_messages[] = "SQL fallido: $sqlDebug1";
    $debug_messages = array_merge($debug_messages, $errores);
  }
  echo json_encode([
    'success' => false,
    'error' => 'No se pudo registrar ninguna asignación.',
    'detalles' => $errores,
    'debug' => $debug_messages
  ]);
  exit();
}

} catch (Exception $e) {
  $conn->rollback();
  $mensaje = 'Error inesperado al guardar citas';
  if (defined('DEBUG_MODE') && DEBUG_MODE) {
    $mensaje .= ': ' . $e->getMessage();
  }
  echo json_encode([
    'success' => false,
    'error' => $mensaje,
    'debug' => $debug_messages
  ]);
  
}

$conn->close();