<?php
	require_once 'minilibreria.php';

	$busqueda = filtrarCadena($_GET["busqueda"]);
	$filtro = $_GET["filtro"];
	$id_fabricante = $_GET["id_fabricante"];
	$ordenar = $_GET["ordenar"];
	$orden = $_GET["orden"];
	$cantidad = $_GET["cantidad"];
	$pagina = $_GET["pagina"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Modeleitor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="all" type="text/css" href="estilo/estilo.css" />
		<script src="javascript.js" type="text/JavaScript"></script>
	</head>
	<body onload="document.getElementById('busqueda').focus();" onmousemove="registrarCoordenadas(event);">
		<div id="capaimagenraton" style="display: none; position: absolute; "><img id="imagenraton" src="" alt="" /></div>
		<div id="contenedor">
			<div id="menu">
				<?php include 'menu.html'; ?>
			</div>

			<div id="contenido">
				<div id="titulo">
					<p>Modelos</p>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">
					<div id="buscador">
						<form action="index.php" method="get">
							<input id="busqueda" type="text" name="busqueda" class="busqueda" value="<?php echo $busqueda; ?>"/>
							<select name="filtro" class="filtrobusqueda">
								<option value="modelo" <?php if ($filtro == "modelo") echo "selected=\"selected\""; ?>>Buscar sólo en el código de referencia</option>
								<option value="descripcion" <?php if ($filtro == "descripcion") echo "selected=\"selected\""; ?>>Buscar sólo en la descripción del modelo</option>
								<option value="ambos" <?php if ($filtro == "ambos") echo "selected=\"selected\""; ?>>Buscar en todos los campos</option>
							</select>
							<select name="id_fabricante" class="filtrobusqueda">
								<option value="-1">Cualquier fabricante</option>
								<?php
									$fabricantes = ENFabricante::obtenerTodos();
									if ($fabricantes != NULL)
										foreach ($fabricantes as $i)
										{
											if ($i->getId() == $id_fabricante)
											{
												echo "<option value=\"".$i->getId()."\" selected=\"selected\">".$i->getNombre()."</option>\n";
											}
											else
											{
												echo "<option value=\"".$i->getId()."\">".$i->getNombre()."</option>\n";
											}
										}
								?>
							</select>
							<input type="submit" value="Buscar" class="botonbuscar" />
						</form>
					</div>
					<div id="resultados">
					<?php

					$modelos = ENModelo::obtenerTodos();

					if ($modelos != NULL)
					{
						if (count($modelos)>0)
						{
							echo "<table class=\"selectiva\">\n";
							echo "<tr class=\"cabecera\">\n";
							echo "<td><a href=\"index.php?ordenar=id&orden=$orden\">ID<img src=\"estilo/ascendente.png\"/></a></td>";
							echo "<td>Código de referencia</td>";
							echo "<td>Descripción</td>";
							echo "<td>Precio de venta</td>";
							echo "<td>Precio de venta (minorista)</td>";
							echo "<td>Precio de compra</td>";
							echo "<td>Primer año de fabricación</td>";
							echo "<td>Fabricante</td>";
							echo "<td>Cantidad de fotos</td>";
							echo "<td>Última foto añadida</td>";
							echo "</tr>\n";
							$contador = 0;
							foreach ($modelos as $i)
							{
								$enlace = "onclick=\"location.href='modelo.php?id=".$i->getId()."';\"";
								$impar = "";
								if ($contador%2 != 0)
									$impar = "class=\"impar\"";

								echo "<tr $impar title=\"Haz clic para ver el modelo en detalle.\" $enlace>\n";
								echo "<td>".rellenar($i->getId(), "0", "6")."</td>";
								echo "<td>".$i->getModelo()."</td>";
								echo "<td>".$i->getDescripcion()."</td>";
								echo "<td>".$i->getPrecioVenta()."</td>";
								echo "<td>".$i->getPrecioVentaMinorista()."</td>";
								echo "<td>".$i->getPrecioCompra()."</td>";
								echo "<td>".$i->getPrimerAno()."</td>";
								echo "<td>".$i->getFabricante()->getNombre()."</td>";
								$fotos = $i->getFotos();
								if ($fotos != NULL)
								{
									echo "<td>".count($fotos)."</td>";
									if (count($fotos)>0)
										//onmouseout=\"ocultarImagenRaton();\" onmouseover=\"mostrarImagenRaton('".$fotos[0]->getRutaFoto()."')\" 
										echo "<td><img src=\"".$fotos[0]->getRutaMiniatura()."\" alt=\"Foto nº ".$fotos[0]->getId()."\"/></td>";
									else
										echo "<td></td>";
								}
								else
								{
									echo "<td>0</td>";
									echo "<td></td>";
								}
								echo "\n";
								echo "<tr>\n";
								$contador++;
							}
							echo "</table>\n";
						}
					}

					?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
