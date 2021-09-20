<?php session_start();

class Conexion_BD
{
  public static function conectar()
  {
    // Creamos una conexión
    $link = new mysqli($_SERVER['SERVER_NAME'], "root", "", "galeria");

    if ($link->connect_error) {
      throw new Exception("Fallo conectando a SQL: " . $link->connect_error, 50);
    } else {
      return $link;
    }
  }

  // Regresa un arreglo asociado que contiene los datos del usuario encontrado o Falso en caso de no encontrar nada
  public static function validar_id_usuario($id_usuario)
  {
    $link = Conexion_BD::conectar();
    $id_usuario = filter_var($id_usuario, FILTER_SANITIZE_SPECIAL_CHARS);
    $sql = "SELECT * FROM Usuarios WHERE id_usuario='{$id_usuario}'";
    $resultado = $link->query($sql);

    $usuario = $resultado->fetch_array(MYSQLI_ASSOC);

    $resultado->free();

    $link->close();

    return $usuario;
  }

  public static function insertar_usuario($usuario)
  {
    $link = Conexion_BD::conectar();

    $sql = "INSERT INTO Usuarios(id_usuario,password_hash,email,nombres,apellidos) VALUES ('{$usuario->get_id_usuario()}','{$usuario->get_password_hash()}','{$usuario->get_email()}','{$usuario->get_nombres()}','{$usuario->get_apellidos()}')";

    if (!$link->query($sql)) {
      throw new Exception("No se pudo registrar el usuario", 51);
    }

    $link->close();
  }

  public static function insertar_galeria($nombre, $propietario, $tipo)
  {
    $link = Conexion_BD::conectar();
    $sql = "INSERT INTO Galerias(nombre,propietario,tipo_de_galeria) VALUES ('{$nombre}','{$propietario}','{$tipo}')";

    if (!$link->query($sql)) {
      throw new Exception("No se pudo agregar la Galeria a la base de datos");
    } else {
      return $link->insert_id;
    }
  }

  public static function drop_galeria($id_galeria, $propietario)
  {
    $link = Conexion_BD::conectar();
    $sql = "DELETE FROM Galerias WHERE id_galeria='{$id_galeria}' and propietario='{$propietario}'";

    if (!$link->query($sql)) {
      throw new Exception("No se pudo eliminar la Galeria de la base de datos");
    }

    $link->close();
  }

  public static function encuentra_galerias_publicas($id_usuario)
  {
    $galerias = array();
    $link = Conexion_BD::conectar();

    if (isset($id_usuario['id']) and $id_usuario['id'] != '') {
      $sql = $link->prepare("SELECT * FROM Galerias WHERE tipo_de_galeria LIKE 'Publica' OR tipo_de_galeria LIKE 'Propia' OR (tipo_de_galeria LIKE 'Privada' AND propietario LIKE ?)");
      $sql->bind_param("s", $id_usuario['id']);
    } else {
      $sql = $link->prepare("SELECT * FROM Galerias WHERE tipo_de_galeria LIKE 'Publica' OR tipo_de_galeria LIKE 'Propia'");
    }

    $sql->execute();

    $sql->bind_result($id_galeria, $nombre, $propietario, $tipo_de_galeria, $fecha_creacion, $ultima_actualizada);

    while ($sql->fetch()) {
      $galeria = new Galeria($id_galeria, $nombre, $propietario, $tipo_de_galeria, $fecha_creacion, $ultima_actualizada);
      array_push($galerias, $galeria);
    }

    $sql->close();
    $link->close();

    return $galerias;
  }

