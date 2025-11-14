<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desactivar Estado de Cita</title>
</head>
<body>
    <h2>Desactivar Estado de Cita</h2>
    <form action="op_ec.php?action=delete" method="post">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" required>
        <input type="submit" value="Desactivar">
    </form>
</body>
</html>