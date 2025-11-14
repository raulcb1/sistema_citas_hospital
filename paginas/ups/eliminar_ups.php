<?php
if(isset($_POST['id'])) {
    $id = $_POST['id'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desactivar UPS</title>
</head>
<body>
    <h2>Desactivar UPS</h2>
    <form action="op_ups.php" method="post">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id"  value="<?php echo $id; ?>" required><br><br>
        <input type="hidden" name="action" value="delete">
        <input type="submit" value="Eliminar">
    </form>
</body>
</html>