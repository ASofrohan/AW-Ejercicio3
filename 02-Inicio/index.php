<?php

//Inicio del procesamiento
session_start();

$tituloPagina = 'Portada';
$contenidoPrincipal = <<<EOS
	<p>Ejemplo de contenido</p>
EOS;

include './includes/plantillas/plantilla.php';