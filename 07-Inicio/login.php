<?php

require_once __DIR__.'/includes/config.php';
use AW\includes\FormularioLogin;

$tituloPagina = 'Login';

$form = new FormularioLogin("login.php"); 
$form->gestiona();
$formulario = $form->generaFormulario();

$contenidoPrincipal = <<<EOS
<h1>Acceso al sistema</h1>

<form action="procesarLogin.php" method="POST">
<fieldset>
	<legend>Usuario y contraseña</legend>
	<div class="grupo-control">
		<label>Nombre de usuario:</label> <input type="text" name="nombreUsuario" />
	</div>
	<div class="grupo-control">
		<label>Password:</label> <input type="password" name="password" />
	</div>
	<div class="grupo-control"><button type="submit" name="login">Entrar</button></div>
</fieldset>
</form>
EOS;

use AW\includes\plantillas\plantilla;