<?php
	session_start();
	require_once 'minilibreria.php';


	// Comprobamos la cantidad de intentos fallidos que tiene la IP.
	$intentos = 100;
	$ip = $_SERVER["REMOTE_ADDR"];
	$sentencia = "select count(*) from accesos where ip = '$ip' and exito = 'no'";
	$resultado = @mysql_query ($sentencia, BD::conectar());
	if ($resultado)
	{
		$fila = @mysql_fetch_array($resultado);
		if ($fila)
		{
			$intentos = $fila[0];
		}
	}

	$intentosMaximos = 10;

	// Realizamos un intento si hay menos que la cantidad máxima.
	if ($intentos <= $intentosMaximos)
	{
		$conectado = false;

		$login = filtrarCadena($_POST["login"]);
		$password = filtrarCadena($_POST["password"]);

		// Comprobamos que las cadenas sean válidas.
		$patron = "/^[a-zA-Z0-9]{1,25}$/";
		if (preg_match($patron, $login) && preg_match($patron, $password))
		{
			// Comprobamos que existe un usuario en la base de datos con ese usuario y contraseña.
			$sentencia = "select id from usuarios where nombre = '$login' and contrasena = '".sha1($password)."'";
			$resultado = @mysql_query($sentencia, BD::conectar());
			if ($resultado)
			{
				$fila = @mysql_fetch_array($resultado);
				if ($fila)
				{
					if ($fila[0]>0)
					{
						$_SESSION["id_usuario"] = $fila[0];
						$conectado = true;
					}
				}
			}
			else
			{
				echo mysql_error();
			}
		}

		if ($conectado)
		{
			$_SESSION["usuario"] = $login;
			$_SESSION["conectado"] = "si";
			$_SESSION["id_catalogo"] = 0;
			
			// Quitamos todos los intentos de "exito=no" a "exito=conseguido".
			// Añadimos un nuevo intento con "exito=si".
			$sentencia = "insert into accesos (ip, fecha, intento, exito) values ('$ip', now(), '$login', 'si')";
			@mysql_query ($sentencia, BD::conectar());

			$sentencia = "update accesos set exito = 'conseguido', intento = '$login' where ip = '$ip'";
			@mysql_query ($sentencia, BD::conectar());

			header("location: modelos.php");
			exit();
		}
		else
		{
			// Añadimos un nuevo intento con "exito=no".
			$sentencia = "insert into accesos (ip, fecha, intento, exito) values ('$ip', now(), '$login ::: $password', 'no')";
			@mysql_query ($sentencia, BD::conectar());
			header("location: index.php?error=Usuario o contraseña incorrecta (quedan ".($intentosMaximos-$intentos)." intentos).");
			exit();
		}
	}
	else
	{
		header("location: index.php?error=Demasiados intentos fallidos. Contacta con el administrador.");
		exit();
	}

?>