  public static function buscar_galeria($id_galeria)
  {
    if (!filter_var($id_galeria, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
      throw new Exception("ID no válido", 404);
    }

    $galeria = NULL;
    $link = Conexion_BD::conectar();

    $sql = $link->prepare("SELECT nombre,propietario,tipo_de_galeria,fecha_creacion,ultima_actualizada FROM Galerias WHERE id_galeria=?");
    $sql->bind_param("i", $id_galeria);
    $sql->execute();
    $sql->bind_result($nombre, $propietario, $tipo_de_galeria, $fecha_cracion, $ultima_actualizada);

    if ($sql->fetch()) {
      $galeria = new Galeria($id_galeria, $nombre, $propietario, $tipo_de_galeria, $fecha_cracion, $ultima_actualizada);
    }

    $sql->close();

    $link->close();

    return $galeria;
  }

  public static function get_fotos_galeria($id_galeria)
  {
    if (!filter_var($id_galeria, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
      throw new Exception("El id de la galeria no es válido");
    }

    $imagenes = array();
    $link = Conexion_BD::conectar();
    $sql = $link->prepare("SELECT nombre,propietario,fecha FROM Imagenes WHERE id_galeria=?");
    $sql->bind_param("i", $id_galeria);
    $sql->execute();
    $sql->bind_result($nombre, $propietario, $fecha);

    while ($sql->fetch()) {
      $imagen = new Imagen($nombre, $propietario, $fecha, $id_galeria);
      array_push($imagenes, $imagen);
    }

    return $imagenes;
  }

  public static function insertar_imagen($nombre, $propietario, $id_galeria)
  {
    $link = Conexion_BD::conectar();
    $sql = "INSERT INTO Imagenes(nombre,propietario,id_galeria) VALUES ('{$nombre}','{$propietario}','{$id_galeria}')";

    if (!$link->query($sql)) {
      throw new Exception("No se pudo agregar la imagen a la base de datos");
    }

    $link->close();
  }

  public static function drop_imagen($nombre)
  {
    $link = Conexion_BD::conectar();

    if ($sql = $link->prepare("DELETE FROM Imagenes WHERE nombre=?")) {
      $sql->bind_param("s", $nombre);

      if (!$sql->execute()) {
        throw new Exception("No se pudo borrar la imagen solicitada");
      }
    }

    $link->close();
  }

  public static function get_imagen($nombre)
  {
    $link = Conexion_BD::conectar();

    if ($sql = $link->prepare("SELECT nombre,propietario,id_galeria FROM Imagenes WHERE nombre=?")) {
      $sql->bind_param("s", $nombre);

      if (!$sql->execute()) {
        throw new Exception("No existe esta imagen");
      }

      $sql->bind_result($nombre, $propietario, $id_galeria);
      $sql->fetch();

      return array("nombre" => $nombre, "propietario" => $propietario, "id_galeria" => $id_galeria);
    }
  }
}

class Usuario
{
  private $id_usuario;
  private $password_hash;
  private $email;
  private $nombres;
  private $apellidos;
  private $tipo;

  private function __construct($id_usuario, $password_hash, $email, $nombres, $apellidos, $tipo)
  {
    $this->id_usuario = $id_usuario;
    $this->password_hash = $password_hash;
    $this->email = $email;
    $this->nombres = $nombres;
    $this->apellidos = $apellidos;
    $this->tipo = $tipo;
  }

  public static function registrar_usuario($id_usuario, $password, $email, $nombres, $apellidos)
  {
    $id_usuario = filter_var($id_usuario, FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $nombres = filter_var($nombres, FILTER_SANITIZE_SPECIAL_CHARS);
    $apellidos = filter_var($apellidos, FILTER_SANITIZE_SPECIAL_CHARS);

    if (Conexion_BD::validar_id_usuario($id_usuario)) {
      throw new Exception("El usuario ya existe", 20);
    }

    if (strlen($id_usuario) < 4) {
      throw new Exception("El usuario debe tener al menos 4 caracteres", 21);
    }

    if (strlen($password) < 8) {
      throw new Exception("La contraseña debe tener al menos 8 caracteres", 22);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("Debe ingresar un email válido", 23);
    }

    if ($nombres == "") {
      throw new Exception("Debe ingresar al menos un nombre", 24);
    }

    if ($apellidos == "") {
      throw new Exception("Debe ingresar al menos un apellido", 25);
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $usuario = new Usuario($id_usuario, $password_hash, $email, $nombres, $apellidos, 'Usuario');

    Conexion_BD::insertar_usuario($usuario);
  }

  public static function login_user($id_usuario, $password)
  {
    $id_usuario = filter_var($id_usuario, FILTER_SANITIZE_SPECIAL_CHARS);

    $usuario = Conexion_BD::validar_id_usuario($id_usuario);
    if ($usuario) {
      if (password_verify($password, $usuario['password_hash'])) {
        return new Usuario($usuario['id_usuario'], $usuario['password_hash'], $usuario['email'], $usuario['nombres'], $usuario['apellidos'], $usuario['tipo']);
      } else {
        throw new Exception("Contraseña incorrecta");
      }
    } else {
      throw new Exception("Usuario no registrado");
    }
  }

  public function get_id_usuario()
  {
    return $this->id_usuario;
  }

  public function get_password_hash()
  {
    return $this->password_hash;
  }

  public function get_email()
  {
    return $this->email;
  }

  public function get_nombres()
  {
    return $this->nombres;
  }

  public function get_apellidos()
  {
    return $this->apellidos;
  }

  public function get_tipo()
  {
    return $this->tipo;
  }
}

class Galeria
{
  private $id_galeria;
  private $nombre;
  private $propietario;
  private $tipo;
  private $fecha_creacion;
  private $ultima_actualizacion;

  public function __construct($id_galeria, $nombre, $propietario, $tipo, $fecha_creacion, $ultima_actualizacion)
  {
    $this->id_galeria = $id_galeria;
    $this->nombre = $nombre;
    $this->propietario = $propietario;
    $this->tipo = $tipo;
    $this->fecha_creacion = $fecha_creacion;
    $this->ultima_actualizacion = $ultima_actualizacion;
  }

  public static function contruye_galeria($galeria)
  {
    if (!isset($galeria['id_galeria'])) {
      throw new Exception("La galeria no posee un ID");
    }

    if (!isset($galeria['propietario'])) {
      throw new Exception("La galeria no posee el campo propietario");
    }

    if (!isset($galeria['tipo_de_galeria'])) {
      throw new Exception("La galeria no tipo de galeria");
    }

    if (!isset($galeria['fecha_creacion'])) {
      throw new Exception("La galeria no posee una fecha de creacion");
    }

    if (!isset($galeria['ultima_actualizada'])) {
      throw new Exception("La galeria no posee la fecha de su última actualización");
    }

    return new Galeria($galeria['id_galeria'], $galeria['nombre'], $galeria['propietario'], $galeria['tipo_de_galeria'], $galeria['fecha_creacion'], $galeria['ultima_actualizada']);
  }

  public static function get_galeria($id_galeria)
  {
    $id_galeria = filter_var($id_galeria, FILTER_SANITIZE_SPECIAL_CHARS);
    $galeria = Conexion_BD::buscar_galeria($id_galeria);

    if ($galeria) {
      $galeria = new Galeria($galeria['id_galeria'], $galeria['nombre'], $galeria['propietario'], $galeria['tipo'], $galeria['fecha'], $galeria['ultima_actualizada']);
      return $galeria;
    } else {
      throw new Exception("La galeria solicitada no existe");
    }
  }

  public static function crear_nueva_galeria($nombre, $propietario, $tipo)
  {
    $nombre = filter_var($nombre, FILTER_SANITIZE_SPECIAL_CHARS);
    $propietario = filter_var($propietario, FILTER_SANITIZE_SPECIAL_CHARS);
    $tipo = filter_var($tipo, FILTER_SANITIZE_SPECIAL_CHARS);

    if ($nombre == "") {
      throw new Exception("La galeria debe tener un nombre");
    }

    if (!Conexion_BD::validar_id_usuario($propietario)) {
      throw new Exception("El usuario indicado no existe.");
    }

    if (!in_array(strtolower($tipo), ['privada', 'propia', 'publica'])) {
      throw new Exception("El tipo de galeria indicado no existe");
    }

    return Conexion_BD::insertar_galeria($nombre, $propietario, $tipo);
  }

  public static function eliminar_galeria($id_galeria, $propietario)
  {
    if (!filter_var($id_galeria, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
      throw new Exception("El id no es válido");
    }

    if (!Conexion_BD::buscar_galeria($id_galeria)) {
      throw new Exception("No existe la galeria solicitada");
    }

    $propietario = filter_var($propietario, FILTER_SANITIZE_SPECIAL_CHARS);

    Conexion_BD::drop_galeria($id_galeria, $propietario);
  }

  public function get_id()
  {
    return $this->id_galeria;
  }

  public function get_nombre()
  {
    return $this->nombre;
  }

  public function get_tipo()
  {
    return $this->tipo;
  }

  public function get_propietario()
  {
    return $this->propietario;
  }

  public function get_fecha_creacion()
  {
    $fecha = DateTime::createFromFormat("Y-m-d", $this->fecha_creacion);
    return $fecha->format("d M Y");
  }

  public function get_ultima_actualizacion()
  {
    $fecha = DateTime::createFromFormat("Y-m-d", $this->ultima_actualizacion);
    return $fecha->format("d M Y");
  }

  public function get_imagenes()
  {
    return Conexion_BD::get_fotos_galeria($this->id_galeria);
  }
}

class Imagen
{
  private $nombre;
  private $propietario;
  private $fecha;
  private $id_galeria;

  public function __construct($nombre, $propietario, $fecha, $id_galeria)
  {
    $this->propietario = $propietario;
    $this->nombre = $nombre;
    $this->fecha = $fecha;
    $this->id_galeria = $id_galeria;
  }

  public static function crear_nueva_imagen($propietario, $nombre, $id_galeria)
  {
    $propietario = filter_var($propietario, FILTER_SANITIZE_SPECIAL_CHARS);
    $nombre = filter_var($nombre, FILTER_SANITIZE_SPECIAL_CHARS);
    $id_galeria = filter_var($id_galeria, FILTER_SANITIZE_SPECIAL_CHARS);

    if (!Conexion_BD::validar_id_usuario($propietario)) {
      throw new Exception("El usuario no existe", 1);
    }

    if (strlen($nombre) != 36) {
      throw new Exception("El nombre de la imagen no es válido", 2);
    }

    if (!Conexion_BD::buscar_galeria($id_galeria)) {
      throw new Exception("La galeria especificada no existe id='{$id_galeria}'", 3);
    }

    Conexion_BD::insertar_imagen($nombre, $propietario, $id_galeria);
  }

  public static function procesar_imagen($nombre, $temporal)
  {
    if ($nombre == "" or $temporal == "") {
      throw new Exception("No se ingresó una imagen");
    }

    $extensiones = array(1 => ".gif", 2 => '.jpg', 3 => '.png');
    $tipo = getimagesize($temporal);

    if ($tipo[2] != 1 and $tipo[2] != 2 and $tipo[2] != 3) {
      throw new Exception("El archivo no es del tipo solicitado (gif, jpg o png)");
    }

    $archivo = md5(uniqid(filter_var($nombre, FILTER_SANITIZE_SPECIAL_CHARS), true)) . $extensiones[$tipo[2]];

    if (!copy($temporal, './imagenes/galerias/' . $archivo)) {
      throw new Exception("La imagen no se pudo guardar");
    }

    return $archivo;
  }

  public static function eliminar_imagen($nombre)
  {
    $nombre = filter_var($nombre, FILTER_SANITIZE_SPECIAL_CHARS);

    Conexion_BD::drop_imagen($nombre);
  }

  public function get_nombre()
  {
    return $this->nombre;
  }

  public function get_propietario()
  {
    return $this->propietario;
  }

  public function get_fecha()
  {
    return $this->fecha;
  }
}
