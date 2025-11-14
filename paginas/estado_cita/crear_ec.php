<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Estado de Cita</title>
</head>
<body>
    <h2>Crear Estado de Cita</h2>
    <form action="op_ec.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required>
        <input type="submit" value="Crear">
    </form>
</body>
</html>