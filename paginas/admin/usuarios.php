<?php
include 'config.php';

// Verificar permisos de admin
if ($_SESSION['rol'] != 'admin') {
    header("Location: ../sin_permisos.php");
    exit();
}

// Crear/Actualizar usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //transaccion SQL
    $conn->begin_transaction();
    
    try {
        $id = $_POST['id'] ?? null;
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellido_p = $_POST['apellido_p'];
        $apellido_m = $_POST['apellido_m'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $rol_id = $_POST['rol_id'];
        $cmp = $_POST['cmp'] ?? null;
        $especialidad = $_POST['especialidad'] ?? null;
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        //echo "id: $id, dni: $dni, nombre: $nombre, apellido_p: $apellido_p, apellido_m: $apellido_m, email: $email, telefono: $telefono, rol_id: $rol_id, cmp: $cmp, especialidad: $especialidad, password: $password";


        if ($id) {
            // Actualizar usuario
            $sql = "UPDATE usuarios SET dni=?, nombre=?, apellido_p=?, apellido_m=?, email=?, telefono=?, rol_id=?" . 
                   ($password ? ", password=?" : "") . " WHERE id=?";
            $stmt = $conn->prepare($sql);

            // Crear arreglo de parámetros
            $params1 = [$dni, $nombre, $apellido_p, $apellido_m, $email, $telefono, $rol_id];
            $types = 'sssssss';

            if ($password) {
                $params1[] = $password;
                $types .= 's';
            }
            $params1[] = $id;
            $types .= 'i';

            // Convertir arreglo de parámetros a referencias
            $params->bind_param($types, ...$params1);

            $mensaje= $sql;

        } else {
            // Crear usuario
            //echo "entró a crear usuario";
            $sql = "INSERT INTO usuarios (dni, nombre, apellido_p, apellido_m, email, telefono, rol_id, password) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = $conn->prepare($sql);
            $params->bind_param("ssssssss", $dni, $nombre, $apellido_p, $apellido_m, $email, $telefono, $rol_id, $password);
            //echo "</br> Crea usuario: Dni: $dni, nombre: $nombre, apellido_p: $apellido_p, apellido_m: $apellido_m, email: $email, telefono: $telefono, rol_id: $rol_id, password: $password";
            //$mensaje= $sql;
        }

        if ($params->execute()) {
            $nuevo_id = $conn->insert_id;
        } else {
            echo "Error: " . $params->error;
            //echo "</br> $mensaje </br>";
        }
        
        // Si es médico
        if ($rol_id == 3) { // Suponiendo que 3 es el ID de rol médico
            if ($id) {
                $sql_medico = "UPDATE medicos SET cmp=?, especialidad=? WHERE usuario_id=?";
                $stmt_medico = $conn->prepare($sql_medico);
                $stmt_medico->bind_param("ssi", $cmp, $especialidad, $id);
            } else {
                $nuevo_id = $conn->insert_id;
                $sql_medico = "INSERT INTO medicos (usuario_id, cmp, especialidad) VALUES (?, ?, ?)";
                $stmt_medico = $conn->prepare($sql_medico);
                //echo "</br> variables medico: id: $nuevo_id, cmp: $cmp, especialidad: $especialidad";
                $stmt_medico->bind_param("iss", $nuevo_id, $cmp, $especialidad);
            }
            $stmt_medico->execute();
        }
        
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        //echo "se murió";
        die("Error: " . $e->getMessage());
    } finally {
        $stmt->close();
        if ($stmt_medico) {
            $stmt_medico->close();
        }
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->begin_transaction();
    
    try {
        $conn->query("DELETE FROM medicos WHERE usuario_id = $id");
        $conn->query("DELETE FROM usuarios WHERE id = $id");
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}

// Obtener usuarios y roles
$usuarios = $conn->query("
    SELECT u.id, u.dni, u.nombre, u.apellido_p, u.apellido_m,u.email,u.telefono,
    u.activo, u.rol_id, r.nombre as rol, m.cmp, m.especialidad 
    FROM usuarios u
    LEFT JOIN roles r ON u.rol_id = r.id
    LEFT JOIN medicos m ON u.id = m.usuario_id
");
$roles = $conn->query("SELECT * FROM roles");

$rolesArray = [];
while($rol = $roles->fetch_assoc()) {
    $rolesArray[] = $rol;
}


?>

<section class="content-header">
    <h1>Usuarios del Sistema</h1>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalUsuario">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Doc. Identidad</th>
                        <th>Nombres</th>
                        <th>Ap. Paterno</th>
                        <th>Ap. Materno</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($usuario = $usuarios->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $usuario['dni']; ?></td>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['apellido_p']; ?></td>
                        <td><?php echo $usuario['apellido_m']; ?></td>
                        <td><?php echo $usuario['email']; ?></td>
                        <td><?php echo $usuario['telefono']; ?></td>
                        <td><?php echo $usuario['rol']; ?></td>
                        <td><?php echo $usuario['activo']; ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm btn-editar" 
                            data-id="<?php echo $usuario['id']; ?>" 
                            data-nombre="<?php echo $usuario['dni']; ?>"
                            data-apellido_p="<?php echo $usuario['apellido_p']; ?>"
                            data-apellido_m="<?php echo $usuario['apellido_m']; ?>"
                            data-email="<?php echo $usuario['email']; ?>"
                            data-telefono="<?php echo $usuario['telefono']; ?>"
                            data-rol_id="<?php echo $usuario['rol_id']; ?>"
                            >
                            <i class="fas fa-edit"></i>
                            </a>
                            <a href="?eliminar=<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Desactivar este usuario?')">
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

<?php //Modal para registrar usuario ?>
<div class="modal fade" id="modalUsuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar de Usuario</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DNI/Carnet Extranjería</label>
                                <!--  <input type="text" name="dni" class="form-control" pattern="\d{8,9}" required> -->
                                <input type="text" id="dni" name="dni"  class="form-control" pattern="\d{8,9}" autocomplete="off" onkeyup="verificarDNI(this.value)" required>
                                <span id="mensajeDNI"></span>
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rol</label>
                                <select name="rol_id" class="form-control" id="rolSelect1" required>
                                    <?php foreach($rolesArray as $rol1): ?>
                                    <option value="<?= $rol1['id'] ?>"><?= $rol1['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombres</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Apellido Paterno</label>
                                <input type="text" name="apellido_p" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Apellido Materno</label>
                                <input type="text" name="apellido_m" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="tel" name="telefono" class="form-control" pattern="[0-9]{9}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Campos específicos para médicos -->
                    <div id="camposMedico" style="display:none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CMP</label>
                                    <input type="text" name="cmp" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Especialidad</label>
                                    <input type="text" name="especialidad" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="pagina" value="admin_usuarios">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php // Mostrar campos de médico solo cuando se seleccione el rol correspondiente ?>
<script>
$('#rolSelect1').change(function() {
    const isMedico = ($(this).val() == 3); // Suponiendo que 2 es el ID de médico
    $('#camposMedico').toggle(isMedico);
    $('[name="cmp"], [name="especialidad"]').prop('required', isMedico);
});
</script>

<?php //Script para verificar DNI ?>
<script>
function verificarDNI(dni) {
    if (dni.length >= 8) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "funciones/verificar_dni.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("mensajeDNI").innerHTML = xhr.responseText;
            }
        };
        xhr.send("dni=" + dni);
    } else {
        document.getElementById("mensajeDNI").innerHTML = "El DNI debe tener al menos 8 caracteres.";
    }
}
</script>

<?php //Modal para Editar Usuario ?>
<div class="modal fade" id="modalEditarUsuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Usuario</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" autocomplete="off">
                <input type="hidden" name="id" id="usuario_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="pagina" value="admin_usuarios">
                                <label>DNI/Carnet Extranjería</label>
                                <input type="text" name="dni" class="form-control" pattern="\d{8,9}" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rol</label>
                                <select name="rol_id" class="form-control" id="rolSelect2" required>
                                    <?php foreach($rolesArray as $rol2): ?>
                                    <option value="<?= $rol2['id'] ?>"><?= $rol2['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombres</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Apellido Paterno</label>
                                <input type="text" name="apellido_p" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Apellido Materno</label>
                                <input type="text" name="apellido_m" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="tel" name="telefono" class="form-control" pattern="[0-9]{9}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Campos específicos para médicos -->
                    <div id="camposMedico" style="display:none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CMP</label>
                                    <input type="text" name="cmp" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Especialidad</label>
                                    <input type="text" name="especialidad" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Campos específicos para médicos -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
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
        $('#modalEditarUsuario').modal('show');
    });
});
</script>