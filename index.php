<?php
include 'config.php';
// Verificar si se ha recibido el ID del UPS por GET

// Establecer la página por defecto
//$pag = 'paginas/cita/citas.php';
$pag = 'dashboard.php';

// Verificar si se ha recibido una página específica por POST o sesión
if (isset($_POST['pagina'])) {
    $pag = $_POST['pagina'];
} elseif (isset($_SESSION['pag'])) { // Verificar si existe en la sesión
    $pag = $_SESSION['pag'];
    unset($_SESSION['pag']); // Limpiar la variable de sesión después de usarla
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Hospitalario | Red de Salud Gran Chimú</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="sidebar-mini sidebar-mini-xs text-sm layout-boxed layout-footer-fixed" style="height: auto;">
<?php if(isset($mensaje)): ?>
    <div class="alert alert-info"><?php echo $mensaje; ?></div>
<?php endif; ?>
    <!-- Site wrapper -->
    <div class="wrapper">

        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->

        <!-- Contenedor Menú Lateral -->
        <?php include 'menu.php'; ?>
        <!-- /. Contenedor Menú Lateral -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php 
                include $pag; 
            ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Contenedor Pie de página -->
        <?php include 'footer.php'; ?>
        <!-- /. Contenedor Pie de página -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <?php 
    if (isset($_SESSION['datatable'])) { // Verificar si existe en la sesión
    ?>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <?php
    $tabla = $_SESSION['datatable'];
    if ($tabla=="citas"){
    ?>
    <script>
    $(document).ready(function() {
        // Inicializar DataTables y aplicar configuraciones
        var tabla = $('#tabla-citas').DataTable({
            "paging": true, // Habilitar paginación
            "searching": true, // Habilitar búsqueda
            "ordering": true, // Habilitar ordenamiento
            "order": [], // No ordenar por defecto
            "info": true, // Mostrar información de la tabla
            "autoWidth": false, // Desactivar el ajuste automático del ancho de las columnas
            "language": {
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "search": "Buscar:",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros totales)"
            }
        });
        $('#filtro-fecha').on('change', function() {
            var fecha = $(this).val(); // Obtiene la fecha seleccionada por el usuario

            // Filtra la tabla por la fecha seleccionada
            tabla.columns(0).search(fecha).draw(); // Suponiendo que la columna de fecha es la sexta (índice 1)
        });
        // Escucha el clic en el botón de limpiar filtro
        $('#limpiar-filtro').on('click', function() {
            // Limpia el filtro de la tabla y vuelve a mostrar la lista completa
            tabla.columns(0).search('').draw(); // Suponiendo que la columna de fecha es la sexta (índice 5)
        });
    });
    </script>
    <style>
    /* Estilo para disminuir el tamaño de la fuente en el DataTable */
    #tabla-citas {
        font-size: 12px; /* Ajusta el tamaño de la fuente según tus preferencias */
    }
</style>
    <?php
    }
    unset($_SESSION['datatable']); // Limpiar la variable de sesión después de usarla
    }
    ?>
</body>
</html>