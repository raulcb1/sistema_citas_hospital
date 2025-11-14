<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #004983  !important;">
    <!-- Brand Logo -->
    <!--<div style="text-align: center;"> -->
        <a href="index3.html" class="brand-link" style="text-align: center;">
            <!-- <img src="dist/img/logo_menu.png" alt="Logo Red" class=" brand-image img-rounded elevation-3" style="opacity: .8"></p> -->
            <p><img src="dist/img/logo_lateral.png" alt="Logo Red" class="brand-image-xl img-rounded elevation-3"
                    style="opacity: .8"></p>
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
                <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->

                <li class="nav-header">OPCIONES</li>
                <li class="nav-item">
                <a href="#" class="nav-link" onclick="document.getElementById('form_citas').submit();">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text">CITAS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-warning"></i>
                        <p>HISTORIAS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="document.getElementById('form_pacientes').submit();">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>PACIENTES</p>
                    </a>
                </li>                
            </ul>
        </nav>

        <form id="form_pacientes" action="index.php" method="post" style="display: none;">
            <input type="hidden" name="pagina" value="paginas/paciente/pacientes.php">
        </form>
        <form id="form_citas" action="index.php" method="post" style="display: none;">
            <input type="hidden" name="pagina" value="paginas/cita/citas.php">
        </form>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>