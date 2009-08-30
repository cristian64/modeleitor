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
					<p>Catálogos de <?php echo $_SESSION["usuario"]; ?></p>
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
							$hayCatalogos = true;
							echo "<table class=\"selectiva\">\n";
							echo "<tr class=\"cabecera\">\n";
							echo "<td>Nombre</td>";
							echo "<td>Información adicional</td>";
							echo "</tr>\n";
							$contador = 0;
							foreach ($catalogos as $i)
							{
								//$enlace = "onclick=\"location.href='fabricante.php?id=".$i->getId()."';\"";
								$impar = "";
								if ($contador%2 != 0)
									$impar = "class=\"impar\"";

								echo "<tr id=\"catalogo".$i->getId()."\" $impar title=\"Haz clic para abrir el catálogo\" $enlace>\n";
								echo "<td>".$i->getTitulo()."</td>";
								echo "<td><a href=\"javascript: eliminarCatalogo(".$i->getId().");\"><img src=\"estilo/papelera.png\" alt=\"Eliminar catálogo\" title=\"Eliminar catálogo\" /></a></td>";
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
					<div id="crearcatalogo">
						<form action="operarcatalogo.php" method="post">
							<div>
								<input type="hidden" name="operacion" value="insertar" />
								<input type="text" name="titulo" />
								<input type="submit" value="Crear catálogo" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>