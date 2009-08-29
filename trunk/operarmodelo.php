<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();

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
			registrar($nuevo->toString(), "Insertar nuevo modelo");
			if ($_FILES["foto"]["tmp_name"] == "")
			{
				header("location: modelo.php?id=".$nuevo->getId()."&exito=El modelo se ha creado correctamente.&aviso=No has introducido ninguna foto para el modelo.");
				exit();
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
						registrar($foto->toString(), "Insertar nueva foto (adjunta)");
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
				exit();
			}
		}
		else
		{
			header("location: modelo.php?error=No se pudo insertar el modelo. Revisa que los datos introducidos sean correctos.");
			exit();
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
				registrar($existente->toString(), "Editar modelo (antes)");
				$existente->setModelo($modelo);
				$existente->setDescripcion($descripcion);
				$existente->setPrecioVenta($precio_venta);
				$existente->setPrecioVentaMinorista($precio_venta_minorista);
				$existente->setPrecioCompra($precio_compra);
				$existente->setPrimerAno($primer_ano);
				$existente->setFabricante(ENFabricante::obtenerPorId($fabricante));

				if ($existente->actualizar())
				{
					registrar($existente->toString(), "Editar modelo (después)");
					$actualizado = true;
					header("location: modelo.php?exito=Los cambios se han guardado correctamente.&id=".$id);
					exit();
				}
			}
			if (!$actualizado)
			{
				header("location: modelo.php?error=No se han podido guardar los cambios. Revisa que los datos introducidos sean correctos.&id=".$id);
				exit();
			}
		}
		else
		{
			// Comprobamos si se trata de un borrado.
			if ($_POST["operacion"] == "borrar")
			{
				$modelo = ENModelo::obtenerPorId($id);
				if ($modelo != NULL)
				{
					registrar($modelo->toString(), "Borrar modelo (antes)");
					
					$fotos = ENFoto::obtenerTodos($id);
					if ($fotos != NULL)
					{
						foreach ($fotos as $i)
						{
							$i->borrarFicheroFoto();
							$i->borrar();
						}
					}

					if ($modelo->borrar())
					{
						registrar($modelo->toString(), "Borrar modelo (después)");
						header("location: modelos.php?exito=Modelo eliminado correctamente.");
						exit();
					}
					else
					{
						header("location: modelo.php?error=No se ha podido eliminar el modelo.&id=".$id);
						exit();
					}
				}
				else
				{
					header("location: modelo.php?error=No se ha podido eliminar el modelo.&id=".$id);
					exit();
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
							registrar($foto->toString(), "Insertar nueva foto (aparte)");
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
					exit();
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
							registrar($foto->toString(), "Borrar foto");
							echo "OK";
							exit();
						}
						else
						{
							echo "FALLO";
							exit();
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
							exit();
						}
					}
				}
			}
		}
	}

	header("location: modelos.php");
	exit();
?>
