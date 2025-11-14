<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Pacientes</title>
</head>
<?php
// Incluir archivo de configuración
include '..\config.php';
?>
<body>
    <h2>Listar Pacientes</h2>
    <form action="crear_pac.php" method="post">
    <input type="submit" value="Crear">
    </form>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido Materno</th>
                <th>Apellido Paterno</th>
                <th>Fecha de Nacimiento</th>
                <th>Teléfono</th>
                <th>UPS</th>
                <th>Seguro</th>
                <th>Historia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta SQL para obtener todos los registros de la tabla 'pacientes'
            $sql = "SELECT pacientes.*, ups.nombre AS ups_nombre, seguro.nombre AS seguro_nombre FROM pacientes
                    LEFT JOIN ups ON pacientes.ups_id = ups.id
                    LEFT JOIN seguro ON pacientes.seguro_id = seguro.id
                    WHERE pacientes.activo = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostrar los datos en la tabla
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["dni"] . "</td>";
                    echo "<td>" . $row["nombre"] . "</td>";
                    echo "<td>" . $row["apellido_m"] . "</td>";
                    echo "<td>" . $row["apellido_p"] . "</td>";
                    echo "<td>" . $row["fecha_nac"] . "</td>";
                    echo "<td>" . $row["telefono"] . "</td>";
                    echo "<td>" . $row["ups_nombre"] . "</td>";
                    echo "<td>" . $row["seguro_nombre"] . "</td>";
                    echo "<td>" . $row["historia_id"] . "</td>";
                    echo "<td>";
                    echo "<form action='editar_paciente.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' value='Editar'>";
                    echo "</form>";
                    echo "<form action='desactivar_paciente.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' value='Desactivar'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No se encontraron resultados</td></tr>";
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>