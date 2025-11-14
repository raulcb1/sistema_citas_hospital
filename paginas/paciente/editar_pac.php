<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
</head>
<body>
    <h2>Editar Paciente</h2>
    <?php
    // Incluir archivo de configuración
    include '..\config.php';

    // Verificar si se ha recibido el ID del paciente a editar
    if(isset($_POST['id'])) {
        $id = $_POST['id'];

        // Consultar la información del paciente a editar
        $sql = "SELECT * FROM pacientes WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el formulario de edición con la información del paciente
            $row = $result->fetch_assoc();
            ?>
            <form action="op_pac.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" value="<?php echo $row['dni']; ?>" required><br><br>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" required><br><br>
                <label for="apellido_m">Apellido Materno:</label>
                <input type="text" id="apellido_m" name="apellido_m" value="<?php echo $row['apellido_m']; ?>" required><br><br>
                <label for="apellido_p">Apellido Paterno:</label>
                <input type="text" id="apellido_p" name="apellido_p" value="<?php echo $row['apellido_p']; ?>" required><br><br>
                <label for="fecha_nac">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nac" name="fecha_nac" value="<?php echo $row['fecha_nac']; ?>" required><br><br>
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo $row['telefono']; ?>"><br><br>
                <label for="ups_id">UPS:</label>
                <select id="ups_id" name="ups_id">
                    <?php
                    // Consultar la tabla de UPS para obtener los nombres
                    $sql_ups = "SELECT * FROM ups";
                    $result_ups = $conn->query($sql_ups);
                    while($row_ups = $result_ups->fetch_assoc()) {
                        $selected = ($row['ups_id'] == $row_ups['id']) ? 'selected' : '';
                        echo "<option value='" . $row_ups["id"] . "' $selected>" . $row_ups["nombre"] . "</option>";
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
                        $selected = ($row['seguro_id'] == $row_seguro['id']) ? 'selected' : '';
                        echo "<option value='" . $row_seguro["id"] . "' $selected>" . $row_seguro["nombre"] . "</option>";
                    }
                    ?>
                </select><br><br>
                <label for="historia_id">Historia:</label>
                <input type="text" id="historia_id" name="historia_id" value="<?php echo $row['historia_id']; ?>"><br><br>
                <input type="submit" value="Editar">
            </form>
            <?php
        } else {
            echo "No se encontró el paciente a editar";
        }
    } else {
        echo "No se ha recibido el ID del paciente a editar";
    }
    ?>
</body>
</html>