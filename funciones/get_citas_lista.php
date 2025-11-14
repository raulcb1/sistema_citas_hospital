<?php
/**
 * Archivo: get_citas_lista.php
 * 
 * Funcionalidad:
 * Este script devuelve una lista de citas médicas registradas (citas maestras)
 * en formato JSON para ser consumido desde una tabla DataTables.
 * 
 * Permite:
 * - Filtros opcionales por DNI, fecha específica o servicio.
 * - Ordenamiento por fecha (de más reciente a más antigua).
 * - Soporte para serverSide DataTables con paginación, búsqueda y ordenamiento.
 * 
 * Este backend es reutilizable y puede ser llamado desde varias páginas que necesiten
 * mostrar el listado de citas.
 */

include '../config.php';
header('Content-Type: application/json');

$columns = [
    0 => 'cita.id',
    1 => 'paciente.dni',
    2 => 'paciente.nombre',
    3 => 'cita.fecha_cita',
    4 => 'servicio.nombre'
];

// Leer parámetros enviados por DataTables (GET)
$draw = intval($_GET['draw'] ?? 1);
$start = intval($_GET['start'] ?? 0);
$length = intval($_GET['length'] ?? 10);
$searchValue = $_GET['search']['value'] ?? '';

// Filtros personalizados por GET (opcionales)
$dni         = $_GET['dni'] ?? '';
$fecha       = $_GET['fecha'] ?? '';
$cita_id = $_GET['cita_id'] ?? '';
$estado      = $_GET['estado'] ?? ''; // 'activa', 'cancelada', etc.

// Base de la consulta
$sqlBase = "
    FROM cita
    INNER JOIN pacientes ON pacientes.id = cita.paciente_id
    WHERE 1=1 AND cita.servicio_ups_id=" . UPS_ACTIVA_ID ."
";

// Agregar filtros personalizados
$params = [];
$types = '';

if (!empty($dni)) {
    $sqlBase .= " AND pacientes.dni = ?";
    $params[] = $dni;
    $types .= 's';
}

if (!empty($fecha)) {
    $sqlBase .= " AND cita.fecha_cita = ?";
    $params[] = $fecha;
    $types .= 's';
}

if (!empty($cita_id)) {
    $sqlBase .= " AND cita.id = ?";
    $params[] = $servicio_id;
    $types .= 'i';
}

if (!empty($estado)) {
    $sqlBase .= " AND cita.estado = ?";
    $params[] = $estado;
    $types .= 's';
}

// Búsqueda general por nombre o DNI (campo de búsqueda principal de DataTables)
if (!empty($searchValue)) {
    $sqlBase .= " AND (pacientes.nombre LIKE ? OR pacientes.dni LIKE ?)";
    $params[] = "%$searchValue%";
    $params[] = "%$searchValue%";
    $types .= 'ss';
}

// Conteo total (sin filtros)
$totalQuery = "SELECT COUNT(*) FROM cita";
$totalResult = $conn->query($totalQuery);
$totalData = $totalResult->fetch_row()[0];

// Conteo con filtros aplicados
$countFiltered = 0;
$stmtCount = $conn->prepare("SELECT COUNT(*) " . $sqlBase);
if ($types) $stmtCount->bind_param($types, ...$params);
$stmtCount->execute();
$stmtCount->bind_result($countFiltered);
$stmtCount->fetch();
$stmtCount->close();

// Consulta principal con datos paginados
$orderColumn = $columns[$_GET['order'][0]['column'] ?? 0] ?? 'cita.fecha_cita';
$orderDir = strtoupper($_GET['order'][0]['dir'] ?? 'DESC');
$orderDir = in_array($orderDir, ['ASC', 'DESC']) ? $orderDir : 'DESC';

$sql = "
    SELECT cita.id, cita.fecha_cita, cita.estado, cita.motivo,
           pacientes.nombre AS paciente_nombre,
           pacientes.apellido_p AS paciente_apellido,
           pacientes.dni AS paciente_dni
    $sqlBase
    ORDER BY $orderColumn $orderDir
    LIMIT ?, ?
";

$params[] = $start;
$params[] = $length;
$types .= 'ii';

$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id'            => $row['id'],
        'fecha_cita'    => $row['fecha_cita'],
        'estado'        => ucfirst($row['estado']),
        'motivo'        => $row['motivo'],
        'paciente'      => $row['paciente_nombre'] . ' ' . $row['paciente_apellido'],
        'dni'           => $row['paciente_dni']
    ];
}

echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $totalData,
    'recordsFiltered' => $countFiltered,
    'data'            => $data
]);

$conn->close();