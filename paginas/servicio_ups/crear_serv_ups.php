<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Servicios a Establecimientos de Salud</title>
</head>
<?php
// Incluir archivo de configuración
include '../config.php';
?>
<body>
    <h2>Crear Servicio UPS</h2>
    <form action="op_serv_ups.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="ups_id">Seleccione Establecimiento de Salud:</label>
        <select id="ups_id" name="ups_id">
            <?php
            // Consultar la tabla de UPS para obtener los nombres
            $sql_ups = "SELECT * FROM ups WHERE activo=1";
            $result_ups = $conn->query($sql_ups);
            while($row_ups = $result_ups->fetch_assoc()) {
                echo "<option value='" . $row_ups["id"] . "'>" . $row_ups["nombre"] . "</option>";
            }
            ?>
        </select><br><br>
        <label for="servicio_id">Servicio ID:</label>
        <select id="servicio_id" name="servicio_id">
            <?php
            // Consultar la tabla de UPS para obtener los nombres
            $sql_ups = "SELECT * FROM servicios WHERE activo=1";
            $result_ups = $conn->query($sql_ups);
            while($row_ups = $result_ups->fetch_assoc()) {
                echo "<option value='" . $row_ups["id"] . "'>" . $row_ups["nombre"] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Asignar">
    </form>
</body>
</html>