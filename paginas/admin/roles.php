<?php
include 'config.php';

// Validar permisos de admin
if ($_SESSION['rol'] != 'admin') {
    header("Location: ../sin_permisos.php");
    exit();
}

// Crear nuevo rol
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_rol'])) {
    $nombre = $_POST['nombre'];
    $stmt = $conn->prepare("INSERT INTO roles (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
}

// Editar roles
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_rol'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $stmt = $conn->prepare("UPDATE roles SET nombre = ? WHERE  id = ?");
    $stmt->bind_param("si", $nombre, $id);
    $stmt->execute();
}

// Eliminar rol
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conn->prepare("DELETE FROM roles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Obtener todos los roles
$roles = $conn->query("SELECT * FROM roles");
?>
<section class="content-header">
    <h1>Roles del Sistema</h1>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearRol">
                <i class="fas fa-plus"></i> Nuevo Rol
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
                            <a href="#" class="btn btn-warning btn-sm btn-editar" data-id="<?php echo $rol['id']; ?>" data-nombre="<?php echo $rol['nombre']; ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?eliminar=<?php echo $rol['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este rol?')">
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
                <h4 class="modal-title">Crear Nuevo Rol</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Rol:</label>
                        <input type="text" name="nombre" class="form-control" required>
                        <input type="hidden" name="pagina" value="admin_roles">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="crear_rol" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar rol -->
<div class="modal fade" id="modalEditarRol">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Rol</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editarRolId">
                    <div class="form-group">
                        <label>Nombre del Rol:</label>
                        <input type="text" name="nombre" id="editarRolNombre" class="form-control" required>
                        <input type="hidden" name="pagina" value="admin_roles">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="editar_rol" class="btn btn-primary">Guardar Cambios</button>
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

        $('#editarRolId').val(id);
        $('#editarRolNombre').val(nombre);
        $('#modalEditarRol').modal('show');
    });
});
</script>