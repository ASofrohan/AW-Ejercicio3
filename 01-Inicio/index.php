<?php

//Inicio del procesamiento
session_start();

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="estilo.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Portada</title>
</head>

<body>

<div id="contenedor">

<?php
	require("includes/cabecera.php");
	require("includes/sidebarIzq.php");
?>
	<main>
		<article>
			<h1>Página principal</h1>
			<p> Aquí está el contenido público, visible para todos los usuarios. </p>
		</article>
	</main>
<?php

	require("includes/sidebarDer.php");
	require("includes/pie.php");

?>
</div>

</body>
</html>