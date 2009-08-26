<?php
	require_once 'BD.php';
	BD::espeficarDatos("localhost", "root", "8520", "modeleitor");
	require_once 'ENColor.php';
	require_once 'ENFabricante.php';
	require_once 'ENFoto.php';
	require_once 'ENModelo.php';
	
	if ($_POST["operacion"] == "insertar")
	{
		$nuevo = new ENModelo();
		$nuevo->setModelo($_POST["modelo"]);
		$nuevo->setDescripcion($_POST["descripcion"]);
		$nuevo->setPrecioVenta($_POST["precio_venta"]);
		$nuevo->setPrecioVentaMinorista($_POST["precio_venta_minorista"]);
		$nuevo->setPrecioCompra($_POST["precio_compra"]);
		$nuevo->setPrimerAno($_POST["primer_ano"]);
		$nuevo->setFabricante(ENFabricante::obtenerPorId($_POST["fabricante"]));

		if ($nuevo->guardar())
		{
			if ($_FILES["foto"])
			{

			}
			
			$foto = new ENFoto();
			$foto->setIdModelo($nuevo->getId());
			if ($foto->guardar())
			{
				if ($foto->crearFicheroFoto($_FILES["foto"]))
				{
					header("location: index.php?exito=INSERTADO TODO");
				}
				else
				{
					exit();
					header("location: insertarmodelo.php?error=FALLO CREAR FOTO");
				}
			}
			else
			{
				header("location: insertarmodelo.php?error=FALLO GUARDAR FOTO");
			}
		}
		else
		{
			header("location: insertarmodelo.php?error=FALLO GUARDAR MODELO");
		}
	}
	else
	{
		if ($_POST["operacion"] == "editar")
		{
			
		}
		else
		{
			if ($_POST["operacion"] == "borrar")
			{
				$fotos = ENFoto::obtenerTodos($_POST["id"]);
				if ($fotos != NULL)
				{
					foreach ($fotos as $i)
					{
						$i->borrarFicheroFoto();
						$i->borrar();
					}
				}

				if (ENModelo::borrarPorId($_POST["id"]))
				{
					header("location: index.php?exito=BORRAO");
				}
				else
				{
					header("location: modelo.php?error=FALLO&id=".$_POST["id"]);
				}
			}
		}
	}

?>
