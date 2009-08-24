<?php
require_once 'Logger.php';
require_once 'CADColor.php';
/**
 * Clase que representa un color con un nombre y un valor de color en hexadecimal.
 */
class ENColor
{
	/**
	 * Identificador del color. Es clave candidata en la base de datos.
	 * @var int
	 */
	 private $id;

	/**
	 * Nombre del color.
	 * @var string 
	 */
    private $nombre;

	/**
	 * Valor hexadecimal del color.
	 * @var <type>
	 */
	private $rgb;

	/**
	 * Constructor de la clase.
	 * @param string $nombre Nombre del color. Por ejemplo, 'rojo'.
	 * @param string $rgb Valor hexadecimal del color. Por ejemplo, 'ff0000'.
	 */
	public function  __construct($nombre="", $rgb="")
	{
		$this->id = 0;
		$this->nombre = $nombre;
		self::setRGB($rgb);
	}

	/**
	 * Obtiene el identificador del color.
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Modifica el identificador del color. Sólo puede cambiarse si el identificador es 0. Cuando se asigna, no puede modificarse.
	 * @param int $id Nuevo valor para el identificador del color.
	 * @return bool Devuelve verdadero si se ha podido cambiar el identificador.
	 */
	public function setId($id)
	{
		if ($id > 0 && $this->id == 0)
		{
			$this->id = $id;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtiene el nombre del color.
	 * @return string
	 */
	public function getNombre()
	{
		return $this->nombre;
	}

	/**
	 * Modifica el nombre del color.
	 * @param string $nombre Nuevo valor para el nombre del color.
	 */
	public function setNombre($nombre)
	{
		$this->nombre = $nombre;
	}

	/**
	 * Obtiene el valor del color en hexadecimal. Por ejemplo, 'ff0000'.
	 * @return string
	 */
	public function getRGB()
	{
		return $this->rgb;
	}

	/**
	 * Modifica el valor hexadecimal del color. Por ejemplo, 'ff0000'.
	 * @param string $rgb Nuevo valor hexadecimal para el color.
	 * @return bool Devuelve verdadero si se ha podido cambiar el color. Falso en caso contrario.
	 */
	public function setRGB($rgb)
	{
		if (strlen($rgb)==3 || strlen($rgb)==6)
		{
			$this->rgb = $rgb;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Devuelve el color en formato hexadecimal. Por ejemplo, '#00ff00'.
	 * @return string
	 */
	public function toHex()
	{
		return '#'.$this->rgb;
	}

	/**
	 * Obtiene todos los colores que hay en la base de datos.
	 * @return array Devuelve una lista de ENColor con todos los colores de la base de datos.
	 */
	public static function obtenerTodos()
	{
		return CADColor::obtenerTodos();
	}

	/**
	 * Dado el nombre de un color, extrae el resto de atributos desde la base de datos.
	 * @param string $nombre Nombre del color, que es clave primaria en tabla "colores" de la base de datos.
	 * @return ENColor Devuelve el color con todos sus atributos. Si no existe el color en la base de datos, devuelve NULL.
	 */
	public static function obtenerPorNombre($nombre)
	{
		return CADColor::obtenerPorNombre($nombre);
	}

	/**
	 * Dado el identificador de un color, extrae el resto de atributos desde la base de datos.
	 * @param int $id Identificador del color, que es clave primaria en la tabla "colores" de la base de datos.
	 * @return ENColor Devuelve el color con todos sus atributos. Si no existe el color en la base de datos, devuelve NULL.
	 */
	public static function obtenerPorId($id)
	{
		return CADColor::obtenerPorId($id);
	}

	/**
	 * Comprueba si un color existe en la base de datos.
	 * @param string $nombre Nombre del color, que es clave primaria en la tabla "colores" de la base de datos.
	 * @return bool Devuelve verdadero si existe el color en la base de datos. Falso en caso contrario.
	 */
	public static function existePorNombre($nombre)
	{
		return CADColor::existePorNombre($nombre);
	}

	/**
	 * Comprueba si un color existe en la base de datos.
	 * @param int $id Identificador del color, que es clave primaria en la tabla "colores" de la base de datos.
	 * @return bool Devuelve verdadero si existe el color en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		return CADColor::existePorId($id);
	}

	/**
	 * Comprueba si el color que invocó el método existe en la base de datos.
	 * @return bool Devuelve verdadero si existe el color en la base de datos. Falso en caso contrario.
	 */
	public function existe()
	{
		return CADColor::existePorId($this->id);
	}

	/**
	 * Guarda en la base de datos el color que invocó el método.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si el color es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @return bool Devuelve verdadero si ha creado un nuevo color o ha actualizado un color existente. Falso en caso contrario.
	 */
	public function guardar()
	{
		return CADColor::guardar($this);
	}

	/**
	 * Actualiza en la base de datos el color que invocó el método.
	 * Ya debe existir el color en la base de datos.
	 * @return bool Devuelve verdadero si ha ha actualizado un color existente. Falso en caso contrario.
	 */
	public function actualizar()
	{
		return CADColor::actualizar($this);
	}

	/**
	 * Borra de la base de datos el color que invocó el método.
	 * @return bool Devuelve verdadero si se ha borrado el color. Falso en caso contrario.
	 */
	public function borrar()
	{
		return CADColor::borrar($this);
	}

	/**
	 * Dado el nombre de un color, lo elimina de la base de datos.
	 * @param string $nombre Nombre del color que se va a borrar de la base de datos.
	 * @return bool Devuelve verdadero si se ha borrado el color. Falso en caso contrario.
	 */
	public static function borrarPorNombre($nombre)
	{
		return CADColor::borrarPorNombre($nombre);
	}

	/**
	 * Dado el identificador de un color, lo elimina de la base de datos.
	 * @param int $id Identificador del color que se va a borrar de la base de datos.
	 * @return bool Devuelve verdadero si se ha borrado el color. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		return CADColor::borrarPorId($id);
	}
}
?>
