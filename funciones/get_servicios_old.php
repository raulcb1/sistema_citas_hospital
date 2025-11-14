<?php
include '..\config.php';

// Verificar si se ha recibido el ID del UPS por GET
if(isset($_GET['ups_id'])) {
    $ups_id = $_GET['ups_id'];

    // Consultar la tabla de Servicio UPS para obtener los servicios asociados al UPS especificado
    $sql = "SELECT servicios.id, servicios.nombre
            FROM servicio_ups
            INNER JOIN servicios ON servicio_ups.servicio_id = servicios.id
            WHERE servicio_ups.ups_id = $ups_id";
    $result = $conn->query($sql);

    // Crear un array para almacenar los servicios
    $servicios = array();

    // Iterar sobre los resultados y agregarlos al array de servicios
    while($row = $result->fetch_assoc()) {
        $servicio = array(
            'id' => $row['id'],
            'nombre' => $row['nombre']
        );
        array_push($servicios, $servicio);
    }

    // Devolver los servicios en formato JSON
    echo json_encode($servicios);
} else {
    echo "No se ha recibido el ID del UPS";
}

// Cerrar la conexión
$conn->close();
?>