<?php
include 'config.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: ../sin_permisos.php");
    exit();
}

/*
if (!empty($_POST)) {
    echo "<ul>";
    foreach ($_POST as $key => $value) {
        echo "<li><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</li>";
    }
    echo "</ul>";
    } else {
        echo "<p>No se han enviado datos mediante GET.</p>";
    }
*/  


// Crear nueva página
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_pagina'])) {
    $nombre = $_POST['nombre'];
    $nombre_menu = $_POST['nombre_menu'];
    $ruta = $_POST['ruta'];
    $stmt = $conn->prepare("INSERT INTO paginas (nombre, nombre_menu, ruta) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $nombre_menu, $ruta);
    $stmt->execute();
}

// Editar página
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_pagina'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $nombre_menu = $_POST['nombre_menu'];
    $ruta = $_POST['ruta'];
    $stmt = $conn->prepare("UPDATE paginas SET nombre = ?, nombre_menu = ?, ruta = ? WHERE  id = ?");
    $stmt->bind_param("sssi", $nombre, $nombre_menu, $ruta, $id);
    $stmt->execute();
}



// Eliminar página
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conn->prepare("UPDATE paginas SET activo='0' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Obtener todas las páginas
$paginas = $conn->query("SELECT * FROM paginas");
?>


<section class="content-header">
    <h1>Páginas del Sistema</h1>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearRol">
                <i class="fas fa-plus"></i> Nueva Página
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Corto</th>
                        <th>Nombre Menú</th>
                        <th>Ruta</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pagina = $paginas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $pagina['id']; ?></td>
                        <td><?php echo $pagina['nombre']; ?></td>
                        <td><?php echo $pagina['nombre_menu']; ?></td>
                        <td><?php echo $pagina['ruta']; ?></td>
                        <td><?php echo $pagina['activo']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm btn-editar" data-id="<?php echo $pagina['id']; ?>" data-pncorto="<?php echo $pagina['nombre']; ?>" data-pnmenu="<?php echo $pagina['nombre_menu']; ?>" data-pruta="<?php echo $pagina['ruta']; ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?eliminar=<?php echo $pagina['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este rol?')">
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
                <h4 class="modal-title">Crear Nueva Página</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre para menú:</label>
                        <input type="text" name="nombre_menu" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre código:</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ruta:</label>
                        <input type="text" name="ruta" class="form-control" required>
                        <input type="hidden" name="pagina" value="admin_paginas">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="crear_pagina" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar páginas -->
<div class="modal fade" id="modalEditarPagina">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Página</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editarPaginaId">
                    <div class="form-group">
                        <label>Nombre corto:</label>
                        <input type="text" name="nombre" id="editarPNcorto" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre menú:</label>
                        <input type="text" name="nombre_menu" id="editarPNmenu" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ruta:</label>
                        <input type="text" name="ruta" id="editarPruta" class="form-control" required>
                        <input type="hidden" name="pagina" value="admin_paginas">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="editar_pagina" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.btn-editar').on('click', function() {
        var id = $(this).data('id');
        var pncorto = $(this).data('pncorto');
        var pnmenu = $(this).data('pnmenu');
        var pruta = $(this).data('pruta');

        $('#editarPaginaId').val(id);
        $('#editarPNcorto').val(pncorto);
        $('#editarPNmenu').val(pnmenu);
        $('#editarPruta').val(pruta);
        $('#modalEditarPagina').modal('show');
    });
});
</script>