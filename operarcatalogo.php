<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();

	$id = filtrarCadena($_POST["id"]);
	$titulo = filtrarCadena($_POST["titulo"]);
	$id_usuario = $_SESSION["id_usuario"];
	$id_usuario = filtrarCadena($_POST["id_modelo"]);

	if ($_POST["operacion"] == "insertar")
	{
		$nuevo = new ENCatalogo($titulo);
		$nuevo->setIdUsuario($id_usuario);

		if ($nuevo->guardar())
		{
			registrar($nuevo->toString(), "Insertar nuevo catálogo");
			header("location: catalogos.php?exito=El catálogo ha sido insertado correctamente.");
			exit();
		}
		else
		{
			header("location: catalogos.php?error=No se pudo insertar el catálogo. Revisa los datos introducidos y vuelva a intentarlo.");
			exit();
		}
	}
	else
	{
		if ($_POST["operacion"] == "borrar")
		{
			$catalogo = ENCatalogo::obtenerPorId($id);
			if ($catalogo != NULL)
			{
				registrar($catalogo->toString(), "Borrar catálogo (antes)");
				if ($catalogo->borrar())
				{
					registrar($catalogo->toString(), "Borrar catálogo (después)");
					if ($_SESSION["id_catalogo"] == $catalogo->getId())
					{
						$_SESSION["id_catalogo"] = 0;
					}
					echo "OK";
					exit();
				}
			}

			echo "FALLO";
			exit();
		}
		else
		{
			if ($_POST["operacion"] == "seleccionar")
			{
				if (CADCatalogo::existePorId($id))
				{
					$_SESSION["id_catalogo"] = $id;
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
				if ($_POST["operacion"] == "insertarmodelo")
				{
					$catalogo = CADCatalogo::obtenerPorId($_SESSION["id_catalogo"]);
					if ($catalogo != NULL)
					{
						if ($catalogo->insertarModelo($id_modelo))
						{
							echo "OK";
							exit();
						}
					}
					echo "FALLO";
					exit();
				}
				else
				{
					if ($_POST["operacion"] == "quitarmodelo")
					{
						$catalogo = CADCatalogo::obtenerPorId($_SESSION["id_catalogo"]);
						if ($catalogo != NULL)
						{
							if ($catalogo->quitarModelo($id_modelo))
							{
								echo "OK";
								exit();
							}
						}
						echo "FALLO";
						exit();
					}
				}
			}
		}
	}


	header("location: catalogos.php");
	exit();
?>
