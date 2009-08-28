<?php
	require_once 'minilibreria.php';
	
	$operacion = "insertar";
	$textoSubmit = "Insertar modelo";
	$textoReset = "Limpiar formulario";
	$id = "";
	$modelo = "";
	$descripcion = "";
	$precio_venta = "0";
	$precio_venta_minorista = "0";
	$precio_compra = "0";
	$id_fabricante = "";
	$primer_ano = "2000";
	$soloLectura = "";
	$deshabilitado = "";
	if ($_GET["id"] != NULL)
	{
		$objetoModelo = ENModelo::obtenerPorId(filtrarCadena($_GET["id"]));
		if ($objetoModelo != NULL)
		{
			$operacion = "editar";
			$textoSubmit = "Guardar cambios";
			$textoReset = "Deshacer cambios";
			$soloLectura = " readonly=\"readonly\" ";
			$deshabilitado = " disabled=\"disabled\"";

			$id = $objetoModelo->getId();
			$modelo = $objetoModelo->getModelo();
			$descripcion = $objetoModelo->getDescripcion();
			$precio_venta = str_replace(".", ",", $objetoModelo->getPrecioVenta());
			$precio_venta_minorista = str_replace(".", ",", $objetoModelo->getPrecioVentaMinorista());
			$precio_compra = str_replace(".", ",", $objetoModelo->getPrecioCompra());
			if ($objetoModelo->getFabricante() != NULL)
				$id_fabricante = $objetoModelo->getFabricante()->getId();
			$primer_ano = $objetoModelo->getPrimerAno();
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Modeleitor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="all" type="text/css" href="estilo/estilo.css" />
		<script src="javascript.js" type="text/JavaScript"></script>
	</head>
	<body>
		<div id="contenedor">
			<div id="menu">
				<?php include 'menu.html'; ?>
			</div>

			<div id="contenido">
				<div id="titulo">
					<?php
						if ($operacion == "insertar")
							echo "<p>Añadir un nuevo modelo</p>";
						else
							echo "<p>Modelo $modelo</p>";
					?>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">

					<table style="width: 100%;">
						<tr>
							<td style="vertical-align: top;">
								<!-- Parte del formulario de edición -->
								<?php
									if ($operacion == "editar")
									{
								?>
									<form id="formularioeliminarmodelo" action="operarmodelo.php" method="post" onsubmit="return confirmarEliminarModelo();">
										<div style="text-align: center; margin-bottom: 10px;">
											<input id="desbloquear" type="button" value="Desbloquear formulario" onclick="desbloquearFormularioModelo()" />
											<input id="bloquear" type="button" value="Bloquear formulario" onclick="bloquearFormularioModelo()" style="display: none;"/>
											<input type="hidden" name="id" value="<?php echo $id; ?>" />
											<input type="hidden" name="operacion" value="borrar" />
											<input id="botoneliminar" type="submit" value="Eliminar modelo" disabled="disabled" />
										</div>
									</form>
								<?php
									}
								?>

								<form id="formularioeditarmodelo" action="operarmodelo.php" method="post" onsubmit="return validarModelo(this);" <?php if ($operacion == "insertar") echo "enctype=\"multipart/form-data\"" ?>>
									<table class="insertiva">
										<tr>
											<td class="etiqueta">Código de referencia:</td>
											<td>
												<input type="text" name="modelo" autocomplete="off" value="<?php echo $modelo; ?>" <?php echo $soloLectura; ?> />
												<input type="hidden" name="operacion" value="<?php echo $operacion; ?>" />
												<?php if ($operacion == "editar") echo "<input type=\"hidden\" name=\"id\" value=\"$id\" />\n"; ?>
											</td>
										</tr>
										<tr>
											<td class="etiqueta">Descripción:</td>
											<td><textarea rows="5" cols="50" name="descripcion" autocomplete="off" <?php echo $soloLectura; ?> ><?php echo $descripcion; ?></textarea></td>
										</tr>
										<tr>
											<td class="etiqueta">Precio de venta:</td>
											<td><input type="text" name="precio_venta" autocomplete="off" value="<?php echo $precio_venta; ?>" <?php echo $soloLectura; ?> /></td>
										</tr>
										<tr>
											<td class="etiqueta">Precio de venta (minorista):</td>
											<td><input type="text" name="precio_venta_minorista" autocomplete="off" value="<?php echo $precio_venta_minorista; ?>" <?php echo $soloLectura; ?> /></td>
										</tr>
										<tr>
											<td class="etiqueta">Precio de compra:</td>
											<td><input type="text" name="precio_compra" autocomplete="off" value="<?php echo $precio_compra; ?>" <?php echo $soloLectura; ?> /></td>
										</tr>
										<tr>
											<td class="etiqueta">Primer año de fabricación:</td>
											<td><input type="text" name="primer_ano" autocomplete="off" value="<?php echo $primer_ano; ?>" <?php echo $soloLectura; ?> /></td>
										</tr>
										<tr>
											<td class="etiqueta">Fabricante:</td>
											<td>
												<?php
													$fabricantes = ENFabricante::obtenerTodos();
													if ($fabricantes != NULL)
													{
														if (count($fabricantes) > 0)
														{
															echo "<select name=\"fabricante\" $deshabilitado >\n";
															foreach ($fabricantes as $i)
															{
																if ($i->getId() == $id_fabricante)
																	echo "<option value=\"".$i->getId()."\" selected=\"selected\">".$i->getNombre()."</option>\n";
																else
																	echo "<option value=\"".$i->getId()."\">".$i->getNombre()."</option>\n";
															}
															echo "</select>\n";
														}
													}
												?>
											</td>
										</tr>
										<?php
											if ($operacion == "insertar")
											{
										?>
										<tr>
											<td class="etiqueta">Foto (JPEG):</td>
											<td><input type="file" name="foto" /></td>
										</tr>
										<?php
											}
										?>
										<tr>
											<td colspan="2" class="botones">
												<input id="botonsubmit" type="submit" value="<?php echo $textoSubmit; ?>" <?php echo $deshabilitado; ?> />
												<input id="botonreset" type="reset" value="<?php echo $textoReset; ?>" <?php echo $deshabilitado; ?> />
											</td>
										</tr>
									</table>
								</form>
							</td>

							<!-- Parte de las fotos -->
							<?php if ($operacion == "editar") { ?>
							<td style="width: 50%; vertical-align: top; padding: 30px">
								<div id="fotos">
									<fieldset>
										<legend>Fotos</legend>
										<?php
											$fotos = ENFoto::obtenerTodos($id);
											if ($fotos != NULL)
											{
												foreach ($fotos as $i)
												{
													echo "<div class=\"foto\" id=\"foto".$i->getId()."\">\n";
													echo "<a class=\"miniatura\" href=\"".$i->getRutaFoto()."\"><img src=\"".$i->getRutaMiniatura()."\" alt=\"".$i->getId()."\" title=\"Haz click para ampliar la foto\" /></a>\n";
													echo "<a class=\"papelera\" href=\"javascript: eliminarFoto(".$i->getId().")\"><img title=\"¿Eliminar foto?\" src=\"estilo/papelera.png\" alt=\"Eliminar foto\" /></a>\n";
													echo "</div>\n";
												}
											}
										?>
									</fieldset>
									<form action="operarmodelo.php" method="post" enctype="multipart/form-data">
										<div style="padding: 20px;">
											Insertar una nueva foto (JPEG):
											<input type="hidden" value="insertarfoto" name="operacion" />
											<input type="hidden" value="<?php echo $id; ?>" name="id" />
											<input type="file" name="foto" />
											<input type="submit" value="Subir foto" />
										</div>
									</form>
								</div>
							</td>
							<?php } ?>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
