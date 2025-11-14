<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Tipos de Atención</title>
</head>
<body>
    <h2>Listar Tipos de Atención</h2>
    <form action="crear_ta.php" method="post">
    <input type="submit" value="Crear">
    </form>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo de Atención</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Conexión a la base de datos
            include '..\config.php';

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            // Consulta SQL para obtener todos los registros de la tabla 'tipo_atencion'
            $sql = "SELECT * FROM tipo_atencion WHERE activo = TRUE";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostrar los datos en la tabla
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["tipo"] . "</td>";
                    echo "<td>";
                    echo "<form action='editar_ta.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' value='Editar'>";
                    echo "</form>";
                    echo "<form action='eliminar_ta.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' value='Eliminar'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No se encontraron resultados</td></tr>";
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>