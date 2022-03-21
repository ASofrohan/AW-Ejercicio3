<?php

require __DIR__ . '/Aplicacion.php';

session_start();

/**
 * Configuración del soporte de UTF-8, localización (idioma y país) y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ejercicio3');

/**
 * Inicialización del objeto aplicación.
 */
$app = Aplicacion::getSingleton();

$app->init(
	array(
		'host' => DB_HOST,
		'user' => DB_USER,
		'pass' => DB_PASSWORD,
		'name' => DB_NAME
	));

register_shutdown_function(array($app, 'shutdown'));