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
							echo "<td>Nombre</td>";
							echo "<td>Información adicional</td>";
							echo "</tr>\n";
							$contador = 0;
							foreach ($fabricantes as $i)
							{
								$enlace = "onclick=\"location.href='fabricante.php?id=".$i->getId()."';\"";
								$impar = "";
								if ($contador%2 != 0)
									$impar = "class=\"impar\"";

								echo "<tr $impar title=\"Haz clic para ver el fabricante en detalle.\" $enlace>\n";
								echo "<td>".$i->getNombre()."</td>";
								echo "<td>".$i->getInformacionAdicional()."</td>";
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