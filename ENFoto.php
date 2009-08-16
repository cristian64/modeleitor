<?php
/**
 * Description of ENFoto
 *
 * @author cristian
 */
class ENFoto
{
	private static $rutaFoto = "fotos/";
	private static $rutaMiniatura = "miniaturas/";
	
	private $id;
	private $descripcion;

	public function getId()
	{
		return $this->id;
	}

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

	public function getDescripcion()
	{
		return $this->descripcion;
	}

	public function setDescripcion($descripcion)
	{
		$this->descripcion = $descripcion;
	}


}
?>
