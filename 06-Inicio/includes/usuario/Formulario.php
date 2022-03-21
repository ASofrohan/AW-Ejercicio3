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
			$nombreUsuario = '';
			$app = Aplicacion::getSingleton();

			if (!empty($datosIniciales))
			{
				$nif = isset($datosIniciales['nombreUsuario']) ? $datosIniciales['nombreUsuario'] : $nif;
			}

			$html = <<< HTML
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
			HTML;

			return $html;
		}
		
		protected function procesaFormulario($datos): void
		{
			$error = array();
			$nif = isset($datos['nombreUsuario']) ? $datos['nombreUsuario'] : null;
					
			if (empty($nombreUsuario))
			{
				$error[] = "El NIF, NIE o CIF no puede estar vacío.";
			}
			
			$password = isset($datos['password']) ? $datos['password'] : null;
			
			if (empty($password))
			{
				$error[] = "La contraseña no puede estar vacía.";
			}
			
			// Si no hay ningún error, continuar.
			if (empty($error))
			{
				$usuario = Usuario::buscaUsuario($nombreUsuario);

				if (!$usuario)
				{
					$erroresFormulario[] = "El usuario o el password no coinciden";
				}
				else
				{
					if ( $usuario->compruebaPassword($password) )
					{
						$_SESSION['login'] = true;
						$_SESSION['nombre'] = $nombreUsuario;
						$_SESSION['esAdmin'] = strcmp($fila['rol'], 'admin') == 0 ? true : false;
						header('Location: index.php');
						exit();
					} else {
						$error[] = "El usuario o el password no coinciden";
						$this->generaListaErroresGlobales($error);
					}
				}
			}
			else
			{
				$this->generaListaErroresGlobales($error);
			}

			$this->generaFormulario();
		}
	}
?>