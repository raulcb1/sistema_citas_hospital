<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #004983  !important;">
    <!-- Brand Logo -->
    <!--<div style="text-align: center;"> -->
        <a href="#" class="brand-link" style="text-align: center;">
            <!-- <img src="dist/img/logo_menu.png" alt="Logo Red" class=" brand-image img-rounded elevation-3" style="opacity: .8"></p> -->
            <p><img src="dist/img/logo_lateral.png" alt="Logo Red" class="brand-image-xl img-rounded elevation-3" style="opacity: .8"></p>
            <p><span class="brand-text font-weight-light">Sistema Hospitalario</span></p>
        </a>
    <!--</div> -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <?php include 'paginas/modulos/usuario_menu.php';?>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">OPCIONES</li>
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <?php
                    $usuario_id = $_SESSION['usuario_id'];
                    //echo "Usuario:" . $usuario_id;
                    $sql = "SELECT p.nombre, p.nombre_menu, p.ruta, p.icono 
                            FROM permisos pm
                            INNER JOIN paginas p ON pm.pagina_id = p.id
                            INNER JOIN usuarios u ON pm.rol_id = u.rol_id
                            WHERE u.id = ? AND p.activo = 1";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $usuario_id);
                    $stmt->execute();
                    $paginas = $stmt->get_result();
                    
                    while ($pagina = $paginas->fetch_assoc()):
                    ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="cargarPagina('<?php echo $pagina['nombre']; ?>')">
                            <i class="nav-icon fas <?php echo $pagina['icono']; ?>"></i>
                            <p><?php echo $pagina['nombre_menu']; ?></p>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </nav>

        <script>
            function cargarPagina(nombre) {
                fetch('index.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'pagina=' + encodeURIComponent(nombre)
                })
                .then(() => {
                    window.location.href = 'index.php'; // Redirigir a index.php
                });
            }
        </script>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>