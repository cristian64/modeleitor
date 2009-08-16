<?php
/**
 * Clase que se encarga de registrar mensajes, avisos y errores.
 */
class Logger
{
	public static function mensaje($mensaje)
	{
		echo $mensaje;
	}

	public static function aviso($aviso)
	{
		echo $aviso;
	}

	public static function error($error)
	{
		echo $error;
	}
}
?>
