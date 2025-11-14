<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Estados de Cita</title>
</head>
<body>
    <h2>Listar Estados de Cita</h2>
    <?php
    // Conexión a la base de datos
    include '..\config.php';

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para obtener todos los estados de cita
    $sql_select = "SELECT * FROM estado_cita WHERE activo=TRUE";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        // Mostrar los estados de cita
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>" . $row["estado"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron estados de cita</p>";
    }

    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>