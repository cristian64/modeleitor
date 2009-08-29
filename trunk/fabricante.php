<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();

	$operacion = "insertar";
	$textoSubmit = "Insertar fabricante";
	$textoReset = "Limpiar formulario";
	$id = "";
	$nombre = "";
	$informacion_adicional = "";
	$soloLectura = "";
	$deshabilitado = "";
	if ($_GET["id"] != NULL)
	{
		$objetoFabricante = ENFabricante::obtenerPorId(filtrarCadena($_GET["id"]));
		if ($objetoFabricante != NULL)
		{
			$operacion = "editar";
			$textoSubmit = "Guardar cambios";
			$textoReset = "Deshacer cambios";
			$soloLectura = " readonly=\"readonly\" ";
			$deshabilitado = " disabled=\"disabled\"";

			$id = $objetoFabricante->getId();
			$nombre = $objetoFabricante->getNombre();
			$informacion_adicional = $objetoFabricante->getInformacionAdicional();
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
							echo "<p>Añadir un nuevo fabricante</p>";
						else
							echo "<p>$nombre</p>";
					?>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">

					<?php
						if ($operacion == "editar")
						{
					?>
						<form id="formularioeliminarfabricante" action="operarfabricante.php" method="post" onsubmit="return confirmarEliminarFabricante();">
							<div style="text-align: center; margin-bottom: 10px;">
								<input id="desbloquear" type="button" value="Desbloquear formulario" onclick="desbloquearFormularioFabricante()" />
								<input id="bloquear" type="button" value="Bloquear formulario" onclick="bloquearFormularioFabricante()" style="display: none;"/>
								<input type="hidden" name="id" value="<?php echo $id; ?>" />
								<input type="hidden" name="operacion" value="borrar" />
								<input id="botoneliminar" type="submit" value="Eliminar fabricante" disabled="disabled" />
							</div>
						</form>
					<?php
						}
					?>

					<form id="formularioeditarfabricante" action="operarfabricante.php" method="post" onsubmit="return validarFabricante(this);">
						<table class="insertiva">
							<tr>
								<td class="etiqueta">Nombre:</td>
								<td>
									<input type="text" name="nombre" autocomplete="off" <?php echo $soloLectura; ?> value="<?php echo $nombre; ?>"/>
									<input type="hidden" name="operacion" value="<?php echo $operacion; ?>" />
									<?php if ($operacion == "editar") echo "<input type=\"hidden\" name=\"id\" value=\"$id\" />\n"; ?>
								</td>
							</tr>
							<tr>
								<td class="etiqueta">Información adicional:</td>
								<td><textarea rows="15" cols="50" name="informacion_adicional" autocomplete="off" <?php echo $soloLectura; ?>><?php echo $informacion_adicional; ?></textarea></td>
							</tr>
							<tr>
								<td colspan="2" class="botones">
									<input id="botonsubmit" type="submit" value="<?php echo $textoSubmit; ?>" <?php echo $deshabilitado; ?>/>
									<input id="botonreset" type="reset" value="<?php echo $textoReset; ?>" <?php echo $deshabilitado; ?>/>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
