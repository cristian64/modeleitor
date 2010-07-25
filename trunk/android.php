<?php
	session_start();
	require_once 'minilibreria.php';

	/*echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
	if (is_numeric($_GET["busqueda"]))
	{
		$encontrados = ENModelo::obtenerSeleccion($_GET["busqueda"], "modelo", "", "id", "descendente", 30, 1);
		echo "<modelos>\n";
		foreach ($encontrados as $i)
		{
			echo "\t<modelo>\n";
			echo "\t\t<id>".$i->getId()."</id>\n";
			echo "\t\t<referencia>".$i->getModelo()."</referencia>\n";
			echo "\t\t<descripcion>".$i->getDescripcion()."</descripcion>\n";
			echo "\t\t<precioventa>".$i->getPrecioVenta()."</precioventa>\n";
			echo "\t\t<precioventaminorista>".$i->getPrecioVentaMinorista()."</precioventaminorista>\n";
			echo "\t</modelo>\n";
		}
		echo "</modelos>\n";
	}*/
	
	if (is_numeric($_GET["busqueda"]))
	{
		$encontrados = ENModelo::obtenerSeleccion($_GET["busqueda"], "modelo", "", "id", "descendente", 30, 1);
		foreach ($encontrados as $i)
		{
			$fotos = ENFoto::obtenerTodos($i->getId());
			$idFoto = "0";
			if ($fotos != NULL)
				if (count($fotos) > 0)
					$idFoto = $fotos[0]->getId();
			$referencia = str_replace("\n", " ", str_replace("\r\n", " ", $i->getModelo()));
			$descripcion = str_replace("\n", " ", str_replace("\r\n", " ", $i->getDescripcion()));
			$precioventa = $i->getPrecioVenta();
			$precioventaminorista = $i->getPrecioVentaMinorista();

			echo $idFoto."\n".$referencia."\n".$descripcion."\n".$precioventa."\n".$precioventaminorista."\n";
		}
	}
?>
