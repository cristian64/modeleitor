<?php
	require_once 'minilibreria.php';

	$busqueda = filtrarCadena($_GET["busqueda"]);
	$filtro = $_GET["filtro"];
	$fabricante = $_GET["fabricante"];
	$ordenar = $_GET["ordenar"];
	$orden = $_GET["orden"];
	$cantidad = $_GET["cantidad"];
	$pagina = $_GET["pagina"];
	$maxpagina = 100;
	$otrosparametros = "index.php?";
	$miniaturas = $_GET["miniaturas"];
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
		<div id="capaimagenraton" style="display: none; position: absolute; "><img id="imagenraton" src="" alt="" style="border: 1px solid #000;"/></div>
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
							<div>
								<input id="busqueda" type="text" name="busqueda" class="busqueda" value="<?php echo $busqueda; ?>"/>
								<select name="filtro" class="filtrobusqueda">
									<option value="modelo" <?php if ($filtro == "modelo") echo "selected=\"selected\""; ?>>Buscar sólo en el código de referencia</option>
									<option value="descripcion" <?php if ($filtro == "descripcion") echo "selected=\"selected\""; ?>>Buscar sólo en la descripción del modelo</option>
									<option value="ambos" <?php if ($filtro == "ambos") echo "selected=\"selected\""; ?>>Buscar en todos los campos</option>
								</select>
								<select name="fabricante" class="filtrobusqueda">
									<option value="cualquiera">Cualquier fabricante</option>
									<?php
										$fabricantes = ENFabricante::obtenerTodos();
										if ($fabricantes != NULL)
											foreach ($fabricantes as $i)
											{
												if ($i->getId() == $fabricante)
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
							</div>
						</form>
					</div>
					<div id="resultados">
						<div style="text-align: right;"><input type="checkbox" onclick="permutarMiniaturas();" />&nbsp;Ocultar miniaturas</div>
					<?php

					$modelos = ENModelo::obtenerTodos();

					if ($modelos != NULL)
					{
						if (count($modelos)>0)
						{
							echo "<table class=\"selectiva\">\n";
							echo "<tr class=\"cabecera\">\n";
							echo "<td class=\"columnaid\"><a href=\"index.php?ordenar=id&orden=$orden\">ID<img src=\"estilo/ascendente.png\"/></a></td>";
							echo "<td class=\"columnamodelo\">Código de referencia</td>";
							echo "<td class=\"columnadescripcion\">Descripción</td>";
							echo "<td class=\"columnaprecio\">Precio de venta</td>";
							echo "<td class=\"columnaprecio\">Precio de venta (minorista)</td>";
							echo "<td class=\"columnaprecio\">Precio de compra</td>";
							echo "<td class=\"columnaprimerano\">Primer año de fabricación</td>";
							echo "<td class=\"columnafabricante\">Fabricante</td>";
							if ($miniaturas != "no")
								echo "<td class=\"columnafoto\">Última foto añadida</td>";
							echo "</tr>\n";
							$contador = 0;
							foreach ($modelos as $i)
							{
								$enlace = "onclick=\"location.href='modelo.php?id=".$i->getId()."';\"";
									
								$fotos = $i->getFotos();
								if ($fotos != NULL)
								{
									if (count($fotos)>0)
									{
										$enlace = $enlace." onmouseout=\"ocultarImagenRaton();\" onmouseover=\"mostrarImagenRaton('".$fotos[0]->getRutaMiniatura()."')\"";
									}
								}

								$impar = "";
								if ($contador%2 != 0)
									$impar = " impar";

								echo "<tr class=\"fila$impar\" title=\"Haz clic para ver el modelo en detalle\" $enlace>\n";
								echo "<td class=\"columnaid\">".rellenar($i->getId(), "0", "6")."</td>";
								echo "<td class=\"columnamodelo\">".$i->getModelo()."</td>";
								echo "<td class=\"columnadescripcion\">".$i->getDescripcion()."</td>";
								echo "<td class=\"columnaprecio\">".str_replace(".", ",", $i->getPrecioVenta())."</td>";
								echo "<td class=\"columnaprecio\">".str_replace(".", ",", $i->getPrecioVentaMinorista())."</td>";
								echo "<td class=\"columnaprecio\">".str_replace(".", ",", $i->getPrecioCompra())."</td>";
								echo "<td class=\"columnaprimerano\">".$i->getPrimerAno()."</td>";
								echo "<td class=\"columnafabricante\">".$i->getFabricante()->getNombre()."</td>";

								if ($fotos != NULL)
								{
									if (count($fotos)>1)
										$cantidadFotos = "(".count($fotos)." fotos)";
									else
										$cantidadFotos = "(1 foto)";
									if (count($fotos)>0)
										echo "<td class=\"columnafoto\"><img src=\"".$fotos[0]->getRutaMiniatura()."\" alt=\"Foto nº ".$fotos[0]->getId()."\"/></td>";
									else
										echo "<td class=\"columnafoto\">(sin foto)</td>";
								}
								else
								{
									echo "<td class=\"columnafoto\">(sin foto)</td>";
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
					<?php include 'paginacion.php' ?>
				</div>
			</div>
		</div>
	</body>
</html>
