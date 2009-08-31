<?php
	session_start();
	require_once 'minilibreria.php';
	accesoValido();

	$id = filtrarCadena($_POST["id"]);
	$titulo = filtrarCadena($_POST["titulo"]);
	$id_usuario = $_SESSION["id_usuario"];
	$id_modelo = filtrarCadena($_POST["id_modelo"]);
	$fabricantes = $_POST["fabricantes"]; // Vector de identificadores.

	if ($_POST["operacion"] == "insertar")
	{
		$nuevo = new ENCatalogo($titulo);
		$nuevo->setIdUsuario($id_usuario);

		if ($nuevo->guardar())
		{
			// Comprobamos si hay fabricantes seleccionados para insertar todos sus modelos en el catálogo recién creado.
			if ($fabricantes != NULL)
			{
				foreach ($fabricantes as $i)
				{
					$modelos = ENModelo::obtenerTodos(filtrarCadena($i));
					if ($modelos != NULL)
					{
						foreach ($modelos as $j)
						{
							$nuevo->insertarModelo($j->getId());
						}
					}
				}
			}
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
					if ($_SESSION["id_catalogo"] != $id)
					{
						$_SESSION["id_catalogo"] = $id;
						echo "SELECCIONADO";
						exit();
					}
					else
					{
						$_SESSION["id_catalogo"] = 0;
						echo "DESSELECCIONADO";
						exit();
					}
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
						if ($catalogo->existeModelo($id_modelo) || $catalogo->insertarModelo($id_modelo))
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
							if (!$catalogo->existeModelo($id_modelo) || $catalogo->quitarModelo($id_modelo))
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
						if ($_POST["operacion"] == "permutarmodelo")
						{
							$catalogo = ENCatalogo::obtenerPorId($_SESSION["id_catalogo"]);
							if ($catalogo != NULL)
							{
								//echo "distinto null\n";
								if ($catalogo->existeModelo($id_modelo))
								{
									//echo "existe\n";
									if ($catalogo->quitarModelo($id_modelo))
									{
										echo "QUITADO";
										exit();
									}
								}
								else
								{
									//echo "no existe\n";
									if ($catalogo->insertarModelo($id_modelo))
									{
										echo "INSERTADO";
										exit();
									}
									/*else
									{
										echo "NO INSERTO";
									}*/
								}
							}
							echo "FALLO";//.$_SESSION["id_catalogo"];
							exit();
						}
					}
				}
			}
		}
	}


	header("location: catalogos.php");
	exit();
?>
