<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desactivar Tipo de Atención</title>
</head>
<?php
if(isset($_POST['id'])) {
    $id = $_POST['id'];}
?>
<body>
    <h2>Desactivar Tipo de Atención</h2>
    <form action="op_ta.php" method="post">
        <input type="hidden" name="action" value="delete">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" value="<?php echo $id; ?>" required><br><br>
        <input type="submit" value="Desactivar">
    </form>
</body>
</html>