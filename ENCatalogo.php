<?php
require_once 'CADCatalogo.php';
require_once 'ENModelo.php';
/**
 * Clase que representa un conjunto de modelos (catálogo).
 */
class ENCatalogo
{
	/**
	 * Identificador del catálogo que es clave cantidata en la base de datos.
	 * @var int
	 */
    private $id;

	/**
	 * Título (y descripción) del catálogo.
	 * @var string
	 */
	private $titulo;

	/**
	 * Identificador del usuario al que pertenece el catálogo.
	 * @var int
	 */
	private $id_usuario;

	/**
	 * Constructor por defecto.
	 * @param string $titulo Título para el catálogo.
	 */
	public function  __construct($titulo="")
	{
		$this->id = 0;
		$this->titulo = $titulo;
		$this->id_usuario = 0;
	}

	/**
	 * Obtiene el identificador del catálogo. Si es 0, signfica que no está guardado en la base de datos.
	 * @return int Devuelve el identificador del catálogo.
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Establece el identificador del catálogo. Sólo puede asignarse una vez.
	 * @param int $id Nuevo valor para el identificador del catálogo.
	 * @return bool Devuelve verdadero si ha modificado el identificador. Falso en caso contrario.
	 */
	public function setId($id)
	{
		if (is_numeric($id))
		{
			if ($id > 0 and $this->id == 0)
			{
				$this->id = $id;
				return true;
			}
		}
		return false;
	}

	/**
	 * Obtiene el título del catálogo.
	 * @return string Devuelve una cadena de caracteres.
	 */
	public function getTitulo()
	{
		return $this->titulo;
	}

	/**
	 * Establece el título del catálogo.
	 * @param string $titulo Nuevo valor para el título del catálogo.
	 * @return bool Devuelve verdadero si ha podido asignarse.
	 */
	public function setTitulo($titulo)
	{
		if (is_string($titulo))
		{
			$this->titulo = $titulo;
			return true;
		}
		return false;
	}

	/**
	 * Obtiene el identificador del usuario al que pertenece el catálogo.
	 * @return int Devuelve el identificador del usuario.
	 */
	public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	 * Establece el usuario (identificador) al que pertenece el modelo. Si no existe en la base de datos, fallará
	 * al actualizar o al guardar el catálgo.
	 * @param int $id_usuario Nuevo valor para el identificador del usuario.
	 */
	public function setIdUsuario($id_usuario)
	{
		if (is_numeric($id_usuario))
		{
			if ($id_usuario > 0)
			{
				$this->id_usuario = $id_usuario;
			}
		}
	}

	/**
	 * Obtiene un conjunto de caracteres con los atributos del catálogo. Sobre todo para depuración.
	 * @return string
	 */
	public function toString()
	{
		return "----- CATÁLOGO :: $this->id :: $this->titulo :: $this->id_usuairo -----";
	}

	/**
	 * Obtiene todos los catálogos que hay en la base de datos.
	 * @param int $id_usuario Identificador del usuario del que se quiere extraer todos sus catálogos.
	 * @return array Devuelve una lista con todos los catálogos de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerTodos($id_usuario=NULL)
	{
		return CADCatalogo::obtenerTodos($id_usuario);
	}

	/**
	 * Obtiene un catálogo desde la base de datos a partir de su identificador.
	 * @param int $id Identificador del catálogo que se va a obtener.
	 * @return ENCatalogo Devuelve el catálogo con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorId($id)
	{
		return CADCatalogo::obtenerPorId($id);
	}

	/**
	 * Comprueba si un catálogo existe en la base de datos.
	 * @param int $id Identificador del catálogo, que es clave primaria en la tabla "catalogos" de la base de datos.
	 * @return bool Devuelve verdadero si existe el catálogo en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		return CADCatalogo::existePorId($id);
	}

	/**
	 * Comprueba si el catálogo que invocó el método existe en la base de datos.
	 * @return bool Devuelve verdadero si existe el catálogo en la base de datos. Falso en caso contrario.
	 */
	public function existe()
	{
		return CADCatalogo::existePorId($this->id);
	}

	/**
	 * Guarda el catálogo que invocó el método en la base de datos.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si el catálogo es nuevo, utilizarás "guardar". Si ha sido extraido de la base de datos, se utilizará "actualizar".
	 * @return bool Devuelve verdadero si ha creado un nuevo catálogo. Falso en caso contrario.
	 */
	public function guardar()
	{
		return CADCatalogo::guardar($this);
	}

	/**
	 * Actualiza los datos del catálogo en la base de datos.
	 * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
	 */
	public function actualizar()
	{
		return CADCatalogo::actualizar($this);
	}

	/**
	 * Elimina el catálogo que invocó el método de la base de datos.
	 * @return bool Devuelve verdadero si ha borrado el catálogo. Falso en caso contrario.
	 */
	public function borrar()
	{
		return CADCatalogo::borrar($this);
	}

	/**
	 * Dado el identificador de un catálogo, lo elimina de la base de datos.
	 * @param int $id Identificador del catálogo que va a ser borrado.
	 * @return bool Devuelve verdadero si ha borrado el catálogo. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		return CADCatalogo::borrarPorId($id);
	}

	/**
	 * Inserta un modelo en el catálogo que invocó el método.
	 * @param int $id_modelo Identificador del modelo.
	 * @return bool Devuelve verdadero si se ha insertado correctamente.
	 */
	public function insertarModelo ($id_modelo)
	{
		return CADCatalogo::insertarModelo($this, $id_modelo);
	}

	/**
	 * Comprueba si un modelo está en el catálogo que invocó el método.
	 * @param int $id_modelo Identificador del modelo.
	 * @return bool Devuelve verdadero si el modelo está en el catálogo.
	 */
	public function existeModelo ($id_modelo)
	{
		return CADCatalogo::existeModelo ($this, $id_modelo);
	}

	/**
	 * Elimina un modelo del modelo que invocó el método.
	 * @param int $id_modelo Identificador del modelo que se quiere quitar.
	 * @return bool Devuelve verdadero si ha eliminado el modelo del catálogo.
	 */
	public function quitarModelo ($id_modelo)
	{
		return CADCatalogo::quitarModelo($this, $id_modelo);
	}

	/**
	 * Obtiene todos los modelos del catálogo que invocó el método.
	 * @return array Devuelve todos los modelos (array de ENModelo). Si falla, devuelve NULL.
	 */
	public function obtenerModelos ()
	{
		return CADCatalogo::obtenerModelos($this);
	}
}
?>
