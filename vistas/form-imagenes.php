<?php if (isset($ex) and $ex instanceof Exception) { ?>
  <div class="error">
    <?php echo $ex->getMessage(); ?>
  </div>
<?php } else { ?>
  <h2>Agregar imágenes</h2>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?id=" . $_GET['id']; ?>" method="post" class="insertar" enctype="multipart/form-data">
    <label for="imagenes">Imagenes</label><input type="file" name="imagenes[]" id="imagenes" accept="image/gif, image/jpg, image/png" multiple required>
    <input type="submit" value="Agregar">
  </form>
<?php } ?>