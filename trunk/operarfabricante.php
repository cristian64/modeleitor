<?php
	require_once 'minilibreria.php';

	$id = filtrarCadena($_POST["id"]);
	$nombre = filtrarCadena($_POST["nombre"]);
	$informacion_adicional = filtrarCadena($_POST["informacion_adicional"]);

	if ($_POST["operacion"] == "insertar")
	{
		$nuevo = new ENFabricante();
		$nuevo->setNombre($nombre);
		$nuevo->setInformacionAdicional($informacion_adicional);

		if (!ENFabricante::existePorNombre($nombre))
		{
			if ($nuevo->guardar())
			{
				header("location: fabricante.php?exito=El fabricante ha sido insertado correctamente.&id=".$nuevo->getId());
			}
			else
			{
				header("location: fabricante.php?error=No se pudo insertar el fabricante. Revisa los datos introducidos y vuelva a intentarlo.");
			}
		}
		else
		{
			header("location: fabricante.php?error=Ya existe un fabricante con ese nombre (".$nombre.").");
		}
	}
	else
	{
		if ($_POST["operacion"] == "editar")
		{
			$existente = ENFabricante::obtenerPorId($id);
			if ($existente != NULL)
			{
				// Comprobamos si el nombre está libre.
				$nombreLibre = true;
				$existentePorNombre = ENFabricante::obtenerPorNombre($nombre);
				if ($existentePorNombre != NULL)
				{
					if ($existente->getId() != $existentePorNombre->getId())
					{
						$nombreLibre = false;
					}
				}

				if ($nombreLibre)
				{
					$existente->setNombre($nombre);
					$existente->setInformacionAdicional($informacion_adicional);
					if ($existente->actualizar())
					{
						header("location: fabricante.php?exito=Los cambios se han guardado correctamente.&id=".$id);
					}
					else
					{
						header("location: fabricante.php?error=No se han podido guardar los cambios. Revisa los datos introducidos y vuelve a intentarlo.");
					}
				}
				else
				{
					header("location: fabricante.php?error=Ya existe un fabricante con ese nombre ($nombre).&id=$id");
				}
			}
		}
		else
		{
			if ($_POST["operacion"] == "borrar")
			{
				$modelosBorrados = false;
				$modelos = ENModelo::obtenerTodos($id);
				if (is_array($modelos))
				{
					$modelosBorrados = true;
					foreach ($modelos as $j)
					{
						$fotos = ENFoto::obtenerTodos($j->getId());
						if ($fotos != NULL)
						{
							foreach ($fotos as $i)
							{
								$i->borrarFicheroFoto();
								$i->borrar();
							}
						}

						if (!$j->borrar())
						{
							$modelosBorrados = false;
							break;
						}
					}
				}

				if ($modelosBorrados)
				{
					if (ENFabricante::borrarPorId($id))
					{
						header("location: fabricantes.php?exito=El fabricante ha sido eliminado correctamente.");
					}
					else
					{
						header("location: fabricante.php?error=Ocurrió un error al intentar eliminar el fabricante.&id=".$id);
					}
				}
				else
				{
					header("location: fabricante.php?error=Ocurrió un error al intentar eliminar el fabricante2.&id=".$id);
				}
			}
		}
	}

?>
