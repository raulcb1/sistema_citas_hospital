<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tipo de Atención</title>
</head>
<body>
    <h2>Crear Tipo de Atención</h2>
    <form action="op_ta.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="tipo">Tipo de Atención:</label>
        <input type="text" id="tipo" name="tipo" required>
        <input type="submit" value="Crear">
    </form>
</body>
</html>