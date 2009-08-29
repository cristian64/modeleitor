<?php
require_once 'Logger.php';
require_once('CADFabricante.php');
/**
 * Description of ENFabricante
 *
 * @author cristian
 */
class ENFabricante
{
	/**
	 * Identificador único del fabricante. Es clave candidata en la base de datos.
	 * @var int
	 */
	private $id;

	/**
	 * Nombre del fabricante. No puede repetirse en distintos fabricantes.
	 * @var string
	 */
	private $nombre;

	/**
	 * Información adicional del fabricante: dirección, email, teléfonos...
	 * @var string
	 */
	private $informacion_adicional;

	/**
	 * Listado de teléfonos del fabricante. Es una lista de listas. Cada una de las listas
	 * tiene dos componentes: (teléfono, descripción).
	 * @var array
	 */
	private $telefonos;

	/**
	 * Constructor de la clase.
	 * @param string $nombre Nombre del fabricante.
	 */
	public function  __construct($nombre="")
	{
		$this->id = 0;
		$this->nombre = $nombre;
		$this->telefonos = array();
	}

	/**
	 * Obtiene el identificador del fabricante.
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Modifica el identificador del fabricante. Sólo puede modificarse una vez. Después el método siempre devolverá falso.
	 * @param int $id Identificador que se va a establecer.
	 * @return bool Devuelve verdadero si se ha podido modificar.
	 */
	public function setId($id)
	{
		if (is_numeric($id))
		{
			if ($id > 0 && $this->id == 0)
			{
				$this->id = $id;
				return true;
			}
		}
		return false;
	}

