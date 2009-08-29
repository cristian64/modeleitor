<?php
require_once 'ENFabricante.php';
require_once 'ENFoto.php';
require_once 'Logger.php';
require_once 'CADModelo.php';
/**
 * Clase que representa un modelo con una referencia, una descripción, precio, etcétera.
 */
class ENModelo
{
	/**
	 * Identificador del modelo. Es clave candidata en la base de datos.
	 * @var int
	 */
	private $id;

	/**
	 * Referencia del modelo. Puede estar repetido en distintos modelos; alfanumérico.
	 * @var string
	 */
	private $modelo;

	/**
	 * Descripción del modelo.
	 * @var string
	 */
	private $descripcion;

	/**
	 * Precio de venta del modelo.
	 * @var float
	 */
	private $precio_venta;

	/**
	 * Precio de compra del modelo.
	 * @var float
	 */
	private $precio_compra;

	/**
	 * Precio de venta minorista del modelo.
	 * @var float
	 */
	private $precio_venta_minorista;

	/**
	 * Primer año de fabricación del modelo.
	 * @var int
	 */
	private $primer_ano;

	/**
	 * Fabricante del modelo.
	 * @var ENFabricante
	 */
	private $fabricante;

	/**
	 * Litsa de elementos del tipo ENFoto con las fotos del modelo.
	 * @var array
	 */
	private $fotos;

	/**
	 * Constructor por defecto.
	 */
	public function  __construct()
	{
		$this->id = 0;
		$this->modelo = "";
		$this->descripcion = "";
		$this->precio_venta = -1;
		$this->precio_compra = -1;
		$this->precio_venta_minorista = -1;
		$this->primer_ano = -1;
		$this->fabricante = NULL;
		$this->fotos = array();
	}

	/**
	 * Obtiene el identificador del modelo.
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Establece el identificador del modelo. Sólo puede asignarse una vez.
	 * @param int $id Nuevo valor para el identificador del modelo.
	 * @return bool Devuelve verdadero si ha modificado el identificador. Falso en caso contrario.
	 */
	public function setId($id)
	{
		if (is_numeric($id))
		{
			if ($id > 0 && $this->id==0)
			{
				$this->id = $id;
				return true;
			}
		}
		return false;
	}

	/**
	 * Obtiene la referencia ("modelo") del modelo.
	 * @return string
	 */
	public function getModelo()
	{
		return $this->modelo;
	}

