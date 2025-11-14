<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Paciente</title>
</head>
<?php
// Incluir archivo de configuración
include '..\config.php';
?>
<body>
    <h2>Crear Paciente</h2>
    <form action="op_pac.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required><br><br>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
        <label for="apellido_p">Apellido Paterno:</label>
        <input type="text" id="apellido_p" name="apellido_p" required><br><br>
        <label for="apellido_m">Apellido Materno:</label>
        <input type="text" id="apellido_m" name="apellido_m" required><br><br>
        <label for="fecha_nac">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nac" name="fecha_nac" required><br><br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono"><br><br>
        <label for="ups_id">UPS:</label>
        <select id="ups_id" name="ups_id">
            <?php
            // Consultar la tabla de UPS para obtener los nombres
            $sql_ups = "SELECT * FROM ups";
            $result_ups = $conn->query($sql_ups);
            while($row_ups = $result_ups->fetch_assoc()) {
                echo "<option value='" . $row_ups["id"] . "'>" . $row_ups["nombre"] . "</option>";
            }
            ?>
        </select><br><br>
        <label for="seguro_id">Seguro:</label>
        <select id="seguro_id" name="seguro_id">
            <?php
            // Consultar la tabla de Seguros para obtener los nombres
            $sql_seguro = "SELECT * FROM seguro";
            $result_seguro = $conn->query($sql_seguro);
            while($row_seguro = $result_seguro->fetch_assoc()) {
                echo "<option value='" . $row_seguro["id"] . "'>" . $row_seguro["nombre"] . "</option>";
            }
            ?>
        </select><br><br>
        <label for="historia_id">Historia:</label>
        <select id="historia_id" name="historia_id">
            <?php
            // Consultar la tabla de Seguros para obtener los nombres
            $sql_seguro = "SELECT * FROM historia";
            $result_seguro = $conn->query($sql_seguro);
            while($row_seguro = $result_seguro->fetch_assoc()) {
                echo "<option value='" . $row_seguro["id"] . "'>" . $row_seguro["codigo"] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Crear">
    </form>
</body>
</html>