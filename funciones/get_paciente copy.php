<?php
header('Content-Type: application/json');
include '../config.php';

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(array("error" => "Error en la conexión: " . $conn->connect_error)));
}



// Verificar si se ha recibido el ID del paciente por POST
//if(isset($_POST['dni_paciente'])) {
if(isset($_POST['dni'])) {
    // Obtener el ID del paciente
    $dniPaciente = $_POST['dni'];

    // Consultar la información del paciente en la base de datos
    $sql = "SELECT * FROM pacientes WHERE dni = $dniPaciente and activo = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // El paciente fue encontrado, devolver los datos en formato JSON
        $row = $result->fetch_assoc();
        $data = array(
            'id' => $row['id'],
            'dni' => $row['dni'],
            'apellido_p' => $row['apellido_p'],
            'apellido_m' => $row['apellido_m'],
            'fecha_nac' => $row['fecha_nac'],
            'telefono' => $row['telefono'],
            'nombre' => $row['nombre'],
            'nombre_completo' => $row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']
            // Agregar más campos según sea necesario
        );
        echo json_encode(array('success' => true, 'data' => $data));
    } else {
        // El paciente no fue encontrado
        echo json_encode(array('success' => false));
    }
} else {
    // No se ha recibido el ID del paciente por POST
    echo json_encode(array('success' => false));
}

// Cerrar la conexión
$conn->close();
?>