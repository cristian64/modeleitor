<?php
	session_start();
	
	require_once 'BD.php';
	BD::espeficarDatos("localhost", "root", "8520", "modeleitor");
	require_once 'ENFabricante.php';
	require_once 'ENFoto.php';
	require_once 'ENModelo.php';

	function filtrarCadena($cadena)
	{
		if ($cadena != NULL)
		{
			if (is_string($cadena))
			{
				$cadena = str_replace("'", "", $cadena);
				$cadena = str_replace("\\", "", $cadena);
				$cadena = str_replace("\"", "", $cadena);
				$cadena = str_replace("=", "", $cadena);
				$cadena = str_replace(">", "", $cadena);
				$cadena = str_replace("<", "", $cadena);
				$cadena = str_replace("\/", "", $cadena);
				$cadena = str_replace("/", "", $cadena);
				$cadena = str_replace("%", "", $cadena);
				$cadena = str_replace(";", ":", $cadena);
				$cadena = str_replace("|", "", $cadena);
				$cadena = str_replace("&", "", $cadena);
				return $cadena;
			}
		}
		return "";
	}

	function rellenar($cadena,$caracter,$digitos)
	{
		while (strlen($cadena)<$digitos)
		{
			$cadena = "$caracter$cadena";
		}
		return $cadena;
	}
?>
