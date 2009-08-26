<?php
require_once 'Logger.php';
require_once 'BD.php';
require_once 'ENFoto.php';
/**
 * Componente de acceso a datos para la clase ENFoto.
 */
class CADFoto
{
	/**
	 * Procesa una fila y devuelve un objeto elaborado con la foto.
	 * @param array $fila Tantas componentes como columnas tiene la tabla "fotos" de la base de datos.
	 * @return ENFoto Devuelve la foto con todos sus atributos.
	 */
	private static function obtenerDatos($fila)
	{
		$foto = new ENFoto;
		$foto->setId($fila[0]);
		$foto->setDescripcion($fila[2]);
		$foto->setIdModelo($fila[1]);
		return $foto;
	}

	/**
	 * Obtiene todos las fotos que hay en la base de datos. Si se especifica el identificador de un modelo, sólo extrae las fotos de ese modelo.
	 * @param int $id_modelo Identificador del modelo del que se extraerán sus fotos. Es un parámetro opcional.
	 * @return array Devuelve una lista con todas las fotos de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerTodos($id_modelo=NULL)
	{
		$listaFotos = NULL;

		try
		{
			$sentencia = "";
			if ($id_modelo == NULL)
				$sentencia = "select * from fotos order by id";
			else
				$sentencia = "select * from fotos where id_modelo = ".$id_modelo." order by id desc";

			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$listaFotos = array();
				$contador = 0;
				while ($fila = mysql_fetch_array($resultado))
				{
					$foto = self::obtenerDatos($fila);
					if ($foto != NULL)
					{
						$listaFotos[$contador++] = $foto;
					}
					else
					{
						Logger::aviso("<CADFoto::obtenerTodos(id_modelo=NULL)> Foto nula nº $contador");
					}
				}
			}
			else
			{
				Logger::error("<CADFoto::obtenerTodos(id_modelo=NULL)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFoto::obtenerTodos(id_modelo=NULL) ".$e->getMessage());
			$listaFotos = NULL;
		}

		return $listaFotos;
	}

	/**
	 * Obtiene una foto desde la base de datos a partir de su identificador.
	 * @param int $id Identificador de la foto que se va a obtener.
	 * @return ENFoto Devuelve la foto con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorId($id)
	{
		$foto = NULL;

		try
		{
			$sentencia = "select * from fotos where id = ".$id."";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$fila = mysql_fetch_array($resultado);

				if ($fila)
				{
					$foto = self::obtenerDatos($fila);
				}
			}
			else
			{
				Logger::error("<CADFoto::obtenerPorId(id)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFoto::obtenerPorId(id) ".$e->getMessage());
			$foto = NULL;
		}

		return $foto;
	}

	/**
	 * Comprueba si una foto existe en la base de datos.
	 * @param int $id Identificador de la foto, que es clave primaria en la tabla "fotos" de la base de datos.
	 * @return bool Devuelve verdadero si existe la foto en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		$foto = self::obtenerPorId($id);
		return $foto != NULL;
	}

	/**
	 * Dado una foto, la guarda en la base de datos.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si la foto es nueva, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @param ENFoto $foto La foto que se va a guardar en la base de datos.
	 * @return bool Devuelve verdadero si ha creado una nueva foto. Falso en caso contrario.
	 */
	public static function guardar($foto)
	{
		$guardado = false;

		if ($foto->getId() == 0)
		{
			try
			{
				$conexion = BD::conectar();
				if (BD::begin($conexion))
				{
					$error = true;
					
					// Insertamos la foto.
					$sentencia = "insert into fotos (id_modelo, descripcion, fecha_insercion) values (".$foto->getIdModelo().",'".$foto->getDescripcion()."', now());\n";
					$resultado = mysql_query($sentencia, BD::conectar());
					
					if ($resultado)
					{
						// Si se ha insertado, extraemos su identificador.
						$sentencia = "select max(id) from fotos";
						$resultado = mysql_query($sentencia, BD::conectar());

						if ($resultado)
						{
							$fila = mysql_fetch_array($resultado);
							if ($fila)
							{
								$foto->setId($fila[0]);
								$guardado = true;
								$error = false;
							}
						}
					}
					
					// Si ocurrió un error, se cancela la operación.
					if ($error == true)
					{
						Logger::error("<CADFoto::guardar(foto)>".mysql_error());
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
				Logger::error("<CADFoto::guardar(foto) ".$e->getMessage());
			}
		}

		return $guardado;
	}

	/**
	 * Dada una foto que ya existe en la base de datos, actualiza su descripción y el modelo al que hace referencia en la base de datos.
	 * @param ENFoto $foto Foto que va a ser actualizada.
	 * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
	 */
	public static function actualizar($foto)
	{
		$actualizado = false;

		try
		{
			if (self::existePorId($foto->getId()))
			{
				// Actualizamos los datos de la foto.
				$sentencia = "update fotos set descripcion = '".$foto->getDescripcion()."', id_modelo = ".$foto->getIdModelo()." where id = ".$foto->getId();
				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$actualizado = true;
				}
				else
				{
					Logger::error("<CADFoto::actualizar(foto)>".mysql_error());
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFoto::actualizar(foto) ".$e->getMessage());
		}

		return $actualizado;
	}

	/**
	 * Dada una foto, la elimina de la base de datos.
	 * @param ENFoto $foto Foto que va a ser borrada.
	 * @return bool Devuelve verdadero si ha borrado la foto. Falso en caso contrario.
	 */
	public static function borrar($foto)
	{
		return self::borrarPorId($foto->getId());
	}

	/**
	 * Dado el identificador de una foto, la elimina de la base de datos.
	 * @param int $id Identificador de la foto que se va a borrar de la base de datos.
	 * @return bool Devuelve verdadero si se ha borrado la foto. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		$borrado = false;

		try
		{
			if (self::existePorId($id))
			{
				$sentencia = "delete from fotos where id = ".$id."";
				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$borrado = true;
				}
				else
				{
					Logger::error("<CADFoto::borrarPorId(id)>".mysql_error());
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADFoto::borrarPorId(id) ".$e->getMessage());
		}

		return $borrado;
	}
}
?>
