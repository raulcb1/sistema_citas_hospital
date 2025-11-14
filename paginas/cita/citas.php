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
?>
<?php if(isset($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>
<section class="content-header">
    <div class="container-fluid">
        <h2>Citas</h2>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Citas</h3>
                <div class="card-tools">
                    <form action="index.php" method="post">
                        <input type="hidden" name="pagina" value="paginas/cita/crear_cita.php">
                        <button type="submit" class="btn btn-block btn-success btn-m">Generar Cita</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
            <div class="row">
            <div class="col-sm-12 col-md-6">
            <input type="date" id="filtro-fecha"><button class="btn btn-primary btn-sm" id="limpiar-filtro">Limpiar Filtro</button>
            </div></div>
            <div class="row"><p></p></div>
                <!-- <table id="tabla-citas" class="table table-bordered table-sm dataTable"> -->
                <table id="tabla-citas" name="tabla-citas" class="table table-bordered table-hover">
                    <?php $_SESSION['datatable'] = "citas";?>
                    <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>PACIENTE</th>
                            <th>DNI</th>
                            <!-- <th>EESS</th> -->
                            <th>SERVICIO</th>
                            <th>TURNO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Consultar la tabla de Citas y obtener los datos
                            $sql = "SELECT 
                                        c.id, 
                                        p.apellido_p, 
                                        p.apellido_m, 
                                        p.nombre AS nombre_p, 
                                        p.dni, 
                                        u.nombre AS nombre_ups, 
                                        s.nombre as nombre_servicio, 
                                        s.turno, 
                                        c.fecha_cita 
                                    FROM asignacion_citas c 
                                    INNER JOIN pacientes p ON c.paciente_id = p.id 
                                    INNER JOIN servicio_ups su ON c.servicio_id = su.id 
                                    INNER JOIN ups u ON su.ups_id = u.id 
                                    INNER JOIN servicios s ON su.servicio_id = s.id
                                    ORDER BY c.fecha_cita ASC";
                            $result = $conn->query($sql);

                            // Mostrar los datos en la tabla
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["fecha_cita"] . "</td>";
                                    echo "<td>" . $row["apellido_p"] . " " . $row["apellido_m"] . ", " . $row["nombre_p"] . "</td>";
                                    echo "<td>" . $row["dni"] . "</td>";
                                    //echo "<td>" . $row["nombre_ups"] . "</td>";
                                    echo "<td>" . $row["nombre_servicio"] . "</td>";
                                    echo "<td>" . $row["turno"] . "</td>";
                                    echo "<td><a href=\"?eliminar=" . $row["id"] . "\" class=\"btn btn-danger btn-xs\">Eliminar</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No se encontraron citas</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>