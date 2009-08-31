<?php
require_once 'Logger.php';
require_once 'BD.php';
require_once 'ENCatalogo.php';
/**
 * Componente de acceso a datos para la clase ENCatalogo.
 */
class CADCatalogo
{
	/**
	 * Procesa una fila y devuelve un objeto elaborado con el catálogo.
	 * @param array $fila Tantas componentes como columnas tiene la tabla "catalogos" de la base de datos.
	 * @return ENCatalogo Devuelve el catálogo con todos sus atributos.
	 */
	private static function obtenerDatos($fila)
	{
		$catalogo = new ENCatalogo;
		$catalogo->setId($fila[0]);
		$catalogo->setTitulo($fila[1]);
		$catalogo->setIdUsuario($fila[2]);
		return $catalogo;
	}

	/**
	 * Obtiene todos los catálogos que hay en la base de datos.
	 * @param int $id_usuario Identificador del usuario del que se quiere extraer todos sus catálogos.
	 * @return array Devuelve una lista con todos los catálogos de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerTodos($id_usuario=NULL)
	{
		$listaCatalogos = NULL;

		try
		{
			$sentencia = "";
			if ($id_usuario == NULL)
				$sentencia = "select * from catalogos order by id desc";
			else
				$sentencia = "select * from catalogos where id_usuario = $id_usuario order by id desc";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$listaCatalogos = array();
				$contador = 0;
				while ($fila = mysql_fetch_array($resultado))
				{
					$catalogo = self::obtenerDatos($fila);
					if ($catalogo != NULL)
					{
						$listaCatalogos[$contador++] = $catalogo;
					}
					else
					{
						Logger::aviso("<CADCatalogo::obtenerTodos(id_usuario=NULL)> Catálogo nulo nº $contador");
					}
				}
			}
			else
			{
				Logger::error("<CADCatalogo::obtenerTodos(id_usuario=NULL)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			$listaCatalogos = NULL;
			Logger::error("<CADCatalogo::obtenerTodos(id_usuario=NULL) ".$e->getMessage());
		}

		return $listaCatalogos;
	}

	/**
	 * Obtiene un catálogo desde la base de datos a partir de su identificador.
	 * @param int $id Identificador del catálogo que se va a obtener.
	 * @return ENCatalogo Devuelve el catálogo con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorId($id)
	{
		$catalogo = NULL;

		try
		{
			$sentencia = "select * from catalogos where id = ".$id."";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$fila = mysql_fetch_array($resultado);

				if ($fila)
				{
					$catalogo = self::obtenerDatos($fila);
				}
			}
			else
			{
				Logger::error("<CADCatalogo::obtenerPorId(id)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			$catalogo = NULL;
			Logger::error("<CADCatalogo::obtenerPorId(id) ".$e->getMessage());
		}

		return $catalogo;
	}

	/**
	 * Comprueba si un catálogo existe en la base de datos.
	 * @param int $id Identificador del catálogo, que es clave primaria en la tabla "catalogos" de la base de datos.
	 * @return bool Devuelve verdadero si existe el catálogo en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		$catalogo = self::obtenerPorId($id);
		return $catalogo != NULL;
	}

	/**
	 * Dado un catálogo, la guarda en la base de datos.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si el catálogo es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @param ENCatalogo $catalogo El catáogo que se va a guardar en la base de datos.
	 * @return bool Devuelve verdadero si ha creado un nuevo catálogo. Falso en caso contrario.
	 */
	public static function guardar($catalogo)
	{
		$guardado = false;

		if ($catalogo->getId() == 0)
		{
			try
			{
				$conexion = BD::conectar();
				if (BD::begin($conexion))
				{
					$error = true;

					// Insertamos el catálogo.
					$sentencia = "insert into catalogos (titulo, id_usuario, fecha_insercion) ";
					$sentencia = $sentencia."values ('".$catalogo->getTitulo()."', '".$catalogo->getIdUsuario()."', now());\n";
					$resultado = mysql_query($sentencia, BD::conectar());
					
					if ($resultado)
					{
						// Si se ha insertado, extraemos su identificador.
						$sentencia = "select max(id) from catalogos";
						$resultado = mysql_query($sentencia, BD::conectar());

						if ($resultado)
						{
							$fila = mysql_fetch_array($resultado);
							if ($fila)
							{
								$catalogo->setId($fila[0]);
								$guardado = true;
								$error = false;
							}
						}
						else
						{
							echo mysql_error();
						}
					}
					else
					{
						echo mysql_error();
					}

					// Si ocurrió un error, se cancela la operación.
					if ($error == true)
					{
						Logger::error("<CADCatalogo::guardar(catalogo)>".mysql_error());
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
				Logger::error("<CADCatalogo::guardar(catalogo) ".$e->getMessage());
			}
		}

		return $guardado;
	}

	/**
	 * Dado un catálogo que ya existe en la base de datos, actualiza sus datos en la base de datos.
	 * @param ENCatalogo $catalogo Catálogo que va a ser actualizado.
	 * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
	 */
	public static function actualizar($catalogo)
	{
		$actualizado = false;

		try
		{
			if (self::existePorId($catalogo->getId()))
			{
				// Actualizamos los datos de la foto.
				$sentencia = "update catalogos set ";
				$sentencia = $sentencia."titulo = '".$catalogo->getTitulo()."'";
				$sentencia = $sentencia.", id_usuario = ".$catalogo->getIdUsuario();
				$sentencia = $sentencia." where id = ".$catalogo->getId();

				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$actualizado = true;
				}
				else
				{
					Logger::error("<CADCatalogo::actualizar(catalogo)>".mysql_error());
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADCatalogo::actualizar(catalogo) ".$e->getMessage());
		}

		return $actualizado;
	}

	/**
	 * Dado un catálogo, lo elimina de la base de datos.
	 * @param ENCatalogo $catalogo Catálogo que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el catálogo. Falso en caso contrario.
	 */
	public static function borrar($catalogo)
	{
		return self::borrarPorId($catalogo->getId());
	}

	/**
	 * Dado el identificador de un catálogo, lo elimina de la base de datos.
	 * @param int $id Identificador del catálogo que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el catálogo. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		$borrado = false;

		try
		{
			if (self::existePorId($id))
			{
				$sentencia = "delete from catalogos where id = ".$id."";
				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$borrado = true;
				}
				else
				{
					Logger::error("<CADCatalogo::borrarPorId(id)>".mysql_error());
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADCatalogo::borrarPorId(id) ".$e->getMessage());
		}

		return $borrado;
	}

	/**
	 * Inserta un modelo en un catálogo.
	 * @param ENCatalogo $catalogo Catálogo al que se le va a insertar el modelo.
	 * @param int $id_modelo Identificador del modelo.
	 * @return bool Devuelve verdadero si se ha insertado correctamente.
	 */
	public static function insertarModelo ($catalogo, $id_modelo)
	{
		if ($catalogo != NULL && $id_modelo != NULL)
		{
			if (!self::existeModelo($catalogo, $id_modelo))
			{
				$sentencia = "insert into catalogos_modelos (id_modelo, id_catalogo, fecha_insercion) values ($id_modelo, ".$catalogo->getId().", now())";
				$resultado = @mysql_query ($sentencia, BD::conectar());
				if ($resultado)
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Comprueba si un modelo está en un determinado catálogo.
	 * @param ENCatalogo $catalogo Catálogo en el que se va a comprobar.
	 * @param int $id_modelo Identificador del modelo.
	 * @return bool Devuelve verdadero si el modelo está en el catálogo.
	 */
	public static function existeModelo ($catalogo, $id_modelo)
	{
		if ($catalogo != NULL && $id_modelo)
		{
			$sentencia = "select count(*) from catalogos_modelos where id_modelo = $id_modelo and id_catalogo = ".$catalogo->getId();
			$resultado = @mysql_query ($sentencia, BD::conectar());
			if ($resultado)
			{
				$fila = @mysql_fetch_array($resultado);
				if ($fila != NULL)
				{
					if ($fila[0] > 0)
					{
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Elimina un modelo de un catálogo.
	 * @param ENCatalogo $catalogo Catálogo al que se le va a quitar el modelo.
	 * @param int $id_modelo Identificador del modelo que se quiere quitar.
	 * @return bool Devuelve verdadero si ha eliminado el modelo del catálogo.
	 */
	public static function quitarModelo ($catalogo, $id_modelo)
	{
		if ($catalogo != NULL && $id_modelo != NULL)
		{
			if (self::existeModelo($catalogo, $id_modelo))
			{
				$sentencia = "delete from catalogos_modelos where id_modelo = $id_modelo and id_catalogo = ".$catalogo->getId();
				$resultado = @mysql_query ($sentencia, BD::conectar());
				if ($resultado)
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Obtiene todos los modelos de un determinado catálogo.
	 * @param ENCatalogo $catalogo Catálogo del que se quieren conocer sus modelos.
	 * @return array Devuelve todos los modelos (array de ENModelo) de un determinado catálogo. Si falla, devuelve NULL.
	 */
	public static function obtenerModelos ($catalogo)
	{
		$listaModelos = NULL;

		if ($catalogo != NULL)
		{
			try
			{
				$sentencia = "select modelos.* from modelos, catalogos_modelos where modelos.id = catalogos_modelos.id_modelo and catalogos_modelos.id_catalogo = ".$catalogo->getId()." order by catalogos_modelos.fecha_insercion desc";
				$resultado = mysql_query($sentencia, BD::conectar());

				if ($resultado)
				{
					$listaModelos = array();
					$contador = 0;
					while ($fila = mysql_fetch_array($resultado))
					{
						$modelo = CADModelo::obtenerDatos($fila);
						if ($modelo != NULL)
						{
							$listaModelos[$contador++] = $modelo;
						}
						else
						{
							Logger::aviso("<CADCatalogo::obtenerModelos(catalogo)> Modelo nulo nº $contador");
						}
					}
				}
				else
				{
					Logger::error("<CADCatalogo::obtenerModelos(catalogo)>".mysql_error());
				}
			}
			catch (Exception $e)
			{
				$listaModelos = NULL;
				Logger::error("<CADCatalogo::obtenerModelos(catalogo) ".$e->getMessage());
			}
		}

		return $listaModelos;
	}
}
?>
