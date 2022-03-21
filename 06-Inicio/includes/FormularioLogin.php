<?php
	require_once __DIR__ . '/Aplicacion.php';
	require_once __DIR__ . '/Usuario.php';
	require_once __DIR__ . '/Form.php';

	class FormularioLogin extends Form
	{
		private const FORM_ID = 'form-login';

		public function __construct(string $action)
		{
			parent::__construct(self::FORM_ID, array('action' => $action));
		}
		
		protected function generaCamposFormulario($datosIniciales, $errores = [])
		{
			$nombreUsuario =$datos['nombreUsuario'] ?? '';

			$htmlErroresGlobales = self::generaListaErroresGlobales($errores);
			$errorNombreUsuario = self::createMensajeError($errores, 'nombreUsuario', 'span', array('class' => 'error'));
			$errorPassword = self::createMensajeError($errores, 'password', 'span', array('class' => 'error'));
	
			$html = <<<EOF
			<fieldset>
				<legend>Usuario y contraseña</legend>
				$htmlErroresGlobales
				<p><label>Nombre de usuario:</label> <input type="text" name="nombreUsuario" value="$nombreUsuario"/>$errorNombreUsuario</p>
				<p><label>Password:</label> <input type="password" name="password" />$errorPassword</p>
				<button type="submit" name="login">Entrar</button>
			</fieldset>
			EOF;
			return $html;
		}
		
		protected function procesaFormulario($datos): void
		{
			$result = array();
        
        $nombreUsuario =$datos['nombreUsuario'] ?? null;
                
        if ( empty($nombreUsuario) ) {
            $result['nombreUsuario'] = "El nombre de usuario no puede estar vacío";
        }
        
        $password = $datos['password'] ?? null;
        if ( empty($password) ) {
            $result['password'] = "El password no puede estar vacío.";
        }
        
        if (count($result) === 0) {
            $usuario = Usuario::login($nombreUsuario, $password);
            if ( ! $usuario ) {
                // No se da pistas a un posible atacante
                $result[] = "El usuario o el password no coinciden";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['nombre'] = $nombreUsuario;
                $_SESSION['esAdmin'] = strcmp($usuario->rol(), 'admin') == 0 ? true : false;
                $result = 'index.php';
            }
        }
        return $result;
		}
	}
?>