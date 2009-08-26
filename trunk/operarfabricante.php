<?php
	require_once 'BD.php';
	BD::espeficarDatos("localhost", "root", "8520", "modeleitor");
	require_once 'ENFabricante.php';

	if ($_POST["operacion"] == "insertar")
	{
		$nuevo = new ENFabricante();
		$nuevo->setNombre($_POST["nombre"]);
		$nuevo->setInformacionAdicional($_POST["informacion_adicional"]);

		if ($nuevo->guardar())
		{
			header("location: fabricantes.php?exito=INSERTADO");
		}
		else
		{
			header("location: insertarfabricante.php?error=FALLO GUARDAR FABRICANTE");
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
				if (ENFabricante::borrarPorId($_POST["id"]))
				{
					header("location: index.php?exito=BORRAO");
				}
				else
				{
					header("location: fabricante.php?error=FALLO&id=".$_POST["id"]);
				}
			}
		}
	}

?>
