<?php

//Inicio del procesamiento
session_start();

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Administrar</title>
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
		if (isset($_SESSION["esAdmin"]) && $_SESSION["esAdmin"]) {
	?>
		<h1>Consola de administración</h1>
		<p>Aquí estarían todos los controles de administración</p>
	<?php
		} else {
	?>
		<h1>Acceso denegado!</h1>
		<p>No tienes permisos suficientes para administrar la web.</p>
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