<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Servicio</title>
</head>
<body>
    <h2>Crear Servicio</h2>
    <form action="op_serv.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="nombre">Nombre del Servicio:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
        <input type="submit" value="Crear">
    </form>
</body>
</html>