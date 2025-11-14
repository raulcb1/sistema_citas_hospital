<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estado de Cita</title>
</head>
<body>
    <h2>Editar Estado de Cita</h2>
    <form action="op_ec.php?action=edit" method="post">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" required><br><br>
        <label for="estado">Nuevo Estado:</label>
        <input type="text" id="estado" name="estado" required>
        <input type="submit" value="Editar">
    </form>
</body>
</html>