<?php if (isset($ex) and $ex instanceof Exception) { ?>
  <div class="error">
    <?php echo $ex->getMessage(); ?>
  </div>
<?php } ?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" class="insertar">
  <label for="nombre">Nombre</label><input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" required>
  <label for="tipo-galeria">Tipo de Galeria</label>
  <select name="tipo-galeria" id="tipo-galeria" required>
    <?php foreach ($tipos_galeria as $tipo_galeria) { ?>
      <option value="<?php echo $tipo_galeria; ?>"><?php echo ucfirst($tipo_galeria); ?></option>
    <?php } ?>
  </select>
  <label for="imagenes">Imagenes</label><input type="file" name="imagenes[]" id="imagenes" accept="image/gif, image/jpg, image/png" multiple required>
  <input type="reset" value="Limpiar">
  <input type="submit" value="Siguiente">
</form>