<?php
	require_once 'BD.php';
	BD::espeficarDatos("localhost", "root", "8520", "modeleitor");
	require_once 'ENFabricante.php';
	require_once 'ENFoto.php';
	require_once 'ENModelo.php';
	require_once 'ENCatalogo.php';

	function accesoValido()
	{
		if ($_SESSION["conectado"] != "si")
		{
			header("location: index.php");
			exit();
		}
	}

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

	function registrar ($detalles, $accion)
	{
		$usuario = $_SESSION["usuario"];
		$detalles = filtrarCadena($detalles);
		$accion = filtrarCadena($accion);
		$ip = $_SERVER["REMOTE_ADDR"];
		$sentencia = "insert into registro (nombre_usuario, detalles, accion, ip, fecha) values ('$usuario', '$detalles', '$accion', '$ip', now());";
		$resultado = @mysql_query($sentencia, BD::conectar());
		if ($resultado)
		{
			return true;
		}
		else
		{
			echo mysql_error();
			return false;
		}
	}
?>
