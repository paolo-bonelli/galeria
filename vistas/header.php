<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeria - Vincenzo Bonelli</title>
  <link rel="stylesheet" href="./css/galeria.css">
</head>

<body>
  <header id="cabecera-principal">
    <div class="logo-container">
      <a href="./">Una Galeria</a>
    </div>
    <nav>
      <ul>
        <li><a href="./"><img src="./imagenes/home.png" alt="Inicio"></a></li>
        <?php if (isset($_SESSION['id_usuario']) and ($_SESSION['id_usuario'] != "")) { ?>
          <li><a href="./perfil.php"><img src="./imagenes/grinning.png" alt="Tu perfil" class="header-avatar"> <?php echo $_SESSION['id_usuario']; ?></a></li>
          <li><a href="./nueva-galeria.php">Nueva galería</a></li>
          <li><a href="./logout.php">Salir</a></li>
        <?php } else { ?>
          <li><a href="./register.php">Regístrate</a></li>
          <li><a href="./login.php">Ingresa</a></li>
        <?php } ?>
      </ul>
    </nav>
  </header>
  <main>