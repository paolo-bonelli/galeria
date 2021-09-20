<?php
include './modelos.php';

if (!isset($_SESSION['id_usuario']) or $_SESSION['id_usuario'] == "") {
  header("Location: ./");
}

try {
  if (!isset($_GET['nombre']) or $_GET['nombre'] == "") {
    throw new Exception("No hay imagen que eliminar");
  }

  $imagen = Conexion_BD::get_imagen($_GET['nombre']);

  if (strcasecmp($imagen["propietario"], $_SESSION['id_usuario']) === 0) {
    Imagen::eliminar_imagen($imagen['nombre']);
  } else {
    throw new Exception("No tiene permitido eliminar esta imagen");
  }

  header("Location: ./galeria.php?id=" . $imagen['id_galeria']);
} catch (Exception $ex) {
  include './vistas/header.php'; ?>
  <div class="error">
    <?php echo $ex->getMessage(); ?>
  </div>
<?php
  include './vistas/footer.php';
}
