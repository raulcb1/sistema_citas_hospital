<?php
include '../config.php';

if(isset($_POST['dni'])) {
    $dni = $_POST['dni'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE dni = ?");
    $stmt->bind_param("i", $dni);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    if($count > 0) {
        echo "<span style='color: red;'>El DNI ya está en uso.</span>";
    } else {
        echo "<span style='color: green;'>El DNI está disponible.</span>";
    }

    $stmt->close();
    $conn->close();
}
?>
