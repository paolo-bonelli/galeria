<?php
// $link = new mysqli($_SERVER['SERVER_NAME'], "root", "", "galeria");

// if ($link->connect_errno) {
//   printf("Falló la conexión: %s\n", $link->connect_error);
//   exit();
// }

// $sql = "SELECT * FROM `Imagenes`";
// $resultado = $link->query($sql, MYSQLI_USE_RESULT);
// var_dump($resultado);
// $resultado->close();
// $link->close();

$mysqli = new mysqli("localhost", "root", "", "galeria");

/* verificar conexión */
if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}

if ($stmt = $mysqli->prepare("SELECT * FROM Galerias")) {

  /* ligar parámetros para marcadores */
  // $stmt->bind_param("s", $city);

  /* ejecutar la consulta */
  $stmt->execute();

  /* ligar variables de resultado */
  $stmt->bind_result($id_galeria, $nombre, $propietario, $tipo_de_galeria, $fecha_creacion, $ultima_actualizada);

  /* obtener valor */
  while ($stmt->fetch()) {

    echo $nombre . " " . $propietario . "<br>";
  }



  // printf("%s is in district %s\n", $city, $district);


  /* cerrar sentencia */
  $stmt->close();
}
