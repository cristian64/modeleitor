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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>Modeleitor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" media="all" type="text/css" href="estilo/estilo.css" />
	</head>
	<body onload="document.getElementById('busqueda').focus();">
		<div id="contenedor">
			<div id="menu">
				<?php include 'menu.html'; ?>
			</div>

			<div id="contenido">
				<div id="titulo">
					<p>Añadir un nuevo modelo</p>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">
					<form action="operarmodelo.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="operacion" value="insertar" />
						<table class="insertiva">
							<tr>
								<td class="etiqueta">Código de referencia:</td>
								<td><input type="text" name="modelo" /></td>
							</tr>
							<tr>
								<td class="etiqueta">Descripción:</td>
								<td><textarea rows="5" cols="50" name="descripcion"></textarea></td>
							</tr>
							<tr>
								<td class="etiqueta">Precio de venta mayorista:</td>
								<td><input type="text" name="precio_venta" /></td>
							</tr>
							<tr>
								<td class="etiqueta">Precio de venta:</td>
								<td><input type="text" name="precio_venta_minorista" /></td>
							</tr>
							<tr>
								<td class="etiqueta">Precio de compra:</td>
								<td><input type="text" name="precio_compra" /></td>
							</tr>
							<tr>
								<td class="etiqueta">Primer año de fabricación:</td>
								<td><input type="text" name="primer_ano" /></td>
							</tr>
							<tr>
								<td class="etiqueta">Fabricante:</td>
								<td>
									<select name="fabricante" size="6">
										<option value="1" selected="selected">Desconocido</option>
										<?php
											$fabricantes = ENFabricante::obtenerTodos();
											if ($fabricantes != NULL)
											{
												if (count($fabricantes) > 0)
												{
													foreach ($fabricantes as $i)
													{
														echo "<option value=\"".$i->getId()."\">".$i->getNombre()."</option>\n";
													}
												}
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="etiqueta">Foto (JPEG):</td>
								<td><input type="file" name="foto" /></td>
							</tr>
							<tr>
								<td colspan="2" class="botones">
									<input type="submit" value="Insertar modelo"/>
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
