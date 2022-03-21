<?php

//Inicio del procesamiento
session_start();

if (! isset($_POST['login']) ) {
	header('Location: login.php');
	exit();
}


$erroresFormulario = array();

$nombreUsuario = isset($_POST['nombreUsuario']) ? $_POST['nombreUsuario'] : null;

if ( empty($nombreUsuario) ) {
	$erroresFormulario[] = "El nombre de usuario no puede estar vacío";
}

$password = isset($_POST['password']) ? $_POST['password'] : null;
if ( empty($password) ) {
	$erroresFormulario[] = "El password no puede estar vacío.";
}

if (count($erroresFormulario) === 0) {
	/* Antes de hacer la conexion es necesario importar la base de datos de la carpeta mysql.
	Después de la importacion hay que crear un usuario de mysql para la base de datos que 
	llamaremos ejercicio3 y darle permisos para la base de datos. En caso de querer conectar con otro usuario
	hay que cambiar los parámetros de conexión */
	$conn= new \mysqli('localhost', 'ejercicio3', 'ejercicio3', 'ejercicio3');
	if ( $conn->connect_errno ) {
		echo "Error de conexión a la BD: (" . $conn->connect_errno . ") " . utf8_encode($conn->connect_error);
		exit();
	}
	if ( ! $conn->set_charset("utf8mb4")) {
		echo "Error al configurar la codificación de la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
		exit();
	}
	
	$query=sprintf("SELECT * FROM Usuarios U WHERE U.nombreUsuario = '%s'", $conn->real_escape_string($nombreUsuario));
	$rs = $conn->query($query);
	if ($rs) {
		if ( $rs->num_rows == 0 ) {
			// No se da pistas a un posible atacante
			$erroresFormulario[] = "El usuario o el password no coinciden";
		} else {
			$fila = $rs->fetch_assoc();
			if ( ! password_verify($password, $fila['password'])) {
				$erroresFormulario[] = "El usuario o el password no coinciden";
			} else {
				$_SESSION['login'] = true;
				$_SESSION['nombre'] = $fila['nombre']; //Capturamos el nombre real del usuario
				$_SESSION['esAdmin'] = strcmp($fila['rol'], 'admin') == 0 ? true : false;
				$rs->free(); //Antes de salir liberamos memoria
				header('Location: index.php');
				exit();
			}
		}
		$rs->free();
	} else {
		echo "Error al consultar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
		exit();
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Login</title>
</head>

<body>

<div id="contenedor">

<?php
	require("includes/cabecera.php");
	require("includes/sidebarIzq.php");
?>

<main>
	<article>
	<?php
		if (isset($_SESSION["login"])) {
			echo "<h1>Bienvenido ". $_SESSION['nombre'] . "</h1>";
			echo "<p>Usa el menú de la izquierda para navegar.</p>";
		} else {
			echo "<h1>ERROR</h1>";
            if (count($erroresFormulario) > 0) {
                echo '<ul class="errores">';
            }
            foreach($erroresFormulario as $error) {
                echo "<li>$error</li>";
            }
            if (count($erroresFormulario) > 0) {
                echo '</ul>';
            }
	?>
		<form action="procesarLogin.php" method="POST">
		<fieldset>
            <legend>Usuario y contraseña</legend>
            <div class="grupo-control">
                <label>Nombre de usuario:</label> <input type="text" name="nombreUsuario" value="<?= $nombreUsuario ?>" />
            </div>
            <div class="grupo-control">
                <label>Password:</label> <input type="password" name="password" value="<?= $password ?>" />
            </div>
            <div class="grupo-control"><button type="submit" name="login">Entrar</button></div>
		</fieldset>
		</form>
	<?php
		}
	?>
	</article>
</main>

<?php
	require("includes/sidebarDer.php");
	require("includes/pie.php");
?>
</div>

</body>
</html>