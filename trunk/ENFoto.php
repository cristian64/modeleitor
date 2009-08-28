<?php
require_once 'Logger.php';
require_once 'CADFoto.php';
require_once 'Imagen.php';
require_once 'resize.php';
/**
 * Clase que representa a una foto con ruta al fichero de la foto (cadena de caracteres).
 */
class ENFoto
{
	/**
	 * Anchura máxima para la miniatura de la fotografía.
	 * @var int
	 */
	private static $anchoMiniatura = 100;

	/**
	 * Altura máxima para la miniatura de la fotografía.
	 * @var int
	 */
	private static $altoMiniatura = 100;

	/**
	 * Ruta base para el guardado de miniaturas.
	 * @var string
	 */
	private static $rutaImagenes = "imagenes/";

	/**
	 * Identificador de la foto. Es clave candidata en la base de datos.
	 * @var int
	 */
	private $id;

	/**
	 * Descripción de la foto.
	 * @var string
	 */
	private $descripcion;

	/**
	 * Identificador del modelo al que hace referencia. Debe existir el modelo en la base de datos.
	 * @var int
	 */
	private $id_modelo;

	/**
	 * Constructor por defecto que inicializa los valores de la foto.
	 */
	public function  __construct()
	{
		$this->id = 0;
		$this->descripcion = "";
		$this->id_modelo = 0;
	}

	/**
	 * Obtiene la ruta relativa del fichero de la foto.
	 * @return string Devuelve una cadena de caracteres.
	 */
	public function getRutaFoto()
	{
		return self::$rutaImagenes.$this->id.".jpg";
	}

	/**
	 * Obtiene la ruta relativa del fichero de la miniatura.
	 * @return string Devuelve una cadena de caracteres.
	 */
	public function getRutaMiniatura()
	{
		return self::$rutaImagenes.$this->id."m.jpg";
	}

