<?php
require_once 'Logger.php';
require_once 'BD.php';
require_once 'ENFabricante.php';
/**
 * Componente de acceso a datos para la clase ENFabricante.
 */
class CADFabricante
{
	/**
	 * Procesa una fila y devuelve un objeto elaborado con el fabricante.
	 * @param array $fila Tantas componentes como columnas tiene la tabla "fabricantes" de la base de datos.
	 * @return ENFabricante Devuelve el fabricante con todos sus atributos.
	 */
	private static function obtenerDatos($fila)
	{
		$fabricante = new ENFabricante;
		$fabricante->setId($fila[0]);
		$fabricante->setNombre($fila[1]);
		$fabricante->setTelefonos(self::obtenerTelefonos($fabricante->getId()));
		return $fabricante;
	}

	/**
	 * Obtiene la lista de teléfonos del fabricante indicado.
	 * @param int $id Identificador del fabricante del que se van a extraer los teléfonos.
	 * @return array Devuelve una lista de listas de 2 elementos. El primer elemento es un string con el número de teléfono y el segundo elemento es un string con la descripción del teléfono.
	 */
	private static function obtenerTelefonos($id)
	{
		$telefonos = NULL;

		try
		{
			$sentencia = "select telefono, descripcion from fabricantes_telefonos where id_fabricante = $id order by id";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$telefonos = array();
				$contador = 0;
				while ($fila = mysql_fetch_array($resultado))
				{
					$telefono = array();
					$telefono[0] = $fila[0];
					$telefono[1] = $fila[1];
					$telefonos[$contador++] = $telefono;
				}
			}
			else
			{
				Logger::error("<CADFabricante::obtenerTelefonos(id)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFabricante::obtenerTelefonos(id) ".$e->getMessage());
			$telefonos = NULL;
		}

		return $telefonos;
	}

	/**
	 * Obtiene todos los fabricantes que hay en la base de datos.
	 * @return array Devuelve una lista con todos los fabricantes de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerTodos()
	{
		$listaFabricantes = NULL;

		try
		{
			$sentencia = "select * from fabricantes order by nombre";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$listaFabricantes = array();
				$contador = 0;
				while ($fila = mysql_fetch_array($resultado))
				{
					$fabricante = self::obtenerDatos($fila);
					if ($fabricante != NULL)
					{
						$listaFabricantes[$contador++] = $fabricante;
					}
					else
					{
						Logger::aviso("<CADFabricante::obtenerTodos()> Fabricante nulo nº $contador");
					}
				}
			}
			else
			{
				Logger::error("<CADFabricante::obtenerTodos()>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			$listaFabricantes = NULL;
			Logger::error("<CADFabricante::obtenerTodos() ".$e->getMessage());
		}

		return $listaFabricantes;
	}

	/**
	 * Obtiene un fabricante desde la base de datos a partir de su nombre.
	 * @param string $nombre Nombre del fabricante que se va a obtener.
	 * @return ENFabricante Devuelve el fabricante con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorNombre($nombre)
	{
		$fabricante = NULL;

		try
		{
			$sentencia = "select * from fabricantes where nombre = '".$nombre."'";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$fila = mysql_fetch_array($resultado);

				if ($fila)
				{
					$fabricante = self::obtenerDatos($fila);
				}
			}
			else
			{
				Logger::error("<CADFabricante::obtenerPorNombre(nombre)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFabricante::obtenerPorNombre(nombre) ".$e->getMessage());
			$fabricante = NULL;
		}

		return $fabricante;
	}

	/**
	 * Obtiene un fabricante desde la base de datos a partir de su identificador.
	 * @param int $id Identificador del fabricante que se va a obtener.
	 * @return ENFabricante Devuelve el fabricante con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorId($id)
	{
		$fabricante = NULL;

		try
		{
			$sentencia = "select * from fabricantes where id = ".$id."";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$fila = mysql_fetch_array($resultado);

				if ($fila)
				{
					$fabricante = self::obtenerDatos($fila);
				}
			}
			else
			{
				Logger::error("<CADFabricante::obtenerPorId(id)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFabricante::obtenerPorId(id) ".$e->getMessage());
			$fabricante = NULL;
		}

		return $fabricante;
	}

	/**
	 * Comprueba si un fabricante existe en la base de datos.
	 * @param string $nombre Nombre del fabricante.
	 * @return bool Devuelve verdadero si existe el fabricante en la base de datos. Falso en caso contrario.
	 */
	public static function existePorNombre($nombre)
	{
		$fabricante = self::obtenerPorNombre($nombre);
		return $fabricante != NULL;
	}

