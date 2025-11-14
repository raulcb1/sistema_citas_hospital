<div class="user-panel mt-3 pb-3 mb-3 d-flex">
  <div class="image">
    <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
  </div>
  <div class="info">
    <span class="d-block text-white"><?php echo $_SESSION['apellido_p'] . " " . $_SESSION['apellido_m'] . ", " . $_SESSION['nombre']; ?></span>
    <small class="text-muted"><?php echo ucfirst($_SESSION['rol']); ?></small>
  </div>
</div>