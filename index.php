<!DOCTYPE html>
<html lang="es">
<?php
session_start(); // Iniciar sesión
include 'config.php';
/*echo  "Datos del Post: --- " . $_POST['pagina'] . PHP_EOL . " ||| " . $_POST['pagina'] . PHP_EOL;
echo PHP_EOL . "Datos de la sesion:";
   //var_dump($_SESSION);

   if (!empty($_POST)) {
    echo "<ul>";
    foreach ($_POST as $key => $value) {
        echo "<li><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</li>";
    }
    echo "</ul>";
    } else {
        echo "<p>No se han enviado datos mediante POST.</p>";
    }

*/


// Verificar si se ha recibido el ID del UPS por GET
// Guardar la página seleccionada en la sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pagina'])) {
   $_SESSION['pag'] = $_POST['pagina'];
   //exit(); // Terminar la ejecución
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo "no detecta usuario id";
    header("Location: login.php"); // Redirigir a login
    exit(); // Detener la ejecución del script
} elseif (isset($_SESSION['rol'])) {
    $rol_usuario=$_SESSION['rol'];
}


// Obtener la página solicitada desde POST o sesión
if (isset($_POST['pagina'])) {
    $paginaSolicitada = $_POST['pagina'];
    echo "Pagina trnasmitida: " . $paginaSolicitada . "---";
} elseif (isset($_SESSION['pag'])) {    // Verificar si existe en la sesión
    $paginaSolicitada = $_SESSION['pag'];
    unset($_SESSION['pag']);    // Limpiar la variable de sesión después de usarla
} else {
    $paginaSolicitada = "dashboard_" . $rol_usuario; // Página por defecto
}


// Validar si el usuario tiene permiso para la página
//if (!tienePermiso($_SESSION['usuario_id'], $paginaSolicitada)) {
//    header("Location: sin_permisos.php");
//    exit();
//}

// Validar y cargar la página
$pag = obtenerRutaPagina($paginaSolicitada);

// Función para verificar permisos
function tienePermiso($usuario_id, $paginaSolicitada) {
    global $conn;
    
    $sql = "SELECT p.ruta 
            FROM permisos pm
            INNER JOIN roles r ON pm.rol_id = r.id
            INNER JOIN paginas p ON pm.pagina_id = p.id
            INNER JOIN usuarios u ON u.rol_id = r.id
            WHERE u.id = ? AND p.nombre = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $usuario_id, $paginaSolicitada);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return ($result->num_rows > 0);
}

// Función para obtener la ruta física de la página
function obtenerRutaPagina($nombre) {
    global $conn; // Acceder a la conexión a la base de datos
    $sql = "SELECT ruta FROM paginas WHERE nombre = ?"; // Consulta SQL
    $stmt = $conn->prepare($sql); // Preparar la consulta
    $stmt->bind_param("s", $nombre); // Vincular el parámetro
    $stmt->execute(); // Ejecutar la consulta
    $result = $stmt->get_result(); // Obtener el resultado
    $row = $result->fetch_assoc(); // Obtener la fila como un array asociativo
    
    // Si se encuentra la página, devolver su ruta; si no, devolver la página 404
    return $row ? $row['ruta'] : 'paginas/404.php';
}

// Establecer la página por defecto
//$pag = 'paginas/cita/citas.php';

?>
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
    <link rel="stylesheet" href="plugins/fullcalendar/main.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <script src="plugins/jquery/jquery.min.js"></script>
    
</head>
<body class="sidebar-mini-xs text-sm layout-boxed layout-footer-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <?php if(isset($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->

        <!-- Contenedor Menú Lateral -->
        <?php include 'menu.php'; ?>
        <!-- /. Contenedor Menú Lateral -->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php 
                //echo $pag;
                include $pag; 
                //include "paginas/admin/permisos.php";
            ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Contenedor Pie de página -->
        <?php include 'footer.php'; ?>
        <!-- /. Contenedor Pie de página -->

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
            "rowGroup": {
                dataSrc: 'fecha_cita'
            }, // Agrupa por fecha
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
            tabla.columns(0).search(fecha)
        .draw(); // Suponiendo que la columna de fecha es la primera (índice 1)
        });
        // Escucha el clic en el botón de limpiar filtro
        $('#limpiar-filtro').on('click', function() {
            // Limpia el filtro de la tabla y vuelve a mostrar la lista completa
            tabla.columns(0).search('')
        .draw(); // Suponiendo que la columna de fecha es la sexta (índice 5)
        });
    });
    </script>
    <style>
    /* Estilo para disminuir el tamaño de la fuente en el DataTable */
    #tabla-citas {
        font-size: 14px;
        /* Ajusta el tamaño de la fuente según tus preferencias */
    }
    </style>
    <?php
    }
    unset($_SESSION['datatable']); // Limpiar la variable de sesión después de usarla
    }
    ?>
</body>

</html>