<?php
session_start(); // Iniciar sesión
include 'config.php';
// Si el usuario ya está autenticado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validar credenciales
    $sql = "SELECT u.id, u.dni, u.apellido_p, u.apellido_m, u.nombre, u.password, r.nombre AS rol 
            FROM usuarios u
            INNER JOIN roles r ON u.rol_id = r.id 
            WHERE u.dni = ? AND u.activo = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        //$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        //echo $password;
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            // Iniciar sesión
            //echo "pasó verificacion de password";
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['dni'] = $usuario['dni'];
            $_SESSION['apellido_p'] = $usuario['apellido_p'];
            $_SESSION['apellido_m'] = $usuario['apellido_m'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            //echo "va a ir al dashboard";
            // Redirigir al dashboard
            header("Location: index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado o inactivo.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Hospitalario - RIS Gran Chimú</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
  <div class="card-header text-center">
  <a href="#" class="h1">
    <img src="dist/img/logo_lateral.png" alt="Logo de la Institución" style="max-width: 200px; height: auto;">
  </a>
  <p class="mt-2"><h3>Sistema Hospitalario</h3></p>
</div>
    <div class="card-body">
      <p class="login-box-msg">Inicia Sesión</p>

      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input type="input" class="form-control" name="username" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-3"></div>
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block">Inicia Sesión</button>
          </div>
          <!-- /.col -->
          <div class="col-3"></div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-2">
        <a href="forgot-password.html">Olvidé mi contraseña</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>