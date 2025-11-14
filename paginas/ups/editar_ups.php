<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Registro UPS</title>
</head>
<body>
    <h2>Actualizar Registro UPS</h2>
    <?php
    // Verificar si se ha enviado un ID de registro para actualizar
    if(isset($_POST['id'])) {
        $id = $_POST['id'];

        // Conexión a la base de datos
        include '..\config.php';
        //$conn = new mysqli("localhost", "usuario", "contraseña", "basededatos");

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para obtener los datos del registro a actualizar
        $sql = "SELECT * FROM ups WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Mostrar el formulario con los datos del registro seleccionado
            $row = $result->fetch_assoc();
            ?>
            <form action="op_ups.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="codigo_ups">Código UPS:</label>
                <input type="text" id="codigo_ups" name="codigo_ups" value="<?php echo $row['codigo_ups']; ?>" required><br><br>
                
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" required><br><br>
                
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo $row['direccion']; ?>" required><br><br>
                
                <label for="departamento">Departamento:</label>
                <input type="text" id="departamento" name="departamento" value="<?php echo $row['departamento']; ?>" required><br><br>
                
                <label for="provincia">Provincia:</label>
                <input type="text" id="provincia" name="provincia" value="<?php echo $row['provincia']; ?>" required><br><br>
                
                <label for="distrito">Distrito:</label>
                <input type="text" id="distrito" name="distrito" value="<?php echo $row['distrito']; ?>" required><br><br>
                
                <input type="submit" value="Actualizar Registro">
            </form>
            <?php
        } else {
            echo "No se encontró el registro";
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        echo "ID de registro no proporcionado";
    }
    ?>
</body>
</html>