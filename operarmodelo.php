<?php
	require_once 'minilibreria.php';

	$id = filtrarCadena($_POST["id"]);
	$modelo = filtrarCadena($_POST["modelo"]);
	$descripcion = filtrarCadena($_POST["descripcion"]);
	$precio_venta = str_replace(",", ".", filtrarCadena($_POST["precio_venta"]));
	$precio_venta_minorista = str_replace(",", ".", filtrarCadena($_POST["precio_venta_minorista"]));
	$precio_compra = str_replace(",", ".", filtrarCadena($_POST["precio_compra"]));
	$primer_ano = filtrarCadena($_POST["primer_ano"]);
	$fabricante = filtrarCadena($_POST["fabricante"]);

	// Comprobamos si se trata de una inserción.
	if ($_POST["operacion"] == "insertar")
	{
		$nuevo = new ENModelo();
		$nuevo->setModelo($modelo);
		$nuevo->setDescripcion($descripcion);
		$nuevo->setPrecioVenta($precio_venta);
		$nuevo->setPrecioVentaMinorista($precio_venta_minorista);
		$nuevo->setPrecioCompra($precio_compra);
		$nuevo->setPrimerAno($primer_ano);
		$nuevo->setFabricante(ENFabricante::obtenerPorId($fabricante));

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
		// Comprobamos si se trata de una edición.
		if ($_POST["operacion"] == "editar")
		{
			$actualizado = false;
			$existente = ENModelo::obtenerPorId($id);
			if ($existente != NULL)
			{
				$existente->setModelo($modelo);
				$existente->setDescripcion($descripcion);
				$existente->setPrecioVenta($precio_venta);
				$existente->setPrecioVentaMinorista($precio_venta_minorista);
				$existente->setPrecioCompra($precio_compra);
				$existente->setPrimerAno($primer_ano);
				$existente->setFabricante(ENFabricante::obtenerPorId($fabricante));

				if ($existente->actualizar())
				{
					$actualizado = true;
					header("location: modelo.php?exito=Los cambios se han guardado correctamente.&id=".$id);
				}
			}
			if (!$actualizado)
				header("location: modelo.php?error=No se han podido guardar los cambios. Revisa que los datos introducidos sean correctos.&id=".$id);
		}
		else
		{
			// Comprobamos si se trata de un borrado.
			if ($_POST["operacion"] == "borrar")
			{
				$fotos = ENFoto::obtenerTodos($id);
				if ($fotos != NULL)
				{
					foreach ($fotos as $i)
					{
						$i->borrarFicheroFoto();
						$i->borrar();
					}
				}

				if (ENModelo::borrarPorId($id))
				{
					header("location: index.php?exito=Modelo eliminado correctamente.");
				}
				else
				{
					header("location: modelo.php?error=No se ha podido eliminar el modelo.&id=".$id);
				}
			}
			else
			{
				// Comprobamos si se trata de una inserción (foto).
				if ($_POST["operacion"] == "insertarfoto")
				{
					$subida = false;
					$foto = new ENFoto();
					$foto->setIdModelo($id);
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
						header("location: modelo.php?id=".$id."&exito=Foto insertada correctamente.");
					else
						header("location: modelo.php?id=".$id."&error=No se pudo insertar la foto.");
				}
				else
				{
					// Comprobamos si se trata de un borrado (foto).
					if ($_POST["operacion"] == "eliminarfoto")
					{
						$foto = ENFoto::obtenerPorId($id);
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
					else
					{
						// Permutamos el estado de las miniaturas (mostrarlas o no).
						if ($_POST["operacion"] == "miniaturas")
						{
							if ($_SESSION["miniaturas"] != "no")
								$_SESSION["miniaturas"] = "no";
							else
								$_SESSION["miniaturas"] = "si";

							echo "OK";
						}
					}
				}
			}
		}
	}

?>
