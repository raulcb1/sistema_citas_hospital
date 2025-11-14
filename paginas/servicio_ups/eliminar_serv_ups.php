<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desactivar Servicio UPS</title>
</head>
<body>
    <h2>Desactivar Servicio UPS</h2>
    <form action="op_serv_ups.php" method="post">
        <input type="hidden" name="action" value="delete">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" required><br><br>
        <input type="submit" value="Desactivar">
    </form>
</body>
</html>