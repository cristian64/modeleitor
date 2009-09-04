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
					<p>Fabricantes</p>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="panel">
					<div id="resultados">
					<?php

					$fabricantes = ENFabricante::obtenerTodos();

					if ($fabricantes != NULL)
					{
						if (count($fabricantes)>0)
						{
							echo "<table class=\"selectiva\">\n";
							echo "<tr class=\"cabecera\">\n";
							echo "<td class=\"columnanombre\">Nombre</td>";
							echo "<td class=\"columnatelefono\">Teléfono</td>";
							echo "<td class=\"columnainformacionadicional\">Información adicional</td>";
							echo "</tr>\n";
							$contador = 0;
							foreach ($fabricantes as $i)
							{
								$enlace = "onclick=\"location.href='fabricante.php?id=".$i->getId()."';\"";
								$impar = "";
								if ($contador%2 != 0)
									$impar = "class=\"impar\"";

								echo "<tr $impar title=\"Haz clic para ver el fabricante en detalle\" $enlace>\n";
								echo "<td class=\"columnanombre\">".$i->getNombre()."</td>";
								echo "<td class=\"columnatelefono\"><span class=\"columnatelefonodiv\">".$i->getTelefono()."</span></td>";
								echo "<td class=\"columnainformacionadicional\"><span class=\"columnainformacionadicionaldiv\">".$i->getInformacionAdicional()."</span></td>";
								echo "\n";
								echo "</tr>\n";
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