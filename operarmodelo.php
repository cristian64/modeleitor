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
			if ($_FILES["foto"]["tmp_name"] == "")
			{
				header("location: modelo.php?id=".$nuevo->getId()."&exito=El modelo se ha creado correctamente.&aviso=No has introducido ninguna foto para el modelo.");
			}
			else
			{
				$subida = false;
				$foto = new ENFoto();
				$foto->setIdModelo($nuevo->getId());
				if ($foto->guardar())
				{
					if ($foto->crearFicheroFoto($_FILES["foto"]))
					{
						$subida = true;
					}
					else
					{
						$foto->borrar();
					}
				}

				if ($subida)
					header("location: modelo.php?id=".$nuevo->getId()."&exito=El modelo se ha creado correctamente.");
				else
					header("location: modelo.php?id=".$nuevo->getId()."&exito=El modelo se ha creado correctamente.&aviso=No se ha podido subir la foto seleccionada.");
			}
		}
		else
		{
			header("location: modelo.php?error=No se pudo insertar el modelo. Revisa que los datos introducidos sean correctos.");
		}
	}
	else
	{
		if ($_POST["operacion"] == "editar")
		{
			$actualizado = false;
			$existente = ENModelo::obtenerPorId($_POST["id"]);
			if ($existente != NULL)
			{
				$existente->setModelo($_POST["modelo"]);
				$existente->setDescripcion($_POST["descripcion"]);
				$existente->setPrecioVenta($_POST["precio_venta"]);
				$existente->setPrecioVentaMinorista($_POST["precio_venta_minorista"]);
				$existente->setPrecioCompra($_POST["precio_compra"]);
				$existente->setPrimerAno($_POST["primer_ano"]);
				$existente->setFabricante(ENFabricante::obtenerPorId($_POST["fabricante"]));

				if ($existente->actualizar())
				{
					$actualizado = true;
					header("location: modelo.php?exito=Los cambios se han guardado correctamente.&id=".$_POST["id"]);
				}
			}
			if (!$actualizado)
				header("location: modelo.php?error=No se han podido guardar los cambios. Revisa que los datos introducidos sean correctos.&id=".$_POST["id"]);
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
					header("location: index.php?exito=Modelo eliminado correctamente.");
				}
				else
				{
					header("location: modelo.php?error=No se ha podido eliminar el modelo.&id=".$_POST["id"]);
				}
			}
			else
			{
				if ($_POST["operacion"] == "insertarfoto")
				{
					$subida = false;
					$foto = new ENFoto();
					$foto->setIdModelo($_POST["id"]);
					if ($foto->guardar())
					{
						if ($foto->crearFicheroFoto($_FILES["foto"]))
						{
							$subida = true;
						}
						else
						{
							$foto->borrar();
						}
					}

					if ($subida)
						header("location: modelo.php?id=".$_POST["id"]."&exito=Foto insertada correctamente.");
					else
						header("location: modelo.php?id=".$_POST["id"]."&error=No se pudo insertar la foto.");
				}
				else
				{
					if ($_POST["operacion"] == "eliminarfoto")
					{
						$foto = ENFoto::obtenerPorId($_POST["id"]);
						if ($foto != NULL)
						{
							$foto->borrarFicheroFoto();
							$foto->borrar();
							echo "OK";
						}
						else
						{
							echo "FALLO";
						}
					}
				}
			}
		}
	}

?>
