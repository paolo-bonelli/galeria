<?php if (isset($ex) and $ex instanceof Exception) { ?>
  <div class="error">
    <?php echo $ex->getMessage(); ?>
  </div>
<?php } else { ?>
  <article class="galeria">
    <section class="titulo">
      <h3><?php echo $galeria->get_nombre(); ?></h3>
    </section>
    <p>Galeria de <b><?php echo $galeria->get_propietario(); ?></b> creada el <b><?php echo $galeria->get_fecha_creacion(); ?></b></p>
    <section class="imagenes">
      <?php foreach ($galeria->get_imagenes() as $imagen) { ?>
        <article class="img-container" id="<?php echo $imagen->get_nombre(); ?>">
          <a href="#<?php echo $imagen->get_nombre(); ?>" class="link-imagen"></a>
          <a href="#" class="link-galeria"></a>
          <?php if ($puede_eliminar) { ?>
            <a href="./eliminar-imagen.php?nombre=<?php echo $imagen->get_nombre(); ?>" class="btn-eliminar">
              <img src="./imagenes/trash.png" alt="Eliminar foto" class="eliminar">
            </a>
          <?php } ?>
          <img src="./imagenes/galerias/<?php echo $imagen->get_nombre(); ?>" alt="Imagen de <?php echo $imagen->get_propietario(); ?>" class="img-galeria">
        </article>
      <?php } ?>

      <?php if ($puede_agregar) { ?>
        <a href="./agregar-imagenes.php?id=<?php echo $galeria->get_id(); ?>" class="agregar-imagen-icono">
          <img src="./imagenes/plus.png" alt="Aregar imagen">
        </a>
      <?php } ?>
    </section>
  </article>
<?php } ?>