<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Seguros</title>
</head>
<body>
    <h2>Seguros Registrados</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php
        // Conexión a la base de datos
        include '..\config.php';

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para obtener todos los seguros
        $sql = "SELECT * FROM seguro WHERE activo=TRUE";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar los seguros en una tabla
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "<td>";
                echo "<a href='eliminar_seguro.php?id=" . $row["id"] . "'>Eliminar</a>";
                echo "<a href='actualizar_seguro.php?id=" . $row["id"] . "'>Editar</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No se encontraron seguros</td></tr>";
        }

        // Cerrar la conexión
        $conn->close();
        ?>
    </table>
</body>
</html>