	/**
	 * Obtiene el nombre del fabricante.
	 * @return string
	 */
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	 * Modifica el nombre del fabricante.
	 * @param string $nombre Nuevo nombre para el fabricante.
	 * @return bool Devuelve verdadero si se ha podido modificar.
	 */
	public function setNombre($nombre)
	{
		if (is_string($nombre))
		{
			$this->nombre = $nombre;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene la información adicional del fabricante.
	 * @return string
	 */
	public function getInformacionAdicional()
	{
		return $this->informacion_adicional;
	}

	/**
	 * Modifica la información adicional del fabricante.
	 * @param string $informacion_adicional Nuevo valor para la información adicional.
	 * @return bool Devuelve verdadero si ha modificado la información.
	 */
	public function setInformacionAdicional($informacion_adicional)
	{
		if (is_string($informacion_adicional))
		{
			$this->informacion_adicional = $informacion_adicional;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene los números de teléfono junto a la descripción de cada uno.
	 * @return array Lista de listas con el listado de teléfonos. Cada lista tiene dos componentes: (teléfono, descripción).
	 */
	public function getTelefonos()
	{
		return $this->telefonos;
	}

	/**
	 * Establece un listado de teléfonos para el fabricante.
	 * @param array $telefonos Listado de teléfonos.
	 * @return bool Devuelve verdadero si ha modificado la información.
	 */
	public function setTelefonos($telefonos)
	{
		if ($telefonos != NULL)
		{
			if (is_array($telefonos))
			{
				$this->telefonos = $telefonos;
				return true;
			}
		}
		return false;
	}

	/**
	 * Obtiene el listado de teléfonos en formato HTML (ul, li).
	 * @return string
	 */
	public function getTelefonosHTML()
	{
		$codigo = "<ul>";
		foreach ($this->telefonos as $i)
		{
			$codigo = $codigo."<li>";
			$codigo = $codigo.$i[0].' '.$i[1];
			$codigo = $codigo."</li>";
		}
		$codigo = $codigo."</ul>";

		return $codigo;
	}

	/**
	 * Inserta un teléfono en el fabricante.
	 * @param string $telefono Teléfono a introducir.
	 * @param string $descripcion Descripción del teléfono.
	 */
	public function insertarTelefono($telefono, $descripcion)
	{
		$telefonoAux = array();
		$telefonoAux[0] = $telefono;
		$telefonoAux[1] = $descripcion;
		$this->telefonos[count($this->telefonos)] = $telefonoAux;
	}

	/**
	 * Elimina un teléfono en el fabricante.
	 * @param string $telefono Teléfono que se quiere eliminar.
	 * @return bool Devuelve verdadero si se ha borrado el teléfono o no.
	 */
	public function borrarTelefono($telefono)
	{
		$borrado = false;
		$telefonosAux = array();
		foreach ($this->telefonos as $i)
		{
			if ($i[0]!=$telefono)
			{
				$telefonosAux[count($telefonosAux)] = $i;
			}
			else
			{
				$borrado = true;
			}
		}
		$this->telefonos = $telefonosAux;

		return $borrado;
	}

	/**
	 * Obtiene un conjunto de caracteres con los atributos del fabricante. Sobre todo para depuración.
	 * @return string
	 */
	public function toString()
	{
		return "-----FABRICANTE :: $this->nombre($this->id) || $this->informacion_adicional -----";
	}

	/**
	 * Obtiene todos los fabricantes que hay en la base de datos.
	 * @return array Devuelve una lista con todos los fabricantes de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerTodos()
	{
		return CADFabricante::obtenerTodos();
	}

	/**
	 * Obtiene un fabricante desde la base de datos a partir de su nombre.
	 * @param string $nombre Nombre del fabricante que se va a obtener.
	 * @return ENFabricante Devuelve el fabricante con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorNombre($nombre)
	{
		return CADFabricante::obtenerPorNombre($nombre);
	}

	/**
	 * Obtiene un fabricante desde la base de datos a partir de su identificador.
	 * @param int $id Identificador del fabricante que se va a obtener.
	 * @return ENFabricante Devuelve el fabricante con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorId($id)
	{
		return CADFabricante::obtenerPorId($id);
	}

	/**
	 * Comprueba si un fabricante existe en la base de datos.
	 * @param string $nombre Nombre del fabricante.
	 * @return bool Devuelve verdadero si existe el fabricante en la base de datos. Falso en caso contrario.
	 */
	public static function existePorNombre($nombre)
	{
		return CADFabricante::existePorNombre($nombre);
	}

	/**
	 * Comprueba si un fabricante existe en la base de datos.
	 * @param int $id Identificador del fabricante, que es clave primaria en la tabla "fabricantes" de la base de datos.
	 * @return bool Devuelve verdadero si existe el fabricante en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		return CADFabricante::existePorId($id);
	}

	/**
	 * Comprueba si el fabricante que invoca el método existe en la base de datos.
	 * @return bool Devuelve verdadero si existe el fabricante en la base de datos. Falso en caso contrario.
	 */
	public function existe()
	{
		return CADFabricante::existePorId($this->id);
	}

	/**
	 * Guarda en la base de datos el fabricante que invocó el método.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si el fahbricante es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @return bool Devuelve verdadero si se ha guardado correctamente. Falso en caso contrario.
	 */
	public function guardar()
	{
		return CADFabricante::guardar($this);
	}

	/**
	 * Actualiza en la base de datos el fabricante que invocó el método.
	 * Ya debe existir el fabricante en la base de datos.
	 * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
	 */
	public function actualizar()
	{
		return CADFabricante::actualizar($this);
	}

	/**
	 * Dado un fabricante, lo elimina de la base de datos (nombre, teléfonos, ...).
	 * @param ENFabricante $fabricante Fabricante que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el fabricante. Falso en caso contrario.
	 */
	public function borrar()
	{
		return CADFabricante::borrar($this);
	}

	/**
	 * Dado el nombre de fabricante, lo elimina de la base de datos (nombre, teléfonos, ...).
	 * @param string $nombre Nombre del fabricante que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el fabricante. Falso en caso contrario.
	 */
	public static function borrarPorNombre($nombre)
	{
		return CADFabricante::borrarPorNombre($nombre);
	}

	/**
	 * Dado el identificador de un fabricante, lo elimina de la base de datos (nombre, teléfonos, ...).
	 * @param int $id Identificador del fabricante que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el fabricante. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		return CADFabricante::borrarPorId($id);
	}
}
?>
