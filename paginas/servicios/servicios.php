<?php
//include 'config.php'; // Ruta correcta al archivo config.php
// Función para eliminar una cita
function eliminarCita($id) {
    global $conn;
    $sql = "DELETE FROM cita WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Consulta principal de citas
$query = "SELECT c.id, p.apellido_p, p.apellido_m, p.nombre, p.dni, c.fecha_cita, c.motivo as motivo
          FROM cita c
          JOIN pacientes p ON c.paciente_id = p.id
          JOIN servicio_ups s ON c.servicio_ups_id = s.id
          JOIN servicios sv ON servicio_ups_id = sv.id
          ORDER BY c.fecha_cita DESC";

$result = $conn->query($query);
?>
<section class="content-header">
    <h2>Listar Servicios</h2>
    <form action="crear_serv.php" method="post">
    <input type="submit" value="Crear">
    </form>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Servicio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta SQL para obtener todos los registros de la tabla 'servicios'
            $sql = "SELECT * FROM servicios WHERE activo = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostrar los datos en la tabla
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["nombre"] . "</td>";
                    echo "<td>";
                    echo "<form action='editar_serv.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' class='btn btn-alert btn-xs' value='Editar'>";
                    echo "</form>";
                    echo "<form action='eliminar_serv.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' class='btn btn-danger btn-xs' value='Eliminar'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No se encontraron resultados</td></tr>";
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </tbody>
    </table>
</section>