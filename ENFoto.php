<?php
/**
 * Clase que representa a una foto con ruta al fichero de la foto (cadena de caracteres).
 */
class ENFoto
{
	private $id;
	private $descripcion;
	private $rutaFoto;
	private $rutaMiniatura;
	private $id_modelo;

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

	public function getRutaFoto()
	{
		return $this->rutaFoto;
	}

	public function anadirFoto($foto, $rutaForo, $rutaMiniatura)
	{
		// Hay que intentar borrar las anteriores.

		// Luego hay que copiar el fichero de la imagen a la ruta de la foto.

		// Y, por último, reducir la miniatura a un tamaño que será estático de la clase. (falta una librería!)
	}

	public function getRutaMiniatura()
	{
		return $this->rutaMiniatura;
	}

	public function getIdModelo()
	{
		return $this->id_modelo;
	}

	public function setIdModelo ($id_modelo)
	{
		if ($id_modelo > 0)
		{
			$this->id_modelo = $id_modelo;
			return true;
		}
		else
		{
			$this->id_modelo = NULL;
			return false;
		}
	}
}
?>