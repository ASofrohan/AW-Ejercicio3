<?php

session_start();

if (! isset($_POST['registro']) ) {
	header('Location: registro.php');
	exit();
}


$erroresFormulario = array();

$nombreUsuario = isset($_POST['nombreUsuario']) ? $_POST['nombreUsuario'] : null;

if ( empty($nombreUsuario) || mb_strlen($nombreUsuario) < 5 ) {
	$erroresFormulario[] = "El nombre de usuario tiene que tener una longitud de al menos 5 caracteres.";
}

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
if ( empty($nombre) || mb_strlen($nombre) < 5 ) {
	$erroresFormulario[] = "El nombre tiene que tener una longitud de al menos 5 caracteres.";
}

$password = isset($_POST['password']) ? $_POST['password'] : null;
if ( empty($password) || mb_strlen($password) < 5 ) {
	$erroresFormulario[] = "El password tiene que tener una longitud de al menos 5 caracteres.";
}
$password2 = isset($_POST['password2']) ? $_POST['password2'] : null;
if ( empty($password2) || strcmp($password, $password2) !== 0 ) {
	$erroresFormulario[] = "Los passwords deben coincidir";
}

if (count($erroresFormulario) === 0) {
	/* Antes de hacer la conexion es necesario importar la base de datos de la carpeta mysql.
	Después de la importacion hay que crear un usuario de mysql para la base de datos que 
	llamaremos ejercicio3 y darle permisos para la base de datos. En caso de querer conectar con otro usuario
	hay que cambiar los parámetros de conexión */
	$conn = new \mysqli('localhost', 'ejercicio3', 'ejercicio3', 'ejercicio3');
	if ( $conn->connect_errno ) {
		echo "Error de conexión a la BD: (" . $this->conn->connect_errno . ") " . utf8_encode($this->conn->connect_error);
		exit();
	}
	if ( ! $conn->set_charset("utf8mb4")) {
		echo "Error al configurar la codificación de la BD: (" . $this->conn->errno . ") " . utf8_encode($this->conn->error);
		exit();
	}
	
	$query=sprintf("SELECT * FROM Usuarios U WHERE U.nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario));
	$rs = $conn->query($query);
	if ($rs) {
		if ( $rs->num_rows > 0 ) {
			$erroresFormulario[] = "El usuario ya existe";
			$rs->free();
		} else {
			$query=sprintf("INSERT INTO Usuarios(nombreUsuario, nombre, password, rol) VALUES('%s', '%s', '%s', '%s')"
					, $conn->real_escape_string($nombreUsuario)
					, $conn->real_escape_string($nombre)
					, password_hash($password, PASSWORD_DEFAULT)
					, 'user');
			if ( $conn->query($query) ) {
				$_SESSION['login'] = true;
				$_SESSION['nombre'] = $nombre; //Capturamos el nombre real del usuario
				$rs->free();
				header('Location: index.php');
				exit();
			} else {
				echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
				exit();
			}
		}		
	} else {
		echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
		exit();
	}
}

?>
<?php
$tituloPagina = 'ProcesarLogin';

$contenidoPrincipal = register2();
$contenidoPrincipal=<<<EOS
<main>
	<article>
		<h1>Registro de usuario</h1>
		
		<form action="procesarRegistro.php" method="POST">	
		
		</ul>
		<fieldset>
			<div class="grupo-control">
				<label>Nombre de usuario:</label> <input class="control" type="text" name="nombreUsuario" value="<?='$nombreUsuario' ?>" />
			</div>
			<div class="grupo-control">
				<label>Nombre completo:</label> <input class="control" type="text" name="nombre" value="<?='$nombre' ?>" />
			</div>
			<div class="grupo-control">
				<label>Password:</label> <input class="control" type="password" name="password" />
			</div>
			<div class="grupo-control"><label>Vuelve a introducir el Password:</label> <input class="control" type="password" name="password2" /><br /></div>
			<div class="grupo-control"><button type="submit" name="registro">Registrar</button></div>
		</fieldset>
	</article>
</main>
EOS;
require __DIR__.'/includes/plantillas/plantilla.php';

function register2(){
	
	if (count($erroresFormulario) > 0) {
		echo '<ul class="errores">';
	}
	foreach($erroresFormulario as $error) {
		echo "<li>$error</li>";
	}
	if (count($erroresFormulario) > 0) {
		echo '</ul>';
	}
	
}?>