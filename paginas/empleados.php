<?php

// Consulta para obtener la lista de empleados
$sql = "SELECT * FROM empleados WHERE activo='1'";
$result = $conn->query($sql);


?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Empleados de la Institución</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
          <li class="breadcrumb-item active">Empleados</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Listado General</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
          <i class="fas fa-minus"></i>
        </button>
        <!--
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
            </button>
        -->
      </div>
    </div>

    <!-- card-body -->
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
              <th>ID</th>
              <th>DNI</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Acciones</th>
              <th>Adicional</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['dni']; ?></td>
              <td><?php echo $row['nombre']; ?></td>
              <td><?php echo $row['apellido']; ?></td>
              <td><?php echo $row['email']; ?></td>
              <td><?php echo $row['telefono']; ?></td>
              <td>
              <div class="btn-group">
                  <a href="editar.php?id=<?php echo $row['id']; ?>">
                  <button type="button" class="btn btn-info btn-xs">Editar</button>
                  </a>
                  <a href="crud.php?eliminar=<?php echo $row['id']; ?>">
                  <button type="button" class="btn btn-info btn-xs">Eliminar</button>
                  </a>
              </div>
              </td>
              <td>
              <div class="btn-group">
              <a href="editar.php?id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-info btn-xs">Left</button></a>
              <button type="button" class="btn btn-info btn-xs">Middle</button>
              <button type="button" class="btn btn-info btn-xs">Right</button>
            </div>
                  <a href="historico.php?id=<?php echo $row['id']; ?>">Historial</a>
                  <a href="legajo.php?id=<?php echo $row['id']; ?>">Legajo</a>
              </td>
              <?php endwhile; ?>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- /.card-body -->
    <div class="card-footer">
      Footer
    </div>
    <!-- /.card-footer-->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
