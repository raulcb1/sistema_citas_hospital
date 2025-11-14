<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Historia</title>
</head>
<body>
    <h2>Editar Historia</h2>
    <?php
    // Verificar si se ha enviado un ID de historia para editar
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        // Conexión a la base de datos
        include '..\config.php';

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para obtener los datos de la historia a editar
        $sql = "SELECT * FROM historia WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el formulario con los datos de la historia seleccionada
            $row = $result->fetch_assoc();
            ?>
            <form action="actualizar_hist.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="codigo">Código:</label>
                <input type="text" id="codigo" name="codigo" value="<?php echo $row['codigo']; ?>" required><br><br>
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo $row['fecha']; ?>" required><br><br>
                <input type="submit" value="Actualizar Historia">
            </form>
            <?php
        } else {
            echo "No se encontró la historia";
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        echo "ID de historia no proporcionado";
    }
    ?>
</body>
</html>