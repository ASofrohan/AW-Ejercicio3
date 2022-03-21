<?php
session_start();

unset($_SESSION["login"]);
unset($_SESSION["esAdmin"]);
unset($_SESSION["nombre"]);


session_destroy();

$tituloPagina = 'Logout';

$contenidoPrincipal = <<<EOS
<main>
	<article>
		<h1>Hasta pronto!</h1>
	</article>
</main>
EOS;

require __DIR__.'/includes/plantillas/plantilla.php';