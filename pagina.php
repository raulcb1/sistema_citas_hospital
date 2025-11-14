<?php
echo 'detecto el get';
if (isset($_get['p']))
{
    
    $pagina=$_get['p'];
    switch ($pagina) {
        case 'empleado':
            echo 'llegó acá';
          include 'paginas\empleados.php';
          break;
        default:
          //code block
      }
 }
?>