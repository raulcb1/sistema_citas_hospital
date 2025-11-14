<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tipo de Atención</title>
</head>
<body>
    <h2>Editar Tipo de Atención</h2>
    <?php
    // Verificar si se ha recibido el ID del tipo de atención a editar
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Conexión a la base de datos
        include '..\config.php';

        // Consultar la información del tipo de atención a editar
        $sql = "SELECT * FROM tipo_atencion WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el formulario de edición con la información del tipo de atención
            $row = $result->fetch_assoc();
            ?>
            <form action="op_ta.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="tipo">Tipo de Atención:</label>
                <input type="text" id="tipo" name="tipo" value="<?php echo $row['tipo']; ?>" required><br><br>
                <input type="submit" value="Editar">
            </form>
            <?php
        } else {
            echo "No se encontró el tipo de atención a editar";
        }
    } else {
        echo "No se ha recibido el ID del tipo de atención a editar";
    }
    ?>
</body>
</html>