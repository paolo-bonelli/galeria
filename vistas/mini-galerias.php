<a href="./galeria.php?id=<?php echo $galeria->get_id(); ?>">
  <article class="mini-galeria">
    <section class="titulo-galeria">
      <h3><?php echo $galeria->get_nombre(); ?></h3>
    </section>
    <section class="info-galeria">
      <p>Propiedad de <?php echo $galeria->get_propietario(); ?> creada el <?php echo $galeria->get_fecha_creacion(); ?></p>
    </section>
  </article>
</a>