	/**
	 * Comprueba si un fabricante existe en la base de datos.
	 * @param int $id Identificador del fabricante, que es clave primaria en la tabla "fabricantes" de la base de datos.
	 * @return bool Devuelve verdadero si existe el fabricante en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		$fabricante = self::obtenerPorId($id);
		return $fabricante != NULL;
	}

	/**
	 * Dado un fabricante con un nombre y un listado de teléfonos, lo guarda en la base de datos.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si el fahbricante es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @param ENFabricante $fabricante Fabricante que va a ser guardado.
	 * @return bool Devuelve verdadero si se ha guardado correctamente. Falso en caso contrario.
	 */
	public static function guardar($fabricante)
	{
		$guardado = false;

		if ($fabricante->getId() == 0)
		{
			try
			{
				$conexion = BD::conectar();

				// Iniciamos la transacción.
				if (BD::begin($conexion))
				{
					$error = false;

					// Insertamos el fabricante.
					$sentencia ="insert into fabricantes (nombre, fecha_insercion) values ('".$fabricante->getNombre()."', now())";
					$resultado = mysql_query($sentencia, $conexion);

					if ($resultado)
					{
						// Obtenemos el identificador asignado al fabricante.
						$sentencia = "select id from fabricantes where nombre = '".$fabricante->getNombre()."'";
						$resultado = mysql_query($sentencia, $conexion);

						if ($resultado)
						{
							$fila = mysql_fetch_array($resultado);
							if ($fila)
							{
								// Insertamos los teléfonos.
								$telefonos = $fabricante->getTelefonos();
								foreach ($telefonos as $i)
								{
									$sentencia = "insert into fabricantes_telefonos (id_fabricante, telefono, descripcion, fecha_insercion)
									values (".$fila[0].", '".$i[0]."', '".$i[1]."', now());";
									$resultado = mysql_query($sentencia, $conexion);

									if (!$resultado)
									{
										Logger::error("<CADFabricante::guardar(fabricante)>".mysql_error());
										$error = true;
										break;
									}
								}

								// Asignamos el identificador al fabricante.
								if ($error == false)
								{
									$fabricante->setId($fila[0]);
									$guardado = true;
								}
							}
						}
						else
						{
							$error = true;
						}
					}
					else
					{
						$error = true;

					}

					// Si hubo error, deshacemos la operación; si no, la cerramos.
					if ($error == true)
					{
						Logger::error("<CADFabricante::guardar(fabricante)>".mysql_error());
						BD::rollback($conexion);
					}
					else
					{
						BD::commit($conexion);
					}
				}
			}
			catch (Exception $e)
			{
				Logger::error("<CADFabricante::guardar(fabricante) ".$e->getMessage());
			}
		}

		return $guardado;
	}

