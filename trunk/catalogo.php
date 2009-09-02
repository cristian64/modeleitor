<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();

	$id = "";
	$titulo = "";
	
	$columnasImpresora = 2;
	$columnas = 6;

	$hayCatalogo = false;
	if ($_GET["id"] != NULL)
	{
		$objetoCatalogo = ENCatalogo::obtenerPorId(filtrarCadena($_GET["id"]));
		if ($objetoCatalogo != NULL)
		{
			$id = $objetoCatalogo->getId();
			$_SESSION["id_catalogo"] = $id;
			$titulo = $objetoCatalogo->getTitulo();
			if ($objetoCatalogo->getIdUsuario() == $_SESSION["id_usuario"])
				$hayCatalogo = true;
		}
	}

	if ($hayCatalogo != true)
	{
		header ("location: catalogos.php?error=No se puede abrir el catálogo seleccionado.");
		exit();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Modeleitor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="screen" type="text/css" href="estilo/estilo.css" />
		<link rel="stylesheet" media="print" type="text/css" href="estilo/impresora.css" />
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
						echo "<p>Catálogo <strong>$titulo</strong></p>";
					?>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="imprimir">
					<a href="javascript: print()"><img src="estilo/impresora.png" alt="Imprimir" /></a>
					<a href="generarpdf.php?id=<?php echo $id; ?>"><img src="estilo/pdf.png" alt="Generar documento PDF" /></a>
					<div id="imprimirAviso">Ha quitado un modelo del catálogo. Debe recargar la página antes de imprimir.</div>
				</div>
				<div id="panel">

					<?php
						$modelos = $objetoCatalogo->obtenerModelos();
						if ($modelos != NULL)
						{
							if (count($modelos) > 0)
							{
								echo "<table id=\"mosaicoImpresora\"><tr>\n";
								$hayModelos = true;
								$contador = 0;
								//$modelos = array_merge($modelos, $modelos);
								//$modelos = array_merge($modelos, $modelos);
								foreach ($modelos as $i)
								{
									$rutaMiniatura = "";
									$fotos = ENFoto::obtenerTodos($i->getId());
									if ($fotos != NULL)
									{
										if (count($fotos) > 0)
											$rutaMiniatura = $fotos[0]->getRutaMiniatura2();
									}

									$nuevaFila = ($contador % $columnasImpresora == 0 && $contador >= $columnasImpresora);
									if ($nuevaFila)
										echo "</tr><tr>\n";
										
										echo "<td class=\"mosaicomodelo\"><img src=\"".$rutaMiniatura."\" alt=\"Modelo ".$i->getModelo()."\" /><div class=\"informacion\"><p class=\"modelo\">Mod. ".$i->getModelo()."</p><p class=\"precio\">".$i->getPrecioVenta()." €</p></div></td>\n";

									$contador++;
								}
								echo "</tr></table>\n";


								echo "<table id=\"mosaico\"><tr>\n";
								$hayModelos = true;
								$contador = 0;
								//$modelos = array_merge($modelos, $modelos);
								//$modelos = array_merge($modelos, $modelos);
								foreach ($modelos as $i)
								{
									$rutaMiniatura = "";
									$fotos = ENFoto::obtenerTodos($i->getId());
									if ($fotos != NULL)
									{
										if (count($fotos) > 0)
											$rutaMiniatura = $fotos[0]->getRutaMiniatura();
									}

									$nuevaFila = ($contador % $columnas == 0 && $contador >= $columnas);
									if ($nuevaFila)
										echo "</tr><tr>\n";

										echo "<td class=\"mosaicomodelo\"><div class=\"informacion\"><a href=\"modelo.php?id=".$i->getId()."\" title=\"Haz clic para ver el modelo en detalle\"><img src=\"".$rutaMiniatura."\" alt=\"Modelo ".$i->getModelo()."\" /></a><p class=\"modelo\"><img id=\"permutarModeloCatalogo".$i->getId()."\" ondblclick=\"permutarModeloCatalogo(".$i->getId().");\" src=\"estilo/dentro.png\" alt=\"\" title=\"Haz doble clic para cambiar estado del modelo en el catálogo actual\" /> Mod. ".$i->getModelo()."</p></div></td>\n";

									$contador++;
								}
								echo "</tr></table>\n";
							}
						}
					?>
					
				</div>
			</div>
		</div>
	</body>
</html>
