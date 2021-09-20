<?php
include './modelos.php';

if (isset($_SESSION['id_usuario']) and $_SESSION['id_usuario'] != "") {
  header("Location: ./");
}

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_usuario']) or $_POST['id_usuario'] == '') {
      throw new Exception("No se introdujo un usuario");
    }

    if (!isset($_POST['password']) or $_POST['password'] == '') {
      throw new Exception("No se introdujo una contraseña");
    }

    if ($usuario = Usuario::login_user($_POST['id_usuario'], $_POST['password'])) {
      $_SESSION['id_usuario'] = $usuario->get_id_usuario();
      $_SESSION['nombre'] = $usuario->get_nombres();
      $_SESSION['tipo'] = $usuario->get_tipo();
      header("Location: ./");
    }
  }
} catch (Exception $ex) {
} finally {
  include './vistas/header.php';
  include './vistas/form-login.php';
  include './vistas/footer.php';
}
