<?php
	require_once __DIR__ . '/Aplicacion.php';
	use stdClass;

	class Usuario
	{
			private $id;
			private $nombreUsuario;
			private $nombre;
			private $rol;
			private $password;

		public function __construct(
			$id,
			string $nombreUsuario,
			string $nombre,
			string $rol,
		)
		{
			$this->id = $id;
			$this->nombreUsuario = $nombreUsuario;
			$this->nombre = $nombre;
			$this->rol = $rol;
		}
		
		/**
		 * Construye un nuevo objeto de la clase a partir de un objeto resultado
		 * de una consulta de MySQL.
		 * 
		 * @param stdClass $o Objeto resultado de la consulta MySQL.
		 * 
		 * @return self Objeto de la clase construido.
		 */
		public static function fromMysqlFetch(stdClass $o): self
		{
			return new self(
				$o->id,
				$o->nombreUsuario,
				$o->nombre,
				$o->rol,
			);
		}
        
		public static function buscaUsuario(string $nombreUsuario) 
		{
			$bbdd = Aplicacion::getSingleton()->conexionBd();

			$query = sprintf("SELECT * FROM Usuarios U WHERE U.nombreUsuario = '%s'", $bbdd->real_escape_string($nombreUsuario));
			$rs = $bbdd->query($query);
			$result = false;
			if ($rs) {
				if ( $rs->num_rows == 1) {
					$fila = $rs->fetch_assoc();
					$user = new Usuario(null, $fila['nombreUsuario'], $fila['nombre'], $fila['password'], $fila['rol']);
					$user->id = $fila['id'];
					$result = $user;
				}
				$rs->free();
			} else {
				echo "Error al consultar en la BD: (" . $bbdd->errno . ") " . utf8_encode($bbdd->error);
				exit();
			}
			return $result;
		}

		public static function dbGetPasswordFromId(int $id): string
		{
			$bbdd = Aplicacion::getSingleton()->conexionBd();

			$query = <<< SQL
				SELECT 
					password
				FROM
					usuarios
				WHERE
					id = ?
				LIMIT 1
			SQL;

			$sentencia = $bbdd->prepare($query);
			$sentencia->bind_param('i', $id);
			$sentencia->execute();
			$resultado = $sentencia->get_result()->fetch_object();
			$return = $resultado->password;
			$sentencia->close();

			return $return;
		}

		public static function login(string $nombreUsuario, string $password) 
		{
			//Instancia de si mismo
			$usuario = self::buscaUsuario($nombreUsuario);
			
			if(password_verify($password, self::dbGetPasswordFromId($usuario->getId())))
			{
				return $usuario;
			}

			return null;
		}

		private static function hashPassword($password)
   		{
        	return password_hash($password, PASSWORD_DEFAULT);
    	}

		public static function crea($nombreUsuario, $nombre, $password, $rol)
		{
			$user = self::buscaUsuario($nombreUsuario);
			if ($user)
			{
				return false;
			}
			
			$user = new Usuario($nombreUsuario, $nombre, self::hashPassword($password), $rol);
			
			return self::guarda($user);
		}

		public static function guarda($usuario)
		{
			if ($usuario->id !== null)
			{
				return self::actualiza($usuario);
			}

			return self::inserta($usuario);
		}

		private static function actualiza($usuario)
		{
			$app = Aplicacion::getSingleton();
			$conn = $app->conexionBd();
			var_dump($usuario->id);
			$query=sprintf("UPDATE Usuarios U SET nombreUsuario = '%s', nombre='%s', password='%s', rol='%s' WHERE U.id='%s'"
				, $conn->real_escape_string($usuario->nombreUsuario)
				, $conn->real_escape_string($usuario->nombre)
				, $conn->real_escape_string($usuario->password)
				, $conn->real_escape_string($usuario->rol)
				, $usuario->id);
			if ( $conn->query($query) ) {
				if ( $conn->affected_rows != 1) {
					echo "No se ha podido actualizar el usuario: " . $usuario->id;
					exit();
				}
			} else {
				echo "Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
				exit();
			}
			
			return $usuario;
		}

		public function inserta(
		) : int
		{
			$bbdd = Aplicacion::getSingleton()->conexionBd();
			$query = <<< SQL
				INSERT 
				INTO 
					usuarios
					(
						nombreUsuario,
						nombre,
						password,
						rol
					)
				VALUES
					(?,?,?,?)
			SQL;

			$nombreUsuario = $this->getNombreUsuario();
			$nombre = $this->getNombre();
			$contraseña = $this->getContraseña();
			$rol = $this->getRol();

			$sentencia = $bbdd->prepare($query);

			$sentencia->bind_param(
				'ssss',
				$nombreUsuario,
				$nombre,
				$contraseña,
				$rol
			);

			$sentencia->execute();

			$id_insertado = $bbdd->insert_id;

			$sentencia->close();

			$this->id = $id_insertado;

			return $this->id;
		}

		/**
		 * Get the value of id
		 */ 
		public function getId()
		{
					return $this->id;
		}

		/**
		 * Set the value of id
		 *
		 * @return  self
		 */ 
		public function setId($id)
		{
					$this->id = $id;

					return $this;
		}

		/**
		 * Get the value of nombreUsuario
		 */ 
		public function getNombreUsuario()
		{
					return $this->nombreUsuario;
		}

		/**
		 * Set the value of nombreUsuario
		 *
		 * @return  self
		 */ 
		public function setNombreUsuario($nombreUsuario)
		{
					$this->nombreUsuario = $nombreUsuario;

					return $this;
		}

		/**
		 * Get the value of nombre
		 */ 
		public function getNombre()
		{
					return $this->nombre;
		}

		/**
		 * Get the value of rol
		 */ 
		public function getRol()
		{
					return $this->rol;
		}

		/**
		 * Get the value of contraseña
		 */ 
		public function getContraseña()
		{
					return $this->password;
		}
	}
?>