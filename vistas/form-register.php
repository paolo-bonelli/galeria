<?php if (isset($ex) and $ex instanceof Exception) { ?>
  <div class="error">
    <?php echo $ex->getMessage(); ?>
  </div>
<?php } ?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="insertar">
  <label for="id_usuario">Usuario</label><input type="text" name="id_usuario" id="id_usuario" minlength="4" value="<?php echo $id_usuario; ?>" required>
  <label for="password">Password</label><input type="password" name="password" id="password" minlength="8" required>
  <label for="email">Email</label><input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
  <label for="nombres">Nombres</label><input type="text" name="nombres" id="nombres" value="<?php echo $nombres; ?>" required>
  <label for="apellidos">Apellidos</label><input type="text" name="apellidos" id="apellidos" value="<?php echo $apellidos; ?>" required>
  <input type="reset" value="Limpiar">
  <input type="submit" value="Registrar">
</form>