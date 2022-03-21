<?php

/**
 * Parámetros de conexión a la BD
 */
define('BD_HOST', 'localhost');
define('BD_NAME', 'ejercicio3');
define('BD_USER', 'ejercicio3');
define('BD_PASS', 'ejercicio3');

/**
 * Configuración del soporte de UTF-8, localización (idioma y país) y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');

spl_autoload_register(function ($class)
{
	// Prefijo de espacio de nombres específico del proyecto.
	$prefix = 'AW\\';

	// Directorio base para el prefijo del espacio de nombres.
	//$base_dir = __DIR__ . '/';
	$base_dir = __DIR__ . '\\';

	// ¿La clase utiliza el prefijo del espacio de nombres?
	$len = strlen($prefix);

	if (strncmp($prefix, $class, $len) !== 0)
	{
		// No, entonces ir al siguiente autoloader.
		return;
	}

	// Obtener el nombre relativo de la clase.
	$relative_class = substr($class, $len);

	// Reemplazar el prefijo del espacio de nombres con directorio base,
	// reemplazar los separadores del espacio de nombres con separadores de
    // directorio en el nombre relativo de la clase y añadir la extensión de
    // PHP.
    $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    var_dump($file);
    // Si el fichero existe, cargarlo.
    if (file_exists($file))
    {
        require $file;
    }
});
// Inicializa la aplicación
$app = \AW\Aplicacion::getSingleton();
$app->init(array('host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS));

/**
 * @see http://php.net/manual/en/function.register-shutdown-function.php
 * @see http://php.net/manual/en/language.types.callable.php
 */
register_shutdown_function(array($app, 'shutdown'));