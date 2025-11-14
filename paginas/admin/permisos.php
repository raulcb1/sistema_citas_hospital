<?php
include 'config.php';

if ($_SESSION['rol'] != 'admin') {
    header("Location: ../sin_permisos.php");
    exit();
}

// Asignar/eliminar permisos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rol_id = $_POST['rol_id'];
    $pagina_id = $_POST['pagina_id'];

    if (isset($_POST['asignar'])) {
        $stmt = $conn->prepare("INSERT INTO permisos (rol_id, pagina_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $rol_id, $pagina_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM permisos WHERE rol_id = ? AND pagina_id = ?");
        $stmt->bind_param("ii", $rol_id, $pagina_id);
    }
    $stmt->execute();
}

// Obtener datos
$roles = $conn->query("SELECT * FROM roles");
$paginas = $conn->query("SELECT * FROM paginas");
?>

<section class="content-header">
    <h1>Permisos</h1>
</section>

<section class="content">
<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-5">
                    <label>Rol:</label>
                    <select name="rol_id" class="form-control" required>
                        <?php while ($rol = $roles->fetch_assoc()): ?>
                        <option value="<?php echo $rol['id']; ?>"><?php echo $rol['nombre']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label>Página:</label>
                    <select name="pagina_id" class="form-control" required>
                        <?php while ($pagina = $paginas->fetch_assoc()): ?>
                        <option value="<?php echo $pagina['id']; ?>"><?php echo $pagina['nombre']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="hidden" name="pagina" value="admin_permisos">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <div class="btn-group d-flex">
                        <button type="submit" name="asignar" class="btn btn-success">Asignar</button>
                        <button type="submit" name="eliminar" class="btn btn-danger">Quitar</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Listar permisos existentes -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Rol</th>
                    <th>Página</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $permisos = $conn->query("
                    SELECT r.nombre AS rol, p.nombre AS pagina 
                    FROM permisos pm
                    INNER JOIN roles r ON pm.rol_id = r.id
                    INNER JOIN paginas p ON pm.pagina_id = p.id
                ");
                while ($permiso = $permisos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $permiso['rol']; ?></td>
                    <td><?php echo $permiso['pagina']; ?></td>
                    <td>
                        <a href="?eliminar=..." class="btn btn-danger btn-sm">Quitar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</section>