<?php

require_once 'resize.php';

echo "Hay que modificar el valor del contador del bucle para que funcione.";
for ($i = 0; $i < 0; $i++)
{
	$rutaFoto = "imagenes/".$i.".jpg";
	$rutaMiniatura5 = "imagenes/".$i."m5.jpg";
	$miniatura=new thumbnail($rutaFoto);
	$miniatura->size_auto(80);
	$miniatura->jpeg_quality(100);
	echo $rutaFoto."<br/>".$rutaMiniatura5."<br/><br/>";
	$miniatura->save($rutaMiniatura5);
}

?>
