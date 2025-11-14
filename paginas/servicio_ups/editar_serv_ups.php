<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicio UPS</title>
</head>
<?php
// Incluir archivo de configuración
include '..\config.php';
?>
<body>
    <h2>Editar Servicio UPS</h2>
    <?php
    // Verificar si se ha recibido el ID del servicio UPS a editar
    if(isset($_POST['id'])) {
        $id = $_POST['id'];

        // Consultar la información del servicio UPS a editar
        $sql = "SELECT * FROM servicio_ups WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el formulario de edición con la información del servicio UPS
            $row = $result->fetch_assoc();
            ?>
            <form action="op_serv_ups.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="ups_id">UPS:</label>
                <select id="ups_id" name="ups_id">
                    <?php
                    // Consultar la tabla de UPS para obtener los nombres
                    $sql_ups = "SELECT * FROM ups WHERE activo=1";
                    $result_ups = $conn->query($sql_ups);
                    while($row_ups = $result_ups->fetch_assoc()) {
                        $selected = ($row['ups_id'] == $row_ups['id']) ? 'selected' : '';
                        echo "<option value='" . $row_ups["id"] . "' $selected>" . $row_ups["nombre"] . "</option>";
                    }
                    ?>
                </select><br><br>
                <label for="servicio_id">Servicio:</label>
                <select id="servicio_id" name="servicio_id">
                    <?php
                    // Consultar la tabla de UPS para obtener los nombres
                    $sql_ups = "SELECT * FROM servicios WHERE activo=1";
                    $result_ups = $conn->query($sql_ups);
                    while($row_ups = $result_ups->fetch_assoc()) {
                        $selected = ($row['servicio_id'] == $row_ups['id']) ? 'selected' : '';
                        echo "<option value='" . $row_ups["id"] . "' $selected>" . $row_ups["nombre"] . "</option>";
                    }
                    ?>
                </select><br><br>
                <input type="submit" value="Editar">
            </form>
            <?php
        } else {
            echo "No se encontró el servicio UPS a editar";
        }
    } else {
        echo "No se ha recibido el ID del servicio UPS a editar";
    }
    ?>
</body>
</html>