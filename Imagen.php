<?php
require_once 'Logger.php';
/**
 * Clase que representa una imagen. Soporta los siguientes formatos: JPEG.
 */
class Imagen
{
	private $rutaFichero;
	private $tamanoFichero;
	private $ancho;
	private $alto;

	public function  __construct($rutaFichero)
	{
		$this->tamanoFichero = -1;
		$this->ancho = 0;
		$this->alto = 0;

		if (file_exists($rutaFichero))
		{
			$this->rutaFichero = $rutaFichero;
			$this->tamanoFichero = filesize($rutaFichero);
			$tamanoImagen = getimagesize($rutaFichero);
			$this->ancho = $tamanoImagen[0];
			$this->alto = $tamanoImagen[1];
		}
		else
		{
			Logger::error("<Imagen::__construct(rutaFichero)> No existe el fichero <<".$rutaFichero.">>");
		}
	}

	public function getTamanoFichero()
	{
		return $this->tamanoFichero;
	}

	public function getAnchoImagen()
	{
		return $this->ancho;
	}

	public function getAltoImagen()
	{
		return $this->alto;
	}

	public function redimensionar($width, $height, $rutaMiniatura)
	{
		$redimensionado = false;
		$foto = @imagecreatefromjpeg($this->rutaFichero);
		if ($foto)
		{
			$miniatura = imagecreatetruecolor($width, $height);
			if ($miniatura)
			{
				if (@imagecopyresized($miniatura, $foto, 0, 0, 0, 0, $width, $height, $this->ancho, $this->alto))
				{
					if (@imagejpeg($miniatura, $rutaMiniatura, 100))
					{
						@imagedestroy($miniatura);
						@imagedestroy($foto);
						$redimensionado = true;
					}
					else
					{
						Logger::error("<Imagen::redimensionar(...)> No se puede guardar la imagen JPEG.");
					}
				}
				else
				{
					Logger::error("<Imagen::redimensionar(...)> No se puede redimensionar la imagen JPEG.");
				}
			}
			else
			{
				Logger::error("<Imagen::redimensionar(...)> No se puede crear una imagen vacía.");
			}
		}
		else
		{
			Logger::error("<Imagen::redimensionar(...)> No se puede abrir la imagen JPEG.");
		}

		return $redimensionado;
	}

	public static function borrar($rutaFichero)
	{
		if ($rutaFichero != NULL)
		{
			if (file_exists($rutaFichero))
				return @unlink($rutaFichero);
			else
				return false;
		}
		else
		{
			if (file_exists($rutaFichero))
				return @unlink($rutaFichero);
			else
				return false;
		}
	}
}
?>
