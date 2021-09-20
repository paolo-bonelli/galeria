<?php
include './modelos.php';

if (!isset($_SESSION['id_usuario']) or $_SESSION['id_usuario'] == "") {
  header("Location: ./");
}

try {
  $archivos = array();

  if (!isset($_GET['id']) or $_GET['id'] == "") {
    throw new Exception("No hay una galeria a la cual agregar imagenes");
  }

  $galeria = Conexion_BD::buscar_galeria($_GET['id']);

  if (!$galeria) {
    throw new Exception("La galeria solicitada no existe");
  }

  if (in_array(strtolower($galeria->get_tipo()), array("privada", "propia")) and $_SESSION['id_usuario'] != $galeria['propietario']) {
    throw new Exception("No tiene permitido añadir imágenes a esta galería");
  }

  if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (!isset($_FILES['imagenes']['name']) or !isset($_FILES['imagenes']['tmp_name'])) {
      throw new Exception("Debe haber un campo para recibir imágenes.");
    }

    foreach (array_keys($_FILES['imagenes']['name']) as $key) {
      array_push($archivos, Imagen::procesar_imagen($_FILES['imagenes']['name'][$key], $_FILES['imagenes']['tmp_name'][$key]));
    }

    foreach ($archivos as $archivo) {
      Imagen::crear_nueva_imagen($_SESSION['id_usuario'], $archivo, $_GET['id']);
    }

    header("Location: ./galeria.php?id=" . $galeria->get_id());
  }
} catch (Exception $ex) {
  foreach ($archivos as $archivo) {
    Imagen::eliminar_imagen($archivo);
    unlink('./imagenes/galerias/' . $archivo);
  }
} finally {
  include './vistas/header.php';
  include './vistas/form-imagenes.php';
  include './vistas/footer.php';
}
