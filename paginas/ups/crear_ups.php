<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear UPS</title>
</head>
<body>
    <h2>Crear UPS</h2>
    <form action="op_ups.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="codigo_ups">Código UPS:</label>
        <input type="text" id="codigo_ups" name="codigo_ups" required><br><br>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br><br>

        <label for="departamento">Departamento:</label>
        <input type="text" id="departamento" name="departamento"><br><br>

        <label for="provincia">Provincia:</label>
        <input type="text" id="provincia" name="provincia"><br><br>
       

        <label for="distrito">Distrito:</label>
        <input type="text" id="distrito" name="distrito"><br><br>

        <input type="submit" value="Crear">
    </form>
</body>
</html>