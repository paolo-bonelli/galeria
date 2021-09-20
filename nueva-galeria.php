<?php
include './modelos.php';

if (!isset($_SESSION['id_usuario']) or $_SESSION['id_usuario'] == "") {
  header("Location: ./");
}

$tipos_galeria = array("Publica", "Propia", "Privada");
$nombre = "";
$archivos = array();

try {
  if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (!isset($_POST['nombre'])) {
      throw new Exception("No ingresó un nombre");
    }

    if (!isset($_POST['tipo-galeria'])) {
      throw new Exception("No ingresó un tipo de Galería");
    }

    if (!isset($_FILES['imagenes']['name']) or !isset($_FILES['imagenes']['tmp_name'])) {
      throw new Exception("Debe haber un campo para recibir imágenes.");
    }

    $nombre = $_POST['nombre'];
    $tipo_galeria = $_POST['tipo-galeria'];
    foreach (array_keys($_FILES['imagenes']['name']) as $key) {
      array_push($archivos, Imagen::procesar_imagen($_FILES['imagenes']['name'][$key], $_FILES['imagenes']['tmp_name'][$key]));
    }

    $id_galeria = Galeria::crear_nueva_galeria($nombre, $_SESSION['id_usuario'], $_POST['tipo-galeria']);

    foreach ($archivos as $archivo) {
      Imagen::crear_nueva_imagen($_SESSION['id_usuario'], $archivo, $id_galeria);
    }

    header("Location: ./");
  }
} catch (Exception $ex) {
  foreach ($archivos as $archivo) {
    Imagen::eliminar_imagen($archivo);
    unlink('./imagenes/galerias/' . $archivo);
    if (isset($id_galeria)) {
      Galeria::eliminar_galeria($id_galeria, $_SESSION['id_usuario']);
    }
  }
} finally {
  include './vistas/header.php';
  include './vistas/form-galeria.php';
  include './vistas/footer.php';
}
