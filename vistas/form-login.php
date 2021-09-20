<?php if (isset($ex) and $ex instanceof Exception) { ?>
  <div class="error">
    <?php echo $ex->getMessage(); ?>
  </div>
<?php } ?>

<h2>Login</h2>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="insertar">
  <label for="id_usuario">Usuario</label>
  <input type="text" name="id_usuario" id="id_usuario">
  <label for="password">Contraseña</label>
  <input type="password" name="password" id="password">
  <input type="submit" value="Ingresar">
</form>