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
		if ($id > 0 && $this->id==0)
		{
			$this->id = $id;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getModelo()
	{
		return $this->modelo;
	}

	public function setModelo($modelo)
	{
		$this->modelo = $modelo;
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
	 */
	public function setDescripcion($descripcion)
	{
		$this->descripcion = $descripcion;
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
	 */
	public function setPrecioVenta ($precio_venta)
	{
		$this->precio_venta = $precio_venta;
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
	 */
	public function setPrecioCompra ($precio_compra)
	{
		$this->precio_compra = $precio_compra;
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
	 */
	public function setPrecioVentaMinorista ($precio_venta_minorista)
	{
		$this->precio_venta_minorista = $precio_venta_minorista;
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
	 */
	public function setPrimerAno($primer_ano)
	{
		$this->primer_ano = $primer_ano;
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
	 */
	public function setFabricante($fabricante)
	{
		$this->fabricante = $fabricante;
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
	 */
	public function setFotos($fotos)
	{
		$this->fotos = $fotos;
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
}
?>
