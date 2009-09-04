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
		<script src="javascript.js" type="text/JavaScript"></script>
	</head>
	<body>
		<div id="contenedor">
			<div id="menu">
				<?php include 'menu.php'; ?>
			</div>

			<div id="contenido">
				<div id="titulo">
					<?php
						echo "<p>Catálogo <strong>$titulo</strong></p>";
					?>
				</div>
				<?php include 'mensajes.php'; ?>
				<div id="imprimir">
					<a href="imprimir.php?id=<?php echo $objetoCatalogo->getId(); ?>&amp;formato=1x1"><img src="estilo/formato1x1.png" alt="1x1 (un modelo por página)" title="1x1 (1 modelo por página)" /></a>
					<a href="imprimir.php?id=<?php echo $objetoCatalogo->getId(); ?>&amp;formato=1x3"><img src="estilo/formato1x3.png" alt="1x3 (3 modelos por página)" title="1x3 (3 modelos por página)" /></a>
					<a href="imprimir.php?id=<?php echo $objetoCatalogo->getId(); ?>&amp;formato=2x4"><img src="estilo/formato2x4.png" alt="2x4 (8 modelos por página)" title="2x4 (8 modelos por página)" /></a>
					<a href="imprimir.php?id=<?php echo $objetoCatalogo->getId(); ?>&amp;formato=4x3"><img src="estilo/formato4x3.png" alt="4x3 (12 modelos por página)" title="4x3 (12 modelos por página)" /></a>
					<a href="imprimir.php?id=<?php echo $objetoCatalogo->getId(); ?>&amp;formato=4x4"><img src="estilo/formato4x4.png" alt="4x4 (16 modelos por página)" title="4x4 (16 modelos por página)"/></a>
					<a href="imprimir.php?id=<?php echo $objetoCatalogo->getId(); ?>&amp;formato=5x7"><img src="estilo/formato5x7.png" alt="5x7 (35 modelos por página)" title="5x7 (35 modelos por página)" /></a>
				</div>
				<div id="panel">

					<?php
						$modelos = $objetoCatalogo->obtenerModelos();
						if ($modelos != NULL)
						{
							if (count($modelos) > 0)
							{
								echo "<table id=\"mosaico\"><tr>\n";
								$hayModelos = true;
								$contador = 0;
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
