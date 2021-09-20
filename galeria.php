<?php
include './modelos.php';

try {
  $puede_agregar = false;
  $puede_eliminar = false;

  if (isset($_GET['id']) and $_GET['id'] != '') {
    $galeria = Conexion_BD::buscar_galeria($_GET['id']);

    if ($galeria == NULL) {
      throw new Exception("No existe la galeria solicitada");
    }

    if (strcasecmp($galeria->get_tipo(), "privada") === 0) {
      if (!isset($_SESSION['id_usuario'])) {
        throw new Exception("Debe ingresar con su cuenta para poder ver esta galeria");
      }

      if (strcasecmp($_SESSION['id_usuario'], $galeria->get_propietario()) !== 0) {
        throw new Exception("No tiene permiso de ver esta galeria");
      }
    }

    if (isset($_SESSION['id_usuario']) and $_SESSION['id_usuario'] != "") {
      if (strcasecmp($_SESSION['id_usuario'], $galeria->get_propietario()) === 0) {
        $puede_agregar = true;
        $puede_eliminar = true;
      }

      if (strcasecmp($galeria->get_tipo(), "publica") === 0) {
        $puede_agregar = true;
      }
    }
    $imagenes = Conexion_BD::get_fotos_galeria($_GET['id']);
  } else {
    header("Location: ./");
  }
} catch (Exception $ex) {
} finally {
  include './vistas/header.php';
  include './vistas/galeria-unica.php';
  include './vistas/footer.php';
}