	/**
	 * Dado un fabricante, actualiza su nombre y sus teléfonos en la base de datos.
	 * @param ENFabricante $fabricante Fabricante que va a ser actualizado.
	 * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
	 */
	public static function actualizar($fabricante)
	{
		$actualizado = false;
		
		try
		{
			if (self::existePorId($fabricante->getId()))
			{
				$conexion = BD::conectar();

				if (BD::begin($conexion))
				{
					$error = false;
					
					// Actualizamos los datos del fabricante.
					$sentencia = "update fabricantes set nombre = '".$fabricante->getNombre()."' where id = ".$fabricante->getId();
					$resultado = mysql_query($sentencia, $conexion);
					if ($resultado)
					{
						// Actualizamos los teléfonos (los borramos todos y lo insertamos todos).
						$sentencia = "delete from fabricantes_telefonos where id_fabricante = ".$fabricante->getId();
						$resultado = mysql_query($sentencia, $conexion);
						if ($resultado)
						{
							// Insertamos los teléfonos.
							$telefonos = $fabricante->getTelefonos();
							foreach ($telefonos as $i)
							{
								$sentencia = "insert into fabricantes_telefonos (id_fabricante, telefono, descripcion, fecha_insercion)
								values (".$fabricante->getId().", '".$i[0]."', '".$i[1]."', now());";
								$resultado = mysql_query($sentencia, $conexion);

								if (!$resultado)
								{
									$error = true;
									break;
								}
							}
						}
						else
						{
							$error = true;
						}
					}
					else
					{
						$error = true;
					}

					// Si hubo error, deshacemos la operación; si no, la cerramos.
					if ($error == true)
					{
						Logger::error("<CADFabricante::actualizar(fabricante)>".mysql_error());
						BD::rollback($conexion);
					}
					else
					{
						if (BD::commit($conexion))
							$actualizado = true;
					}
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFabricante::actualizar(fabricante) ".$e->getMessage());
		}
		
		return $actualizado;
	}

	/**
	 * Dado un fabricante, lo elimina de la base de datos (nombre, teléfonos, ...).
	 * @param ENFabricante $fabricante Fabricante que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el fabricante. Falso en caso contrario.
	 */
	public static function borrar($fabricante)
	{
		$borrado = false;

		if ($fabricante->getId() > 0)
		{
			try
			{
				$conexion = BD::conectar();
				if (BD::begin($conexion))
				{
					$error = false;

					// Borramos todos los teléfonos del fabricante.
					$sentencia = "delete from fabricantes_telefonos where id_fabricante = ".$fabricante->getId();
					$resultado = mysql_query($sentencia, $conexion);
					if ($resultado)
					{
						// Finalmente borramos el fabricante.
						$sentencia = "delete from fabricantes where id = ".$fabricante->getId();
						$resultado = mysql_query($sentencia, $conexion);
						if (!$resultado)
							$error = true;
					}
					else
					{
						$error = true;
					}

					// Si ocurrió un error, se cancela la operación.
					if ($error == true)
					{
						Logger::error("<CADFabricante::borrar(fabricante)>".mysql_error());
						BD::rollback($conexion);
					}
					else
					{
						if (BD::commit($conexion))
							$borrado = true;
					}
				}
			}
			catch (Exception $e)
			{
				Logger::error("<CADFabricante::borrar(fabricante) ".$e->getMessage());
			}
		}

		return $borrado;
	}

	/**
	 * Dado el nombre de fabricante, lo elimina de la base de datos (nombre, teléfonos, ...).
	 * @param string $nombre Nombre del fabricante que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el fabricante. Falso en caso contrario.
	 */
	public static function borrarPorNombre($nombre)
	{
		$borrado = false;

		try
		{
			$fabricante = self::obtenerPorNombre($nombre);

			if ($fabricante != NULL)
			{
				$borrado = self::borrar($fabricante);
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFabricante::borrarPorNombre(nombre) ".$e->getMessage());
		}

		return $borrado;
	}

	/**
	 * Dado el identificador de un fabricante, lo elimina de la base de datos (nombre, teléfonos, ...).
	 * @param int $id Identificador del fabricante que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el fabricante. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		$borrado = false;

		try
		{
			$fabricante = self::obtenerPorId($id);

			if ($fabricante != NULL)
			{
				$borrado = self::borrar($fabricante);
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFabricante::borrarPorId(id) ".$e->getMessage());
		}

		return $borrado;
	}
}
?>
