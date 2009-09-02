<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();

	$id = "";
	$titulo = "";

	$formato = $_GET["formato"];
	if ($formato == NULL)
		$formato = "";
	switch ($formato)
	{
		case "1x1":
			$columnas = 1;
			$maxWidth = "100%";
			$maxHeight = "100%";
			$orientacion = "horizontal";
			break;
		
		case "1x3":
			$columnas = 1;
			$maxWidth = "380px";
			$maxHeight = "380px";
			$orientacion = "vertical";
			break;
		
		case "2x4":
			$columnas = 2;
			$maxWidth = "250px";
			$maxHeight = "250px";
			$orientacion = "vertical";
			break;

		case "4x3":
			$columnas = 4;
			$maxWidth = "250px";
			$maxHeight = "250px";
			$orientacion = "horizontal";
			break;

		case "4x4":
			$columnas = 4;
			$maxWidth = "170px";
			$maxHeight = "170px";
			$orientacion = "horizontal";
			break;
		
		case "5x7":
			$columnas = 5;
			$maxWidth = "170px";
			$maxHeight = "130px";
			$orientacion = "vertical";
			break;

		default:
			$columnas = 4;
			$maxWidth = "250px";
			$maxHeight = "250px";
			$orientacion = "vertical";
			break;
	}

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
		header ("location: catalogos.php?error=No se puede imprimir el catálogo seleccionado.");
		exit();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Modeleitor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="all" type="text/css" href="estilo/impresora.css" />
		<script src="javascript.js" type="text/JavaScript"></script>
		<style type="text/css" media="all">
			img
			{
				max-width: <?php echo $maxWidth; ?>;
				max-height: <?php echo $maxHeight; ?>;
			}
		</style>
		<style type="text/css" media="print">
			#orientacion { display: none; }
		</style>
		<style type="text/css" media="screen">
			#orientacion
			{
				font-size: 11pt;
				position: fixed;
				top: 15px;
				right: 15px;
				background: #eee url('estilo/infoimpresora.png') no-repeat left center;
				color: #666;
				padding: 15px;
				padding-left: 65px;
				border: 1px solid #aaa;
				border-radius: 10px;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				-ms-border-radius: 10px;
				-khtml-border-radius: 10px;
			}
			#panel
			{
				margin-top: 85px;
			}

			strong
			{
				color: #000;
			}
		</style>
	</head>
	<body>
		<div id="contenedor">
			<div id="contenido">
				<div id="orientacion">
					<p>Según el formato seleccionado, el catálogo debe imprimirse con una orientación <strong><?php echo $orientacion; ?></strong>.</p>
					<p>Asegúrate de que tu impresora tiene la orientación correcta.</p>
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
								foreach ($modelos as $i)
								{
									$rutaMiniatura = "";
									$fotos = ENFoto::obtenerTodos($i->getId());
									if ($fotos != NULL)
									{
										if (count($fotos) > 0)
										{
											$rutaMiniatura = $fotos[0]->getRutaFoto();
										}
									}

									$nuevaFila = ($contador % $columnas == 0 && $contador >= $columnas);
									if ($nuevaFila)
										echo "</tr><tr>\n";

										echo "<td class=\"mosaicomodelo\"><img src=\"".$rutaMiniatura."\" alt=\"Modelo ".$i->getModelo()."\" /><div class=\"informacion\"><p class=\"modelo\">Mod. ".$i->getModelo()."</p><p class=\"precio\">".$i->getPrecioVenta()." €</p><p class=\"clearboth\" /></div></td>\n";

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
