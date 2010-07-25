<?php
require_once 'Logger.php';
require_once 'BD.php';
require_once 'ENModelo.php';
/**
 * Componente de acceso a datos para la clase ENModelo.
 */
class CADModelo
{
	/**
	 * Procesa una fila y devuelve un objeto elaborado con el modelo.
	 * @param array $fila Tantas componentes como columnas tiene la tabla "modelos" de la base de datos.
	 * @return ENModelo Devuelve el modelo con todos sus atributos.
	 */
	public static function obtenerDatos($fila)
	{
		$modelo = new ENModelo;
		$modelo->setId($fila[0]);
		$modelo->setModelo(utf8_encode($fila[1]));
		$modelo->setDescripcion(utf8_encode($fila[2]));
		$modelo->setPrecioVenta($fila[3]);
		$modelo->setPrecioCompra($fila[4]);
		$modelo->setPrecioVentaMinorista($fila[5]);
		$modelo->setPrimerAno($fila[6]);
		$modelo->setFabricante(ENFabricante::obtenerPorId($fila[7]));
		$modelo->setFotos(ENFoto::obtenerTodos($fila[0]));
		return $modelo;
	}

	/**
	 * Obtiene todos los modelos que hay en la base de datos.
	 * @param int $id_fabricante Identificador del fabricante del que se quiere extraer todos sus modelos.
	 * @return array Devuelve una lista con todos los modelos de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerTodos($id_fabricante=NULL)
	{
		$listaModelos = NULL;

		try
		{
			$sentencia = "";
			if ($id_fabricante == NULL)
				$sentencia = "select * from modelos order by id desc";
			else
				$sentencia = "select * from modelos where id_fabricante = $id_fabricante order by id desc";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$listaModelos = array();
				$contador = 0;
				while ($fila = mysql_fetch_array($resultado))
				{
					$modelo = self::obtenerDatos($fila);
					if ($modelo != NULL)
					{
						$listaModelos[$contador++] = $modelo;
					}
					else
					{
						Logger::aviso("<CADModelo::obtenerTodos(id_fabricante=NULL)> Modelo nulo nº $contador");
					}
				}
			}
			else
			{
				Logger::error("<CADModelo::obtenerTodos(id_fabricante=NULL)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			$listaModelos = NULL;
			Logger::error("<CADModelo::obtenerTodos(id_fabricante=NULL) ".$e->getMessage());
		}

		return $listaModelos;
	}

	public static function limpiarEspacios ($cadena)
	{
		$anterior = " ";
		$final = "";
		$cadena = trim($cadena);

		for ($i=0; $i<strlen($cadena); $i++)
		{
			if ($cadena[$i] == " ")
			{
				if ($anterior != " ")
				{
					$final = $final.$cadena[$i];
				}
			}
			else
			{
				$final = $final.$cadena[$i];
			}
			$anterior = $cadena[$i];
		}

		return $final;
	}

	/**
	 * Realiza una consulta siguiendo unos criterios de búsqueda.
	 * @param string $busqueda Cadena de caracteres por la que se filtrará la consulta.
	 * @param string $filtro Indica en qué campos se realizará la búsqueda: {modelo|descripcion|ambos}
	 * @param int $fabricante Identificador numérico del fabricante. Si no es un número, se ignora el parámetro.
	 * @param string $ordenar Campo por el que se ordena la búsqueda: {id, modelo, descripcion, precios..., primer_ano, fabricante}
	 * @param string $orden Orden descendente o ascendente: {descendente|ascendente}
	 * @param int $cantidad Cantidad de resultados que se quieren obtener.
	 * @param int $pagina Número de página que se debe mostrar.
	 * @return array Devuelve una lista con los modelos seleccionados de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerSeleccion($busqueda, $filtro, $fabricante, $ordenar, $orden, $cantidad, $pagina)
	{
		$listaModelos = NULL;

		try
		{
			// Preparamos los parámetros de la consulta.
			if ($filtro != "modelo" && $filtro != "descripcion") $filtro = "ambos";
			if (!is_numeric($fabricante)) $fabricante = "";

			if ($ordenar != "modelo" && $ordenar != "descripcion" && $ordenar != "precio_venta" && $ordenar != "precio_venta_minorista" && $ordenar != "precio_compra" && $ordenar != "primer_ano" && $ordenar != "fabricante")
				$ordenar = "id";
			if ($ordenar == "fabricante") $ordenar = "fabricantes.nombre";
			if ($orden == "ascendente")
				$orden = "asc";
			else
				$orden = "desc";
			if ($cantidad <= 0) $cantidad = 10;
			if ($pagina <= 0) $pagina = 1;

			$order = " order by ".$ordenar." ".$orden;
			$limit = " limit ".($pagina-1)*$cantidad.",".$cantidad;

			$sentencia = "select modelos.* from modelos, fabricantes where modelos.id_fabricante = fabricantes.id";
			if ($fabricante != "")
				$sentencia = $sentencia." and fabricantes.id = $fabricante";

			$busqueda = str_replace ("'", "", $busqueda);
			$busqueda = str_replace (";", "", $busqueda);
			if ($busqueda != "")
			{
				$busqueda = self::limpiarEspacios($busqueda);
				$busquedas = split(" ", $busqueda);
				if (count($busquedas)>0)
					$sentencia = $sentencia." and (1=1 ";

				if ($filtro == "modelo")
					foreach ($busquedas as $palabra)
					{
						$sentencia = $sentencia." and (modelo like '%$palabra%')";
					}

				else if ($filtro == "descripcion")
					foreach ($busquedas as $palabra)
					{
						$sentencia = $sentencia." and (descripcion like '%$palabra%')";
					}

				else
					foreach ($busquedas as $palabra)
					{
						$sentencia = $sentencia." and (modelo like '%$palabra%' or descripcion like '%$palabra%')";
					}

				if (count($busquedas)>0)
					$sentencia = $sentencia.") ";
			}

			$sentencia = $sentencia.$order.$limit;

			// Finalmente, realizamos la consulta.
			$resultado = mysql_query(utf8_decode($sentencia), BD::conectar());

			if ($resultado)
			{
				$listaModelos = array();
				$contador = 0;
				while ($fila = mysql_fetch_array($resultado))
				{
					$modelo = self::obtenerDatos($fila);
					if ($modelo != NULL)
					{
						$listaModelos[$contador++] = $modelo;
					}
					else
					{
						Logger::aviso("<CADModelo::obtenerSeleccion(...)> Modelo nulo nº $contador");
					}
				}
			}
			else
			{
				Logger::error("<CADModelo::obtenerSeleccion(...)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			$listaModelos = NULL;
			Logger::error("<CADModelo::obtenerSeleccion(...) ".$e->getMessage());
		}

		return $listaModelos;
	}

	/**
	 * Calcula el número de resultados que habría si se realiza una consulta con esos criterios.
	 * @param string $busqueda Cadena de caracteres por la que se filtrará la consulta.
	 * @param string $filtro Indica en qué campos se realizará la búsqueda: {modelos|descripcion|ambos}
	 * @param int $fabricante Identificador numérico del fabricante. Si no es un número, se ignora el parámetro.
	 * @return int Devuelve la cantidad de resultados que tendría la consulta anterior.
	 */
	public static function cantidadSeleccion ($busqueda, $filtro, $fabricante)
	{
		$cantidadModelos = 0;

		try
		{
			// Preparamos los parámetros de la consulta.
			if ($filtro != "modelo" && $filtro != "descripcion") $filtro = "ambos";
			if (!is_numeric($fabricante)) $fabricante = "";

			$sentencia = "select count(*) from modelos, fabricantes where modelos.id_fabricante = fabricantes.id";
			if ($fabricante != "")
				$sentencia = $sentencia." and fabricantes.id = $fabricante";

			$busqueda = str_replace ("'", "", $busqueda);
			$busqueda = str_replace (";", "", $busqueda);
			if ($busqueda != "")
			{
				$busqueda = self::limpiarEspacios($busqueda);
				$busquedas = split(" ", $busqueda);
				if (count($busquedas)>0)
					$sentencia = $sentencia." and (1=1 ";

				if ($filtro == "modelo")
					foreach ($busquedas as $palabra)
					{
						$sentencia = $sentencia." and (modelo like '%$palabra%')";
					}

				else if ($filtro == "descripcion")
					foreach ($busquedas as $palabra)
					{
						$sentencia = $sentencia." and (descripcion like '%$palabra%')";
					}

				else
					foreach ($busquedas as $palabra)
					{
						$sentencia = $sentencia." and (modelo like '%$palabra%' or descripcion like '%$palabra%')";
					}

				if (count($busquedas)>0)
					$sentencia = $sentencia.") ";
			}

			$sentencia = $sentencia.$order.$limit;

			// Finalmente, realizamos la consulta.
			$resultado = mysql_query(utf8_decode($sentencia), BD::conectar());

			if ($resultado)
			{
				$fila = mysql_fetch_array($resultado);
				if ($fila)
				{
					$cantidadModelos = $fila[0];
				}
				else
				{
					Logger::aviso("<CADModelo::cantidadSeleccion(...)> Modelo nulo nº $contador");
				}
			}
			else
			{
				Logger::error("<CADModelo::cantidadSeleccion(...)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			$cantidadModelos = 0;
			Logger::error("<CADModelo::cantidadSeleccion(...) ".$e->getMessage());
		}

		return $cantidadModelos;
	}

	/**
	 * Obtiene un modelo desde la base de datos a partir de su identificador.
	 * @param int $id Identificador del modelo que se va a obtener.
	 * @return ENModelo Devuelve el modelo con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorId($id)
	{
		$modelo = NULL;

		try
		{
			$sentencia = "select * from modelos where id = ".$id."";
			$resultado = mysql_query($sentencia, BD::conectar());

			if ($resultado)
			{
				$fila = mysql_fetch_array($resultado);

				if ($fila)
				{
					$modelo = self::obtenerDatos($fila);
				}
			}
			else
			{
				Logger::error("<CADModelo::obtenerPorId(id)>".mysql_error());
			}
		}
		catch (Exception $e)
		{
			$modelo = NULL;
			Logger::error("<CADModelo::obtenerPorId(id) ".$e->getMessage());
		}

		return $modelo;
	}

	/**
	 * Comprueba si un modelo existe en la base de datos.
	 * @param int $id Identificador del modelo, que es clave primaria en la tabla "modelos" de la base de datos.
	 * @return bool Devuelve verdadero si existe el modelo en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		$modelo = self::obtenerPorId($id);
		return $modelo != NULL;
	}

	/**
	 * Dado un modelo, la guarda en la base de datos.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si el modelo es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @param ENModelo $modelo El modelo que se va a guardar en la base de datos.
	 * @return bool Devuelve verdadero si ha creado un nuevo modelo. Falso en caso contrario.
	 */
	public static function guardar($modelo)
	{
		$guardado = false;

		if ($modelo->getId() == 0 && $modelo->getFabricante() != NULL)
		{
			try
			{
				$conexion = BD::conectar();
				if (BD::begin($conexion))
				{
					$error = true;

					// Insertamos el modelo.
					$sentencia = "insert into modelos (modelo, descripcion, precio_venta, precio_compra, precio_venta_minorista, primer_ano, id_fabricante, fecha_insercion) ";
					$sentencia = $sentencia."values ('".utf8_decode($modelo->getModelo())."', '".utf8_decode($modelo->getDescripcion())."', ".$modelo->getPrecioVenta().", ".$modelo->getPrecioCompra().", ".$modelo->getPrecioVentaMinorista().", ".$modelo->getPrimerAno().", ".$modelo->getFabricante()->getId().", now());\n";
					$resultado = mysql_query($sentencia, BD::conectar());

					if ($resultado)
					{
						// Si se ha insertado, extraemos su identificador.
						$sentencia = "select max(id) from modelos";
						$resultado = mysql_query($sentencia, BD::conectar());

						if ($resultado)
						{
							$fila = mysql_fetch_array($resultado);
							if ($fila)
							{
								$modelo->setId($fila[0]);
								$guardado = true;
								$error = false;
							}
						}
					}

					// Si ocurrió un error, se cancela la operación.
					if ($error == true)
					{
						Logger::error("<CADModelo::guardar(modelo)>".mysql_error());
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
				Logger::error("<CADModelo::guardar(modelo) ".$e->getMessage());
			}
		}

		return $guardado;
	}

	/**
	 * Dado un modelo que ya existe en la base de datos, actualiza sus datos en la base de datos.
	 * @param ENModelo $modelo Modelo que va a ser actualizado.
	 * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
	 */
	public static function actualizar($modelo)
	{
		$actualizado = false;

		try
		{
			if (self::existePorId($modelo->getId()))
			{
				// Actualizamos los datos de la foto.
				$sentencia = "update modelos set ";
				$sentencia = $sentencia."modelo = '".utf8_decode($modelo->getModelo())."'";
				$sentencia = $sentencia.", descripcion = '".utf8_decode($modelo->getDescripcion())."'";
				$sentencia = $sentencia.", precio_venta = ".$modelo->getPrecioVenta();
				$sentencia = $sentencia.", precio_compra = ".$modelo->getPrecioCompra();
				$sentencia = $sentencia.", precio_venta_minorista = ".$modelo->getPrecioVentaMinorista();
				$sentencia = $sentencia.", primer_ano = ".$modelo->getPrimerAno();
				if ($modelo->getFabricante() != NULL)
					$sentencia = $sentencia.", id_fabricante = ".$modelo->getFabricante()->getId();
				$sentencia = $sentencia." where id = ".$modelo->getId();

				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$actualizado = true;
				}
				else
				{
					Logger::error("<CADModelo::actualizar(modelo)>".mysql_error());
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADModelo::actualizar(modelo) ".$e->getMessage());
		}

		return $actualizado;
	}

	/**
	 * Dado un modelo, lo elimina de la base de datos.
	 * @param ENModelo $modelo Modelo que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el modelo. Falso en caso contrario.
	 */
	public static function borrar($modelo)
	{
		return self::borrarPorId($modelo->getId());
	}

	/**
	 * Dado el identificador de un modelo, lo elimina de la base de datos.
	 * @param int $id Identificador del modelo que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el modelo. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		$borrado = false;

		try
		{
			if (self::existePorId($id))
			{
				$sentencia = "delete from modelos where id = ".$id."";
				$resultado = mysql_query($sentencia, BD::conectar());
				if ($resultado)
				{
					$borrado = true;
				}
				else
				{
					Logger::error("<CADModelo::borrarPorId(id)>".mysql_error());
				}
			}
		}
		catch (Exception $e)
		{
			Logger::error("<CADModelo::borrarPorId(id) ".$e->getMessage());
		}

		return $borrado;
	}
}
?>
