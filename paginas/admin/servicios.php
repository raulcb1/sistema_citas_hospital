<?php
include 'config.php';

// Validar permisos de admin
if ($_SESSION['rol'] != 'admin') {
    header("Location: ../sin_permisos.php");
    exit();
}

// Crear nuevo rol
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_servicio'])) {
    $nombre = $_POST['nombre'];
    $turno = $_POST['turno'];
    $stmt = $conn->prepare("INSERT INTO servicios (nombre, turno) VALUES (?,?)");
    $stmt->bind_param("ss", $nombre, $turno);
    $stmt->execute();
}

// Editar roles
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_servicio'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $stmt = $conn->prepare("UPDATE servicios SET nombre = ? WHERE  id = ?");
    $stmt->bind_param("si", $nombre, $id);
    $stmt->execute();
}

// Eliminar rol
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conn->prepare("UPDATE servicios SET active = '0' WHERE  id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Obtener todos los roles
$roles = $conn->query("SELECT * FROM servicios");
?>
<section class="content-header">
    <h1>Servicios del Establecimiento</h1>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearRol">
                <i class="fas fa-plus"></i> Nuevo Servicios
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rol = $roles->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $rol['id']; ?></td>
                        <td><?php echo $rol['nombre']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm btn-editar" data-id="<?php echo $rol['id']; ?>"
                                data-nombre="<?php echo $rol['nombre']; ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?eliminar=<?php echo $rol['id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Eliminar este servicio?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal para crear rol -->
<div class="modal fade" id="modalCrearRol">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Crear Nuevo Servicio</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Servicio:</label>
                        <input type="text" name="nombre" class="form-control" required>
                        <input type="hidden" name="pagina" value="admin_servicios">
                        <label>Turno</label>
                        <select name="turno" id="turno" required>
                            <option value="MAÑANA">MAÑANA</option>
                            <option value="TARDE">TARDE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="crear_servicio" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar Servicio -->
<div class="modal fade" id="modalEditarServicio">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Servicio</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editarServicioId">
                    <div class="form-group">
                        <label>Nombre del Servicio:</label>
                        <input type="text" name="nombre" id="editarServicioNombre" class="form-control" required>
                        <input type="hidden" name="pagina" value="admin_servicios">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="editar_servicio" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btn-editar').on('click', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');

        $('#editarServicioId').val(id);
        $('#editarServicioNombre').val(nombre);
        $('#modalEditarServicio').modal('show');
    });
});
</script>