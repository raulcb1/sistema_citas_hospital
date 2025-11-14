<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Registros UPS</title>
</head>
<body>
    <h2>Registros UPS</h2>
    <form action="crear_ups.php" method="post">
    <input type="submit" value="Crear">
    </form>
    <table border="1">
        <tr>
            <th>Código UPS</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Departamento</th>
            <th>Provincia</th>
            <th>Distrito</th>
            <th>Acciones</th>
        </tr>
        <?php
        // Conexión a la base de datos
        include '..\config.php';
        // $conn = new mysqli("localhost", "usuario", "contraseña", "basededatos");

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para obtener todos los registros de la tabla ups
        $sql = "SELECT * FROM ups WHERE activo=TRUE";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar los registros en una tabla
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["codigo_ups"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "<td>" . $row["direccion"] . "</td>";
                echo "<td>" . $row["departamento"] . "</td>";
                echo "<td>" . $row["provincia"] . "</td>";
                echo "<td>" . $row["distrito"] . "</td>";
                echo "<td>";

                echo "<form action='editar_ups.php' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<input type='submit' value='Editar'></form>";

                echo "<form action='eliminar_ups.php' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<input type='submit' value='Eliminar'></form>";

                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No se encontraron registros</td></tr>";
        }

        // Cerrar la conexión
        $conn->close();
        ?>
    </table>
</body>
</html>