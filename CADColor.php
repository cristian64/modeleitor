<?php
require_once 'Logger.php';
require_once 'BD.php';
require_once 'ENColor.php';
/**
 * Componente de acceso a datos para la clase ENColor.
 */
class CADColor
{
	/**
	 * Procesa una fila y devuelve un objeto elaborado con el color.
	 * @param array $fila Tantas componentes como columnas tiene la tabla "colores" de la base de datos.
	 * @return ENColor Devuelve el color con todos sus atributos. Si falla la extracción, devuelve NULL.
	 */
	private static function obtenerDatos($fila)
	{
		$color = NULL;
		
		try
		{
			$color = new ENColor;
			$color->setId($fila[0]);
			$color->setNombre($fila[1]);
			$color->setRGB($fila[2]);
		}
		catch (Exception $e)
		{
			Logger::error("<CADColor::obtenerDatos(fila) ".$e->getMessage());
			$color = NULL;
		}

		return $color;
	}

	/**
	 * Obtiene todos los colores que hay en la base de datos.
	 * @return array Devuelve una lista de ENColor con todos los colores de la base de datos.
	 */
	public static function obtenerTodos()
	{
		$listaColores = NULL;

		try
		{
			$sentencia = "select * from colores";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$listaColores = array();
				$contador = 0;
				while ($fila = mysql_fetch_array($resultado))
				{
					$color = self::obtenerDatos($fila);
					if ($color != NULL)
					{
						$listaColores[$contador++] = $color;
					}
					else
					{
						Logger::aviso("<CADColor::obtenerTodos() Color nulo nº $contador");
					}
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADColor::obtenerTodos() ".$e->getMessage());
			$listaColores = NULL;
		}

		return $listaColores;
	}

	/**
	 * Dado el nombre de un color, extrae el resto de atributos desde la base de datos.
	 * @param string $nombre Nombre del color.
	 * @return ENColor Devuelve el color con todos sus atributos. Si no existe el color en la base de datos, devuelve NULL.
	 */
	public static function obtenerPorNombre($nombre)
	{
		$color = NULL;

		try
		{
			$sentencia = "select * from colores where nombre = '$nombre'";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$fila = mysql_fetch_array($resultado);
				if ($fila)
				{
					$color = self::obtenerDatos($fila);
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADColor::obtenerPorNombre(nombre) ".$e->getMessage());
			$color = NULL;
		}

		return $color;
	}

	/**
	 * Dado el identificador de un color, extrae el resto de atributos desde la base de datos.
	 * @param int $id Identificador del color, que es clave primaria en la tabla "colores" de la base de datos.
	 * @return ENColor Devuelve el color con todos sus atributos. Si no existe el color en la base de datos, devuelve NULL.
	 */
	public static function obtenerPorId($id)
	{
		$color = NULL;

		try
		{
			$sentencia = "select * from colores where id = $id";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$fila = mysql_fetch_array($resultado);
				if ($fila)
				{
					$color = self::obtenerDatos($fila);
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADColor::obtenerPorId(id) ".$e->getMessage());
			$color = NULL;
		}

		return $color;
	}

	/**
	 * Comprueba si un color existe en la base de datos.
	 * @param string $nombre Nombre del color.
	 * @return bool Devuelve verdadero si existe el color en la base de datos. Falso en caso contrario.
	 */
	public static function existePorNombre($nombre)
	{
		$color = self::obtenerPorNombre($nombre);
		return $color != NULL;
	}

	/**
	 * Comprueba si un color existe en la base de datos.
	 * @param int $id Identificador del color, que es clave primaria en la tabla "colores" de la base de datos.
	 * @return bool Devuelve verdadero si existe el color en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		$color = self::obtenerPorId($id);
		return $color != NULL;
	}

	/**
	 * Dado un color con un nombre y un valor de color en hexadecimal, lo guarda en la base de datos.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si el color es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @param ENColor $color El color que se va a guardar en la base de datos.
	 * @return bool Devuelve verdadero si ha creado un nuevo color. Falso en caso contrario.
	 */
	public static function guardar($color)
	{
		$guardado = false;

		if ($color->getId() == 0)
		{
			try
			{
				// Insertamos el color.
				$sentencia = "insert into colores (nombre, rgb, fecha_insercion) values ('".$color->getNombre()."','".$color->getRGB()."',now());\n";

				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					// Si se ha insertado, extraemos su identificador.
					$sentencia = "select id from colores where nombre = '".$color->getNombre()."';";
					$resultado = mysql_query($sentencia, BD::conectar());

					if ($resultado)
					{
						$fila = mysql_fetch_array($resultado);
						if ($fila)
						{
							$color->setId($fila[0]);
							$guardado = true;
						}
					}
				}
			}
			catch (Exception $e)
			{
				Logger::error("<CADColor::guardar(color) ".$e->getMessage());
				$guardado = false;
			}
		}

		return $guardado;
	}

	/**
	 * Dado un color con un nombre y un valor de color en hexadecimal, actualiza su valor en la base de datos.
	 * @param ENColor $color El color que se va a actualizar en la base de datos.
	 * @return bool Devuelve verdadero si ha actualizado un color existente. Falso en caso contrario.
	 */
	public static function actualizar($color)
	{
		$actualizado = false;

		try
		{
			if (self::existePorId($color->getId()))
			{
				$sentencia = "update colores set nombre = '".$color->getNombre()."', rgb = '".$color->getRGB()."' where nombre = '".$color->getNombre()."';";

				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$actualizado = true;
				}
				else
				{
					Logger::error("<CADColor::actualizar> ".mysql_error());
				}
			}
			else
			{
				Logger::aviso("<CADColor::actualizar() El color ".$color->getNombre()." no existe.");
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADColor::actualizar(color) ".$e->getMessage());
			$actualizado = false;
		}

		return $actualizado;
	}

	/**
	 * Dado un color, lo elimina de la base de datos.
	 * @param ENColor $color Color que va a ser borrado de la base de datos.
	 * @return bool Devuelve verdadero si se ha borrado el color. Falso en caso contrario.
	 */
	public static function borrar($color)
	{
		return self::borrarPorNombre($color->getNombre());
	}

	/**
	 * Dado el nombre de un color, lo elimina de la base de datos.
	 * @param string $nombre Nombre del color que se va a borrar de la base de datos.
	 * @return bool Devuelve verdadero si se ha borrado el color. Falso en caso contrario.
	 */
	public static function borrarPorNombre($nombre)
	{
		$borrado = false;

		try
		{
			if (self::existePorNombre($nombre))
			{
				$sentencia = "delete from colores where nombre = '".$nombre."'";
				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$borrado = true;
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADColor::borrar(nombre) ".$e->getMessage());
			$borrado = false;
		}

		return $borrado;
	}

	/**
	 * Dado el identificador de un color, lo elimina de la base de datos.
	 * @param int $id Identificador del color que se va a borrar de la base de datos.
	 * @return bool Devuelve verdadero si se ha borrado el color. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		$borrado = false;

		try
		{
			if (self::existePorId($id))
			{
				$sentencia = "delete from colores where id = ".$id."";
				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$borrado = true;
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADColor::borrar(id) ".$e->getMessage());
		}

		return $borrado;
	}
}
?>
