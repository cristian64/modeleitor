<?php
	/*require_once 'acceso.php';
	if (accesoValido)*/

	require_once 'BD.php';
	BD::espeficarDatos("localhost", "root", "8520", "modeleitor");
	require_once 'ENColor.php';
	require_once 'ENFabricante.php';
	require_once 'ENFoto.php';
	require_once 'ENModelo.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Modeleitor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="all" type="text/css" href="estilo/estilo.css" />
	</head>
	<body onload="document.getElementById('busqueda').focus();">
		<div id="contenedor">
			<div id="menu">
				<?php include 'menu.html'; ?>
			</div>

			<div id="contenido">
				<div id="titulo">
					<p>Añadir un nuevo fabricante</p>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">
					<form action="operarfabricante.php" method="post">
						<input type="hidden" name="operacion" value="insertar" />
						<table class="insertiva">
							<tr>
								<td class="etiqueta">Nombre:</td>
								<td><input type="text" name="nombre" /></td>
							</tr>
							<tr>
								<td class="etiqueta">Información adicional:</td>
								<td><textarea rows="15" cols="50" name="informacion_adicional"></textarea></td>
							</tr>
							<tr>
								<td colspan="2" class="botones">
									<input type="submit" value="Insertar fabricante"/>
									<input type="reset" value="Limpiar formulario"/>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