	/**
	 * Establece una nueva referencia para el modelo.
	 * @param string $modelo Nuevo valor para la referencia del modelo.
	 * @return bool Devuelve verdadero si ha modificado el valor.
	 */
	public function setModelo($modelo)
	{
		if (is_string($modelo))
		{
			$this->modelo = $modelo;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene la descripción del modelo.
	 * @return string
	 */
	public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	 * Modifica la descripción del modelo.
	 * @param string $descripcion Nuevo valor para la descripción del modelor.
	 * @return bool Devuelve verdadero si ha modificado el valor.
	 */
	public function setDescripcion($descripcion)
	{
		if (is_string($descripcion) || true)
		{
			$this->descripcion = $descripcion;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene el precio de venta del modelo.
	 * @return float
	 */
	public function getPrecioVenta()
	{
		return $this->precio_venta;
	}

	/**
	 * Modifica el precio de venta del modelo.
	 * @param float $precio_venta Nuevo precio de venta para el modelo.
	 * @return bool Devuelve verdadero si ha modificado el valor.
	 */
	public function setPrecioVenta ($precio_venta)
	{
		if (is_numeric($precio_venta))
		{
			$this->precio_venta = $precio_venta;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene el precio de compra del modelo.
	 * @return float
	 */
	public function getPrecioCompra()
	{
		return $this->precio_compra;
	}

	/**
	 * Modifica el precio de compra del modelo.
	 * @param float $precio_compra Nuevo precio de compra para el modelo.
	 * @return bool Devuelve verdadero si ha modificado el valor.
	 */
	public function setPrecioCompra ($precio_compra)
	{
		if (is_numeric($precio_compra))
		{
			$this->precio_compra = $precio_compra;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene el precio de venta minorista del modelo.
	 * @return float
	 */
	public function getPrecioVentaMinorista()
	{
		return $this->precio_venta_minorista;
	}

	/**
	 * Modifica el precio de venta minorista del modelo.
	 * @param float $precio_venta_minorista Nuevo precio de venta minorista para el modelo.
	 * @return bool Devuelve verdadero si ha modificado el valor.
	 */
	public function setPrecioVentaMinorista ($precio_venta_minorista)
	{
		if (is_numeric($precio_venta_minorista))
		{
			$this->precio_venta_minorista = $precio_venta_minorista;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene el primer año de fabricación del modelo.
	 * @return int
	 */
	public function getPrimerAno()
	{
		return $this->primer_ano;
	}

	/**
	 * Modifica el primer año de fabricación del modelo.
	 * @param int $primer_ano Nuevo valor para el primer año de fabricación del modelo.
	 * @return bool Devuelve verdadero si ha modificado el valor.
	 */
	public function setPrimerAno($primer_ano)
	{
		if (is_numeric($primer_ano))
		{
			$this->primer_ano = $primer_ano;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene el fabricante (ENFabricante) del modelo.
	 * @return ENFabricante Devuelve el fabricante del modelo, o NULL si no tiene ninguno.
	 */
	public function getFabricante()
	{
		return $this->fabricante;
	}

	/**
	 * Modifica el fabricante del modelo.
	 * @param ENFabricante $fabricante Nuevo fabricante para el modelo.
	 * @return bool Devuelve verdadero si ha modificado el valor.
	 */
	public function setFabricante($fabricante)
	{
		if ($fabricante == NULL)
		{
			$this->fabricante = NULL;
			return true;
		}
		else
		{
			if (is_a($fabricante, "ENFabricante"))
			{
				$this->fabricante = $fabricante;
				return true;
			}
		}
		return false;
	}

	/**
	 * Obtiene la lista de fotos (ENFoto) del modelo.
	 * @return array Lista de elementos del tipo ENFoto.
	 */
	public function getFotos()
	{
		return $this->fotos;
	}

	/**
	 * Establece una nueva lista de fotos para el modelo.
	 * @param array $fotos Lista de elementos del tipo ENFoto que tendrá el modelo.
	 * @return bool Devuelve verdadero si ha modificado el valor.
	 */
	public function setFotos($fotos)
	{
		if (is_array($fotos))
		{
			$todosENFoto = true;
			foreach ($fotos as $i)
			{
				if (!is_a($i, "ENFoto"))
				{
					$todosENFoto = false;
					break;
				}
			}

			if ($todosENFoto)
			{
				$this->fotos = $fotos;
				return true;
			}
		}
		return false;
	}

	/**
	 * Obtiene un conjunto de caracteres con los atributos del modelo. Sobre todo para depuración.
	 * @return string
	 */
	public function toString()
	{
		$fabricante = "";
		if ($this->fabricante != NULL)
			$fabricante = " || ".$this->fabricante->getNombre()."(".$this->fabricante->getId().")";
		return "----- MODELO :: $this->id || $this->modelo || $this->descripcion || ($this->precio_venta, $this->precio_compra, $this->precio_venta_minorista) || $this->primer_ano $fabricante -----";
	}

	/**
	 * Obtiene todos los modelos que hay en la base de datos.
	 * @param int $id_fabricante Identificador del fabricante del que se quiere extraer todos sus modelos.
	 * @return array Devuelve una lista con todos los modelos de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerTodos($id_fabricante=NULL)
	{
		return CADModelo::obtenerTodos($id_fabricante);
	}

	/**
	 * Realiza una consulta siguiendo unos criterios de búsqueda.
	 * @param string $busqueda Cadena de caracteres por la que se filtrará la consulta.
	 * @param string $filtro Indica en qué campos se realizará la búsqueda: {modelos|descripcion|ambos}
	 * @param int $fabricante Identificador numérico del fabricante. Si no es un número, se ignora el parámetro.
	 * @param string $ordenar Campo por el que se ordena la búsqueda: {id, modelo, descripcion, precios..., primer_ano, fabricante}
	 * @param string $orden Orden descendente o ascendente: {descendente|ascendente}
	 * @param int $cantidad Cantidad de resultados que se quieren obtener.
	 * @param int $pagina Número de página que se debe mostrar.
	 * @return array Devuelve una lista con los modelos seleccionados de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerSeleccion($busqueda, $filtro, $fabricante, $ordenar, $orden, $cantidad, $pagina)
	{
		return CADModelo::obtenerSeleccion($busqueda, $filtro, $fabricante, $ordenar, $orden, $cantidad, $pagina);
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
		return CADModelo::cantidadSeleccion($busqueda, $filtro, $fabricante);
	}

	/**
	 * Obtiene un modelo desde la base de datos a partir de su identificador.
	 * @param int $id Identificador del modelo que se va a obtener.
	 * @return ENModelo Devuelve el modelo con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorId($id)
	{
		return CADModelo::obtenerPorId($id);
	}

	/**
	 * Comprueba si un modelo existe en la base de datos.
	 * @param int $id Identificador del modelo, que es clave primaria en la tabla "modelos" de la base de datos.
	 * @return bool Devuelve verdadero si existe el modelo en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		return CADModelo::existePorId($id);
	}

	/**
	 * Comprueba si un modelo existe en la base de datos.
	 * @return bool Devuelve verdadero si existe el modelo en la base de datos. Falso en caso contrario.
	 */
	public function existe()
	{
		return CADModelo::existePorId($this->id);
	}

	/**
	 * Guarda el modelo que invocó el método en la base de datos.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si el modelo es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @return bool Devuelve verdadero si ha creado un nuevo modelo. Falso en caso contrario.
	 */
	public function guardar()
	{
		return CADModelo::guardar($this);
	}

	/**
	 * Actualiza los datos del modelo que invocó el método en la base de datos.
	 * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
	 */
	public function actualizar()
	{
		return CADModelo::actualizar($this);
	}

	/**
	 * Eelimina el modelo que invocó el método de la base de datos.
	 * @return bool Devuelve verdadero si ha borrado el modelo. Falso en caso contrario.
	 */
	public function borrar()
	{
		return CADModelo::borrar($this);
	}

	/**
	 * Dado el identificador de un modelo, lo elimina de la base de datos.
	 * @param int $id Identificador del modelo que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el modelo. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		return CADModelo::borrarPorId($id);
	}
}
?>
