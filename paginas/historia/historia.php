<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Historias</title>
</head>
<body>
    <h2>Historias Registradas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Fecha</th>
        </tr>
        <?php
        // Conexión a la base de datos
        include '..\config.php';

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para obtener todas las historias
        $sql = "SELECT * FROM historia WHERE activo=TRUE";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar las historias en una tabla
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["codigo"] . "</td>";
                echo "<td>" . $row["fecha"] . "</td>";
                echo "<td>";
                echo "<a href='eliminar_hist.php?id=" . $row["id"] . "'>Eliminar</a>";
                echo "<a href='actualizar_hist.php?id=" . $row["id"] . "'>Editar</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No se encontraron historias</td></tr>";
        }

        // Cerrar la conexión
        $conn->close();
        ?>
    </table>
</body>
</html>