	/**
	 * Obtiene el identificador de la foto.
	 * @return int Identificador de la foto en la base de datos.
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Establece el identificador de la foto. Sólo puede asignarse una vez, y no puede volver a modificarse.
	 * @param int $id Nuevo identificador para la foto.
	 * @return bool Develve verdadero si se ha cambiado.
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
	 * Obtiene una cadena de caracteres con la descripción de la foto.
	 * @return string Cadena de caracteres con la descripción de la foto.
	 */
	public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	 * Modifica la descripción de la foto.
	 * @param string $descripcion Nuevo valor para la descripción de la foto.
	 * @return bool Develve verdadero si se ha cambiado.
	 */
	public function setDescripcion($descripcion)
	{
		if (is_string($descripcion))
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
	 * Dada una foto que acaba de ser enviada por el método post, la guarda en un fichero físico.
	 * @param resource $httpPostFile Elemento de $HTTP_POST_FILES ($_FILES) que se quiere guardar. Por ejemplo, $_FILES['foto_subida'].
	 * @return bool Devuelve verdadero si ha creado los ficheros correctamente (foto y miniatura).
	 */
	public function crearFicheroFoto($httpPostFile)
	{
		//http://emilio.aesinformatica.com/2007/05/03/subir-una-imagen-con-php/
		$creada = false;
		
		if (is_uploaded_file($httpPostFile['tmp_name']))
		{
			$rutaFoto = $this->getRutaFoto();
			$rutaMiniatura = $this->getRutaMiniatura();
			
			// Hay que intentar borrar las anteriores. No importa si falla.
			Imagen::borrar($rutaFoto);
			Imagen::borrar($rutaMiniatura);

			// Luego hay que copiar el fichero de la imagen a la ruta de la foto.
			if (move_uploaded_file($httpPostFile['tmp_name'], $rutaFoto))
			{
				if (chmod($rutaFoto,0777))
				{
					// Y, por último, reducir la miniatura a un tamaño que será estático de la clase.
					/*$foto = new Imagen($rutaFoto);


					$width = $foto->getAnchoImagen();
					$height = $foto->getAltoImagen();

					if ($foto->getAnchoImagen() > $foto->getAltoImagen())
					{
						$width = self::$anchoMiniatura;
						$height = $height * (self::$anchoMiniatura / (float) $foto->getAnchoImagen());
					}
					else
					{
						$height = self::$altoMiniatura;
						$width = $width * (self::$altoMiniatura / (float) $foto->getAltoImagen());
					}*/

					$miniatura=new thumbnail($rutaFoto);
					$miniatura->size_width(100);
					$miniatura->size_height(100);
					$miniatura->jpeg_quality(100);
					$miniatura->save($rutaMiniatura);
					$creada = true;

					/*if ($foto->redimensionar($width, $height, $rutaMiniatura))
					{
						$creada = true;
					}
					else
					{
						echo "no la he redimensionado<br/>";
					}*/
				}
				else
				{
					echo "no le he dado permisos<br/>";
				}
			}
			else
			{
				echo "no la he movido<br/>";
			}
		}
		else
		{
			echo "no está subida<br/>";
		}

		return $creada;
	}

	/**
	 * Borra los ficheros de las fotos físicamente. Debería invocarse este método antes de eliminar la foto de la base de datos.
	 * @return bool Devuelve verdadero si ha borrado la foto y su miniatura.
	 */
	public function borrarFicheroFoto()
	{
		$rutaFoto = $this->getRutaFoto();
		$rutaMiniatura = $this->getRutaMiniatura();

		$borrado = Imagen::borrar($rutaFoto);
		$borrado = $borrado && Imagen::borrar($rutaMiniatura);

		return $borrado;
	}

	/**
	 * Obtiene el identificador del modelo alq ue hace referencia la foto.
	 * @return int
	 */
	public function getIdModelo()
	{
		return $this->id_modelo;
	}

	/**
	 * Modifica el modelo al que pertenece la foto.
	 * @param int $id_modelo Nuevo valor para el identificador del modelo.
	 * @return bool Develve verdadero si se ha cambiado.
	 */
	public function setIdModelo ($id_modelo)
	{
		if (is_numeric($id_modelo))
		{
			if ($id_modelo > 0)
			{
				$this->id_modelo = $id_modelo;
				return true;
			}
		}
		return false;
	}

	/**
	 * Obtiene un conjunto de caracteres con los atributos de la foto. Sobre todo para depuración.
	 * @return string
	 */
	public function toString()
	{
		return "Foto ($this->id, $this->id_modelo): $this->descripcion";
	}

	/**
	 * Obtiene todos las fotos que hay en la base de datos. Si se especifica el identificador de un modelo, sólo extrae las fotos de ese modelo.
	 * @param int $id_modelo Identificador del modelo del que se extraerán sus fotos. Es un parámetro opcional.
	 * @return array Devuelve una lista con todas las fotos de la base de datos. Si hay algun error, devuelve NULL.
	 */
	public static function obtenerTodos($id_modelo=NULL)
	{
		return CADFoto::obtenerTodos($id_modelo);
	}

	/**
	 * Obtiene una foto desde la base de datos a partir de su identificador.
	 * @param int $id Identificador de la foto que se va a obtener.
	 * @return ENFoto Devuelve la foto con todos sus atributos extraidos desde la base de datos. Devuelve NULL si ocurrió algún error.
	 */
	public static function obtenerPorId($id)
	{
		return CADFoto::obtenerPorId($id);
	}

	/**
	 * Comprueba si una foto existe en la base de datos.
	 * @param int $id Identificador de la foto, que es clave primaria en la tabla "fotos" de la base de datos.
	 * @return bool Devuelve verdadero si existe la foto en la base de datos. Falso en caso contrario.
	 */
	public static function existePorId($id)
	{
		return CADFoto::existePorId($id);
	}

	/**
	 * Comprueba si la foto que invocó el método existe en la base de datos.
	 * @return bool Devuelve verdadero si existe la foto en la base de datos. Falso en caso contrario.
	 */
	public function existe()
	{
		return CADFoto::existePorId($this->id);
	}

	/**
	 * Guarda la foto que invocó el método en la base de datos.
	 * Sólo puede guardarse si no existe en la base de datos. Si ya existe, hay que utilizar el método "actualizar".
	 * Es decir, si la foto es nueva, utilizarás "guardar". Si ha sido extraida de la base de datos, se utilizará "actualizar".
	 * @return bool Devuelve verdadero si ha creado una nueva foto. Falso en caso contrario.
	 */
	public function guardar()
	{
		return CADFoto::guardar($this);
	}

	/**
	 * Actualiza la foto que invocó el método en la base de datos.
	 * @return bool Devuelve verdadero si se ha actualizado correctamente. Falso en caso contrario.
	 */
	public function actualizar()
	{
		return CADFoto::actualizar($this);
	}

	/**
	 * Elimina la foto que invocó el método de la base de datos (sólo de la base de datos, no borra los ficheros de la imagen).
	 * @return bool Devuelve verdadero si ha borrado la foto. Falso en caso contrario.
	 */
	public function borrar()
	{
		return CADFoto::borrar($this);
	}

	/**
	 * Dado el identificador de una foto, la elimina de la base de datos.
	 * @param int $id Identificador de la foto que se va a borrar de la base de datos.
	 * @return bool Devuelve verdadero si se ha borrado la foto. Falso en caso contrario.
	 */
	public static function borrarPorId($id)
	{
		return CADFoto::borrarPorId($id);
	}
}
?>