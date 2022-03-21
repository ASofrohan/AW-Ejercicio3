<?php
function mostrarSaludo() {
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		$salir = 'logout.php';
		echo "Bienvenido, " . $_SESSION['nombre'] . ".<a href='$salir'>(salir)</a>";
		
	} else {
		$entrar = 'login.php';
		$registro = 'registro.php';
		echo "Usuario desconocido. <a href='$entrar'>Login</a> <a href='$registro'>Registro</a>";
	}
}
?>
<header>
	<h1>Mi gran página web</h1>
	<div class="saludo">
	<?php
		mostrarSaludo();
	?>
	</div>
</header>