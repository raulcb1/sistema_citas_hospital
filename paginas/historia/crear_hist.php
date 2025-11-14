<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Historia</title>
</head>
<body>
    <h2>Crear Historia</h2>
    <form action="crear_historia_action.php" method="post">
        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" required><br><br>
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>
        <input type="submit" value="Crear Historia">
    </form>
</body>
</html>