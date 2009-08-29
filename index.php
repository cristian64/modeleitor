<?php
	session_start();
	if ($_SESSION["conectado"] == "si")
	{
		header("location: modelos.php");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Modeleitor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="all" type="text/css" href="estilo/estilo.css" />
	</head>
	<body onload="document.getElementById('camponombre').focus();">
		<div id="contenedor">
			<div id="menu">
			</div>

			<div id="contenido">
				<div id="titulo">
					<p>Iniciar sesión</p>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">

					<form action="login.php" method="post">
						<table class="insertiva">
							<tr>
								<td class="etiqueta">Nombre de usuario:</td>
								<td>
									<input id="camponombre" type="text" name="login" autocomplete="off" />
								</td>
							</tr>
							<tr>
								<td class="etiqueta">Contraseña:</td>
								<td><input type="password" name="password" autocomplete="off" /></td>
							</tr>
							<tr>
								<td colspan="2" class="botones">
									<input type="submit" value="Conectar" />
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
