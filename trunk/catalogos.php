<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();
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
					<p>Catálogos de <strong><?php echo $_SESSION["usuario"]; ?></strong></p>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">
					<div id="resultados">
					<?php

					$catalogos = ENCatalogo::obtenerTodos($_SESSION["id_usuario"]);
					if ($catalogos != NULL)
					{
						if (count($catalogos)>0)
						{
							echo "<p>Selecciona uno de los catálogos para poder añadir modelos.</p><p>&nbsp;</p>\n";
							$hayCatalogos = true;
							echo "<table class=\"selectiva\">\n";
							echo "<tr class=\"cabecera\">\n";
							echo "<td class=\"columnatitulo\">Título</td>";
							echo "<td class=\"columnacantidadmodelos\">Cantidad de modelos</td>";
							echo "<td class=\"columnaacciones\">Acciones</td>";
							echo "</tr>\n";
							$contador = 0;
							foreach ($catalogos as $i)
							{
								$enlace = "onclick=\"seleccionarCatalogo(".$i->getId().");\"";
								$impar = "";
								if ($contador%2 != 0)
									$impar = "class=\"impar\"";
									
								$seleccionado = ($i->getId() == $_SESSION["id_catalogo"]) ? "style=\"background-image: url('estilo/seleccionado.png');\"" : "";

								echo "<tr id=\"catalogo".$i->getId()."\" $impar $seleccionado title=\"Haz clic para abrir el catálogo\">\n";
								echo "<td class=\"columnatitulo\" $enlace>".$i->getTitulo()."</td>";
								echo "<td class=\"columnacantidadmodelos\" $enlace>".count($i->obtenerModelos())."</td>";
								echo "<td class=\"columnaacciones\">\n";
								echo "<a href=\"catalogo.php?id=".$i->getId()."\"><img src=\"estilo/lupa.png\" alt=\"Ver catálogo\" title=\"Ver catálogo\" /></a>\n";
								echo "<a href=\"javascript: eliminarCatalogo(".$i->getId().");\"><img src=\"estilo/papelera.png\" alt=\"Eliminar catálogo\" title=\"Eliminar catálogo\" /></a>\n";
								echo "</td>";
								echo "\n";
								echo "<tr>\n";
								$contador++;
							}
							echo "</table>\n";
						}
					}

					if ($catalogos != true)
					{
						echo "<p>El usuario todavía no tiene catálogos.</p>\n";
					}

					?>
					</div>
					<fieldset id="crearcatalogo">
						<legend>Crear un nuevo catálogo</legend>
						<form action="operarcatalogo.php" method="post">
							<div>
								<input type="hidden" name="operacion" value="insertar" />
								Título: <input type="text" name="titulo" />
								<input type="submit" value="Crear catálogo" />
								<div id="checkboxesfabricante">
									<?php
										$fabricantes = ENFabricante::obtenerTodos();
										if ($fabricantes != NULL)
										{
											if (count($fabricantes) > 1)
											{
												echo "<p>Selecciona los fabricantes que quieras incluir automáticamente en el catálogo:<br /></p>\n";
												$hayFabricantes = true;
												foreach ($fabricantes as $i)
												{
													if ($i->getId() != 1)
													{
														echo "<div class=\"checkboxfabricante\">\n";
														echo "<input type=\"checkbox\" name=\"fabricantes[]\" value=\"".$i->getId()."\" />&nbsp;".$i->getNombre()."\n";
														echo "</div>\n";
													}
												}
											}
										}

										if ($hayFabricantes != true)
										{
											
										}
									?>
								</div>
							</div>
						</form>
					</fieldset>
				</div>
			</div>
		</div>
	</body>
</html>