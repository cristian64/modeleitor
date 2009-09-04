<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();

	function obtenerRuta ($busqueda, $filtro, $fabricante, $ordenar, $orden, $cantidad, $pagina)
	{
		$ruta = "modelos.php?";
		$parametros = "";

		if ($busqueda != "")
			$parametros = $parametros."busqueda=$busqueda";
		if ($filtro != "")
			$parametros = $parametros."&amp;filtro=$filtro";
		if ($fabricante != "")
			$parametros = $parametros."&amp;fabricante=$fabricante";
		if ($ordenar != "")
			$parametros = $parametros."&amp;ordenar=$ordenar";
		if ($orden != "")
			$parametros = $parametros."&amp;orden=$orden";
		if ($cantidad != "")
			$parametros = $parametros."&amp;cantidad=$cantidad";
		if ($pagina != "")
			$parametros = $parametros."&amp;pagina=$pagina";

		return $ruta.$parametros;
	}

	$busqueda = filtrarCadena($_GET["busqueda"]);
	$filtro = $_GET["filtro"];
	$fabricante = $_GET["fabricante"];
	$ordenar = $_GET["ordenar"];
	if ($_GET["ordenar"] == NULL)
		$ordenar = $_SESSION["ordenar"];
	else
		$_SESSION["ordenar"] = $ordenar;

	$orden = $_GET["orden"];
	if ($_GET["orden"] == NULL)
		$orden = $_SESSION["orden"];
	else
		$_SESSION["orden"] = $orden;

	$cantidad = $_GET["cantidad"];
	if ($cantidad <= 0) $cantidad = 10;
	if ($_GET["cantidad"] == NULL)
		$cantidad = $_SESSION["cantidad"];
	else
		$_SESSION["cantidad"] = $cantidad;

	$pagina = $_GET["pagina"];
	$otrosparametros = obtenerRuta($busqueda, $filtro, $fabricante, $ordenar, $orden, $cantidad, "");

	$miniaturas = $_SESSION["miniaturas"];
	if ($miniaturas == "no")
		$miniaturasOcultas = "style=\"display: none\"";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Modeleitor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="all" type="text/css" href="estilo/estilo.css" />
		<script src="javascript.js" type="text/JavaScript"></script>
		<?php
			if ($miniaturas == "no")
			{
				echo "<script type=\"text/JavaScript\">miniaturas = false;</script>\n";
			}
		?>
	</head>
	<body onload="document.getElementById('busqueda').focus();" onmousemove="registrarCoordenadas(event);">
		<div id="capaimagenraton" style="display: none; position: absolute; z-index: 10;"><img id="imagenraton" src="" alt="" style="border: 1px solid #000;"/></div>
		<div id="contenedor">
			<div id="menu">
				<?php include 'menu.php'; ?>
			</div>

			<div id="contenido">
				<div id="titulo">
					<p>Modelos</p>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">
					<div id="buscador">
						<form action="modelos.php" method="get">
							<div>
								<input id="busqueda" type="text" name="busqueda" class="busqueda" value="<?php echo $busqueda; ?>"/>
								<select name="filtro" class="filtrobusqueda">
									<option value="ambos" <?php if ($filtro == "ambos") echo "selected=\"selected\""; ?>>Buscar en todos los campos</option>
									<option value="modelo" <?php if ($filtro == "modelo") echo "selected=\"selected\""; ?>>Buscar sólo en el código de referencia</option>
									<option value="descripcion" <?php if ($filtro == "descripcion") echo "selected=\"selected\""; ?>>Buscar sólo en la descripción del modelo</option>
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
								<input type="hidden" name="ordenar" value="<?php echo $ordenar; ?>" />
								<input type="hidden" name="orden" value="<?php echo $orden; ?>" />
								<input type="hidden" name="pagina" value="1" />
								<input type="hidden" name="cantidad" value="<?php echo $cantidad; ?>" />
							</div>
						</form>
					</div>
					<?php
						if ($_SESSION["id_catalogo"] != 0)
						{
							$catalogo = ENCatalogo::obtenerPorId($_SESSION["id_catalogo"]);
							if ($catalogo != NULL)
							{
								echo "<div id=\"catalogoseleccionado\">\n";
								echo "Catálogo seleccionado: <strong>".$catalogo->getTitulo()."</strong>";
								echo "</div>\n";
							}
						}
					?>

					<div id="resultados">
						<div style="float: right;"><input type="checkbox" <?php if ($miniaturas == "no") echo "checked=\"checked\""; ?> onclick="permutarMiniaturas();" />&nbsp;Ocultar miniaturas</div>
					<?php


					$modelos = CADModelo::obtenerSeleccion($busqueda, $filtro, $fabricante, $ordenar, $orden, $cantidad, $pagina);

					$cantidadModelos = CADModelo::cantidadSeleccion($busqueda, $filtro, $fabricante);
					$maxpagina = ceil($cantidadModelos / (float)$cantidad);
					if ($pagina < 1 || $pagina > $maxpagina) $pagina = 1;

					if ($cantidadModelos > 0)
						echo "Mostrando ".((($pagina-1)*$cantidad)+1)." - ".min($pagina*$cantidad, $cantidadModelos)." de ".$cantidadModelos." modelos encontrados.";
					else
						echo "No se han encontrado modelos con esos criterios de búsqueda.";

					if ($modelos != NULL)
					{
						if (count($modelos)>0)
						{
							$cabeceras = array();
							$nuevoOrden = ($orden == "ascendente") ? "descendente" : "ascendente";
							$cabeceras["id"] = "<a href=\"".obtenerRuta($busqueda, $filtro, $fabricante, "id", $nuevoOrden, $cantidad, 1)."\">ID</a>";
							$cabeceras["modelo"] = "<a href=\"".obtenerRuta($busqueda, $filtro, $fabricante, "modelo", $nuevoOrden, $cantidad, 1)."\">Código de referencia</a>";
							$cabeceras["descripcion"] = "<a href=\"".obtenerRuta($busqueda, $filtro, $fabricante, "descripcion", $nuevoOrden, $cantidad, 1)."\">Descripción</a>";
							$cabeceras["precio_venta"] = "<a href=\"".obtenerRuta($busqueda, $filtro, $fabricante, "precio_venta", $nuevoOrden, $cantidad, 1)."\">Precio de venta</a>";
							$cabeceras["precio_venta_minorista"] = "<a href=\"".obtenerRuta($busqueda, $filtro, $fabricante, "precio_venta_minorista", $nuevoOrden, $cantidad, 1)."\">Precio de venta</a>";
							$cabeceras["precio_compra"] = "<a href=\"".obtenerRuta($busqueda, $filtro, $fabricante, "precio_compra", $nuevoOrden, $cantidad, 1)."\">Precio de compra</a>";
							$cabeceras["primer_ano"] = "<a href=\"".obtenerRuta($busqueda, $filtro, $fabricante, "primer_ano", $nuevoOrden, $cantidad, 1)."\">Primer año de fabricación</a>";
							$cabeceras["fabricante"] = "<a href=\"".obtenerRuta($busqueda, $filtro, $fabricante, "fabricante", $nuevoOrden, $cantidad, 1)."\">Fabricante</a>";
								
							echo "<table class=\"selectiva\">\n";
							echo "<tr class=\"cabecera\">\n";

							if ($_SESSION["id_catalogo"] != 0)
								echo "<td class=\"columnacatalogo\"></td>\n";

							echo "<td class=\"columnaid ".(($ordenar == "id") ? $nuevoOrden : "")."\">".$cabeceras['id']."</td>";
							echo "<td class=\"columnamodelo ".(($ordenar == "modelo") ? $nuevoOrden : "")."\">".$cabeceras['modelo']."</td>";
							echo "<td class=\"columnadescripcion ".(($ordenar == "descripcion") ? $nuevoOrden : "")."\">".$cabeceras['descripcion']."</td>";
							if ($_SESSION["usuario"] != "nadapoderoso")
								echo "<td class=\"columnaprecio ".(($ordenar == "precio_venta") ? $nuevoOrden : "")."\">".$cabeceras['precio_venta']."</td>";
							if ($_SESSION["usuario"] == "todopoderoso" || $_SESSION["usuario"] == "nadapoderoso")
								echo "<td class=\"columnaprecio ".(($ordenar == "precio_venta_minorista") ? $nuevoOrden : "")."\">".$cabeceras['precio_venta_minorista']."</td>";
							if ($_SESSION["usuario"] == "todopoderoso")
								echo "<td class=\"columnaprecio ".(($ordenar == "precio_compra") ? $nuevoOrden : "")."\">".$cabeceras['precio_compra']."</td>";
							echo "<td class=\"columnaprimerano ".(($ordenar == "primer_ano") ? $nuevoOrden : "")."\">".$cabeceras['primer_ano']."</td>";
							echo "<td class=\"columnafabricante ".(($ordenar == "fabricante") ? $nuevoOrden : "")."\">".$cabeceras['fabricante']."</td>";
							echo "<td class=\"columnafoto\" $miniaturasOcultas>Última foto añadida</td>";
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
										$enlace = $enlace." onmouseout=\"ocultarImagenRaton();\" onmouseover=\"mostrarImagenRaton('".$fotos[0]->getRutaMiniatura2()."')\"";
									}
								}
								$title =  "title=\"Haz clic para ver el modelo en detalle\"";

								$impar = "";
								if ($contador%2 != 0)
									$impar = " impar";

								echo "<tr class=\"fila$impar\">\n";

								if ($_SESSION["id_catalogo"] != 0)
								{
									if ($catalogo->existeModelo($i->getId()))
										echo "<td class=\"columnacatalogo\" title=\"Haz doble clic para cambiar estado del modelo en el catálogo actual\" ondblclick=\"permutarModeloCatalogo(".$i->getId().");\"><img id=\"permutarModeloCatalogo".$i->getId()."\" src=\"estilo/dentro.png\" alt=\"\" /></td>\n";
									else
										echo "<td class=\"columnacatalogo\" title=\"Haz doble clic para cambiar estado del modelo en el catálogo actual\" ondblclick=\"permutarModeloCatalogo(".$i->getId().");\"><img id=\"permutarModeloCatalogo".$i->getId()."\" src=\"estilo/fuera.png\" alt=\"\" /></td>\n";
								}
									
								echo "<td class=\"columnaid\" $enlace $title>".rellenar($i->getId(), "0", "6")."</td>";
								echo "<td class=\"columnamodelo\" $enlace $title>".$i->getModelo()."</td>";
								echo "<td class=\"columnadescripcion\" $enlace $title><span class=\"columnadescripciondiv\">".$i->getDescripcion()."</span></td>";
								if ($_SESSION["usuario"] != "nadapoderoso")
									echo "<td class=\"columnaprecio\" $enlace $title>".str_replace(".", ",", $i->getPrecioVenta())."</td>";
								if ($_SESSION["usuario"] == "todopoderoso" || $_SESSION["usuario"] == "nadapoderoso")
									echo "<td class=\"columnaprecio\" $enlace $title>".str_replace(".", ",", $i->getPrecioVentaMinorista())."</td>";
								if ($_SESSION["usuario"] == "todopoderoso")
									echo "<td class=\"columnaprecio\" $enlace $title>".str_replace(".", ",", $i->getPrecioCompra())."</td>";
								echo "<td class=\"columnaprimerano\" $enlace $title>".$i->getPrimerAno()."</td>";
								echo "<td class=\"columnafabricante\" $enlace>".$i->getFabricante()->getNombre()."</td>";

								if ($fotos != NULL)
								{
									if (count($fotos)>1)
										$cantidadFotos = "(".count($fotos)." fotos)";
									else
										$cantidadFotos = "(1 foto)";
									if (count($fotos)>0)
										echo "<td class=\"columnafoto\" $miniaturasOcultas $enlace><img src=\"".$fotos[0]->getRutaMiniatura()."\" alt=\"Foto nº ".$fotos[0]->getId()."\"/></td>";
									else
										echo "<td class=\"columnafoto\" $miniaturasOcultas $enlace>(sin foto)</td>";
								}
								else
								{
									echo "<td class=\"columnafoto\" $miniaturasOcultas $enlace>(sin foto)</td>";
								}
								
								echo "\n";
								echo "</tr>\n";
								$contador++;
							}
							echo "</table>\n";
						}
					}

					?>
					</div>
					<div id="cantidad" style="float: left;">
						Cantidad de modelos por página:
						<select onchange="location.href='<?php echo obtenerRuta($busqueda, $filtro, $fabricante, $ordenar, $orden, "", "1"); ?>&amp;cantidad='+this.value;">
							<option value="1" <?php if ($cantidad == 1) echo "selected=\"selected\""; ?>>1</option>
							<option value="3" <?php if ($cantidad == 3) echo "selected=\"selected\""; ?>>3</option>
							<option value="5" <?php if ($cantidad == 5) echo "selected=\"selected\""; ?>>5</option>
							<option value="10" <?php if ($cantidad == 10) echo "selected=\"selected\""; ?>>10</option>
							<option value="25" <?php if ($cantidad == 25) echo "selected=\"selected\""; ?>>25</option>
							<option value="50" <?php if ($cantidad == 50) echo "selected=\"selected\""; ?>>50</option>
							<option value="75" <?php if ($cantidad == 75) echo "selected=\"selected\""; ?>>75</option>
							<option value="100" <?php if ($cantidad == 100) echo "selected=\"selected\""; ?>>100</option>
							<option value="500" <?php if ($cantidad == 500) echo "selected=\"selected\""; ?>>500</option>
							<option value="1000" <?php if ($cantidad == 1000) echo "selected=\"selected\""; ?>>1000</option>
							<option value="2000" <?php if ($cantidad == 2000) echo "selected=\"selected\""; ?>>2000</option>
							<option value="5000" <?php if ($cantidad == 5000) echo "selected=\"selected\""; ?>>5000</option>
						</select>
					</div>
					<?php include 'paginacion.php' ?>
				</div>
			</div>
		</div>
	</body>
</html>
