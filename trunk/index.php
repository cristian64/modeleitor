<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php

			require_once 'BD.php';
			BD::espeficarDatos("localhost", "root", "8520", "modeleitor");
			require_once 'ENColor.php';
			require_once 'ENFabricante.php';
			require_once 'ENFoto.php';
			require_once 'ENModelo.php';

			/*echo "¿Existe el color de id 3? ";
			if (ENColor::existePorId(3)) echo "Sí";
			else echo "No";
			echo "<br/>";

			echo "¿Existe el color de id 31? ";
			if (ENColor::existePorId(31)) echo "Sí";
			else echo "No";
			echo "<br/>";

			echo "¿Existe el color de nombre rojo? ";
			if (ENColor::existePorNombre("rojo")) echo "Sí";
			else echo "No";
			echo "<br/>";

			echo "¿Existe el color de nombre rojoasdad? ";
			if (ENColor::existePorNombre("rojoasdad")) echo "Sí";
			else echo "No";
			echo "<br/>";

			echo "¿Existe el color de extraido rojo? ";
			$colorRojo = ENColor::obtenerPorNombre("rojo");
			if ($colorRojo->existe()) echo "Sí";
			else echo "No";
			echo "<br/>";


			ENColor::borrarPorNombre("azulon");

			echo "¿Existe el color de extraido azulon? ";
			$colorAzulon = new ENColor("azulon", "2255ff");
			echo "Color azulon: ".$colorAzulon->getRGB()."<br/>";
			if ($colorAzulon->existe()) echo "Sí";
			else echo "No";
			echo "<br/>";

			echo "¿Existe el color de extraido azulon? ";
			$colorAzulon->guardar();
			if ($colorAzulon->existe()) echo "Sí";
			else echo "No";
			echo "<br/>";

			echo "Id extraido 4: ".ENColor::obtenerPorId(4)->getNombre()."<br/>";

			$colores = ENColor::obtenerTodos();
			foreach ($colores as $i)
			{
				echo $i->getId() . ' ' . $i->getNombre() . ' ' . $i->toHex() . '<br/>';
			}

			$pollaca = ENColor::obtenerPorNombre("pollaca");
			$pollaca->setRGB('001122');
			$pollaca->actualizar();*/



			/*$fabricante = new ENFabricante("paquito");
			$fabricante->insertarTelefono("965412331", "fabrica");
			$fabricante->insertarTelefono("654213012", "movil");
			$fabricante->insertarTelefono("965412331", "casa");
			$fabricante->insertarTelefono("965431622", "casa mia");

			echo $fabricante->getTelefonosHTML();


			$fabricante->borrarTelefono("965431622");

			$fabricante->borrarTelefono("654213012");

			echo $fabricante->getTelefonosHTML();*/




			/*$fabricante = CADFabricante::obtenerPorNombre("paquito");
			echo $fabricante->getTelefonosHTML();

			$fabricante = CADFabricante::obtenerPorId(1);
			echo $fabricante->getTelefonosHTML();*/


			/*$fabricante = new ENFabricante("cutillas");
			$fabricante->insertarTelefono("666111000", "movil");
			$fabricante->insertarTelefono("666111333", "movil2");
			$fabricante->insertarTelefono("666111444", "movil3");
			$fabricante->insertarTelefono("666111111", "movil casa");
			if (CADFabricante::guardar($fabricante))
			{
				echo "Guardado correctamente.<br/>";
			}
			else
			{
				echo "No guardado correctamente.<br/>";
			}*/

			/*$fabricantes = CADFabricante::obtenerTodos();
			foreach ($fabricantes as $i)
			{
				echo $i->getId().' '.$i->getNombre()."<br/>";
				echo $i->getTelefonosHTML().'<br/>';
				echo "<br/>";
			}

			$cutillas = CADFabricante::obtenerPorNombre("paquito");
			$cutillas->insertarTelefono("961112223", "inventado");
			CADFabricante::actualizar($cutillas);*/

			/*$foto = new Imagen("BD.php");
			$foto->redimensionar(100, 100, "1000.jpg");

			Imagen::borrar("labla.jpg");
			*/


			/*$fotos = CADFoto::obtenerTodos();
			foreach ($fotos as $i)
			{
				echo $i->toString()."<br/>";
			}

			$foto = CADFoto::obtenerPorId(4);
			echo $foto->toString()."<br/>";*/

			/*if (CADFoto::existePorId(2))
			{
				echo "existe la 2<br/>";
			}

			CADFoto::borrar($foto);

			if (CADFoto::existePorId(2))
			{
				echo "existe la 2<br/>";
			}*/

			/*$nuevaFoto = new ENFoto();
			$nuevaFoto->setDescripcion("jejejaejaeae, veamos qué tal");
			$nuevaFoto->setIdModelo(4);

			CADFoto::guardar($nuevaFoto);

			$foto5 = CADFoto::obtenerPorId(4);
			$foto5->setDescripcion($foto5->getDescripcion()."a");
			CADFoto::actualizar($foto5);*/




			$modelo = CADModelo::obtenerPorId(3);
			//$modelo->setDescripcion($modelo->getDescripcion()."a");
			$modelo->setPrecioVenta($modelo->getPrecioVenta()+0.01);
			CADModelo::actualizar($modelo);

			$modeloNuevo = new ENModelo();
			$modeloNuevo->setDescripcion("qué tal");
			$modeloNuevo->setModelo("JB-1005");
			$modeloNuevo->setPrecioVenta(rand(500, 3000)/100);
			$modeloNuevo->setPrecioCompra($modeloNuevo->getPrecioVenta()-rand(50, 150)/100);
			$modeloNuevo->setPrecioVentaMinorista($modeloNuevo->getPrecioVenta()+4);
			$modeloNuevo->setFabricante(ENFabricante::obtenerPorNombre("paquito"));

			CADModelo::guardar($modeloNuevo);

			echo "<br/>";
			$modelos = CADModelo::obtenerTodos();
			foreach ($modelos as $i)
			{
				echo $i->toString()."<br/>";
			}


        ?>
    </body>
</html>
