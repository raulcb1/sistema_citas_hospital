<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Servicios asignados a los Establecimientos de Salud</title>
</head>
<body>
    <h2>Listado de Servicios asignados a los Establecimientos de Salud</h2>
    <form action="crear_serv_ups.php" method="post">
    <input type="submit" value="Crear">
    </form>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>UPS</th>
                <th>Servicio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Incluir archivo de configuración
            include '..\config.php';

            // Consulta SQL para obtener todos los registros de la tabla 'servicio_ups'
            $sql="SELECT servicio_ups.*, ups.nombre AS ups_nombre, servicios.nombre AS servicio_nombre FROM servicio_ups
            LEFT JOIN ups ON servicio_ups.ups_id = ups.id
            LEFT JOIN servicios ON servicio_ups.servicio_id = servicios.id
            WHERE servicio_ups.activo = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostrar los datos en la tabla
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["ups_nombre"] . "</td>";
                    echo "<td>" . $row["servicio_nombre"] . "</td>";
                    echo "<td>";
                    echo "<form action='editar_serv_ups.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' value='Editar'>";
                    echo "</form>";
                    echo "<form action='eliminar_serv_ups.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' value='Desactivar'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No se encontraron resultados</td></tr>";
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>