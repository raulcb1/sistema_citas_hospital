<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Seguro</title>
</head>
<body>
    <h2>Editar Seguro</h2>
    <?php
    // Verificar si se ha enviado un ID de seguro para editar
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        // Conexión a la base de datos
        include '..\config.php';

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para obtener los datos del seguro a editar
        $sql = "SELECT * FROM seguro WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el formulario con los datos del seguro seleccionado
            $row = $result->fetch_assoc();
            ?>
            <form action="actualizar_seguro.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" required><br><br>
                <input type="submit" value="Actualizar Seguro">
            </form>
            <?php
        } else {
            echo "No se encontró el seguro";
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        echo "ID de seguro no proporcionado";
    }
    ?>
</body>
</html>