<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicio</title>
</head>
<body>
    <h2>Editar Servicio</h2>
    <?php
    // Verificar si se ha recibido el ID del servicio a editar
    if(isset($_POST['id'])) {
        $id = $_POST['id'];

        // Conexión a la base de datos
        include '..\config.php';

        // Consultar la información del servicio a editar
        $sql = "SELECT * FROM servicios WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el formulario de edición con la información del servicio
            $row = $result->fetch_assoc();
            ?>
            <form action="op_serv.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="nombre">Nombre del Servicio:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" required><br><br>
                <input type="submit" value="Editar">
            </form>
            <?php
        } else {
            echo "No se encontró el servicio a editar";
        }
    } else {
        echo "No se ha recibido el ID del servicio a editar";
    }
    ?>
</body>
</html>