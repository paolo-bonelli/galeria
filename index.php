<?php
include './modelos.php';
include './vistas/header.php';
$id_usuario = array();

if (isset($_SESSION['id_usuario']) and $_SESSION['id_usuario'] != "") {
  $id_usuario['id'] = $_SESSION['id_usuario'];
}
$galerias = Conexion_BD::encuentra_galerias_publicas($id_usuario);
?>

<h2>Galerias</h2>


<section id="galerias">
  <?php
  foreach ($galerias as $galeria) {
    include('./vistas/mini-galerias.php');
  } ?>

</section>

<?php include './vistas/footer.php'; ?>