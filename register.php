<?php
include './modelos.php';

if (isset($_SESSION['id_usuario']) and $_SESSION['id_usuario'] != "") {
  header("Location: ./");
}

$id_usuario = $password = $email = $nombres = $apellidos = "";

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_usuario'])) {
      $id_usuario = $_POST['id_usuario'];
    } else {
      throw new Exception("Debe ingresar un usuario");
    }

    if (isset($_POST['password'])) {
      $password = $_POST['password'];
    } else {
      throw new Exception("Debe ingresar una contraseña");
    }

    if (isset($_POST['email'])) {
      $email = $_POST['email'];
    } else {
      throw new Exception("Debe ingresar su email");
    }

    if (isset($_POST['nombres'])) {
      $nombres = $_POST['nombres'];
    } else {
      throw new Exception("Debe ingresar sus nombres");
    }

    if (isset($_POST['apellidos'])) {
      $apellidos = $_POST['apellidos'];
    } else {
      throw new Exception("Debe ingresar sus apellidos");
    }

    Usuario::registrar_usuario($id_usuario, $password, $email, $nombres, $apellidos);

    header("Location: ./");
  }
} catch (Exception $ex) {
} finally {
  include './vistas/header.php';
  include './vistas/form-register.php';
  include './vistas/footer.php';
}
