<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();

	// Clase pdf
	include ('pdf/class.ezpdf.php');
	$pdf = new Cezpdf();
	$pdf->selectFont('pdf/Helvetica.afm');

	// Datos de la empresa
	/*$opciones['left']=440;
	$pdf->ezText('TPU Levante, S.L.',7,$opciones);
	$pdf->ezText('Ctra. Aspe-Alicante, km. 0,4',7,$opciones);
	$pdf->ezText('03680 - Aspe',7,$opciones);
	$pdf->ezText(utf8_decode('Alicante - España'),7,$opciones);
	$pdf->ezText('B-73179756',7,$opciones);
	$pdf->ezText('Tfno. (34) 96.549.57.07',7,$opciones);
	$pdf->ezText('Fax (34) 96.549.38.17',7,$opciones);
	$pdf->ezText('mianco@mianco.com',7,$opciones);*/

	// Imagen
	//$pdf->ezImage('imagenes/12m2.jpg',-60,120,'');

	$objetoCatalogo = ENCatalogo::obtenerPorId(filtrarCadena($_GET["id"]));
	if ($objetoCatalogo != NULL)
	{
		$modelos = $objetoCatalogo->obtenerModelos();
		if ($modelos != NULL)
		{
			$opciones['justification'] = "center";
			foreach ($modelos as $i)
			{
				$rutaMiniatura = "";
				$fotos = ENFoto::obtenerTodos($i->getId());
				if ($fotos != NULL)
				{
					if (count($fotos) > 0)
						$rutaMiniatura = $fotos[0]->getRutaMiniatura4();
				}

				$pdf->ezImage($rutaMiniatura, 15, 300, '', 'center');
				$pdf->ezText("Mod. ".$i->getModelo()."               ".$i->getPrecioVenta().utf8_decode(" eur"), 10, $opciones);
			}
		}
	}

	// Datos del cliente
	/*$pdf->ezText(utf8_decode('Número de pedido: 1729'),7);
	$pdf->ezText('Fecha: 11-02-1987',7);
	$pdf->ezText('Nombre: '.$filapedido['nombre'],7);
	$pdf->ezText('',20);*/

	/*while( $fila=mysql_fetch_array($resultado) )
	{
		$data[$i]=array(utf8_decode('Artículo')=>rellenar($fila['id'],'0',6),
						utf8_decode('Cód. barras')=>$fila['codigo'],
						utf8_decode('Descripción del artículo')=>$fila['descripcion'],
						utf8_decode('Sección')=>$fila['seccion'],
						'Cantidad'=>$fila['cantidad'],
						'Precio'=>formatonumero($fila['preciocompra']),
						'Importe'=>formatonumero(round($fila['importe']),2)
						);
		$i++;
	}*/

	/*while($i<40)
	{
		/*$data[$i]['Artículo']='';
		$data[$i]['Cód. barras']='';
		$data[$i]['Descripción del artículo']='';
		$data[$i]['Sección']='';
		$data[$i]['Cantidad']='';
		$data[$i]['Precio']='';
		$data[$i]['Importe']='';
		$i++;
	}*/

	// Opciones de la tabla.
	/*$opciones2['shaded']=0;
	$opciones2['fontSize']=7;
	$opciones2['titleFontSize']=10;
	$opciones2['width']=525;
	$opciones2['innerLineThickness']=0.0;
	$opciones2['outerLineThickness']=0.0;
	$opciones2['splitRows']=1;
	$pdf->ezTable($data,"","",$opciones2);

	$pdf->ezText('',20);

	$final[0][utf8_decode('Información adicional')]=utf8_decode(utf8_encode('Introduce texto.'));
	$final[0]['Total']=round(1283,2).' eur'."\n".round(5001*166.386,0).' pts';
	$pdf->ezTable($final,"","",$opciones2);*/

	$pdf->ezStream();
	exit();

?>
