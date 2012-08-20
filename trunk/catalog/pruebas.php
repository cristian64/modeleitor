<?php

require_once ('minilibreria.php');

$nuevo = new ENFabricante();
$nuevo->setNombre("Antonio");
$nuevo->setTelefono("Holaaaa");
$nuevo->setDescripcion("pollicaaa'''!");
$nuevo->setEmail("cristian@gmal.com");
$nuevo->save();

echo "Cantidad categorias: ".count(ENCategoria::getByPadre(4, false))."<br />";
/*
$categoria = new ENCategoria();
$categoria->setNombre("Bota");
$categoria->setIdPadre(4);
$categoria->setMostrar(0);
$categoria->setDescripcion("pollaca + gorda!");
$categoria->save();

$usuario = new ENUsuario();
$usuario->setEmail("pollon@gmail.com");
$usuario->setNombre("Pollaca Garcia");
$usuario->setTelefono("612313");
$usuario->setDireccion("calle falsa, 123");
$usuario->setContrasena("asdasdasdadasdasdad");
$usuario->setAdmin(0);
$usuario->setActivo(1);
$usuario->save();

$usuario = ENUsuario::getById(1);
$usuario->setEmail("pollonga@gmail.com");
$usuario->update();*/

$modelo = ENModelo::getById(2);
$modelo->setReferencia("7001");
$modelo->setNombre("Californiana Piel Negro");
$modelo->setPrecio(14.50);
$modelo->setDescripcion("Fabricado en España");
$modelo->setTallaMenor(39);
$modelo->setTallaMayor(46);
$modelo->setOferta(true);
$modelo->setDescatalogado(true);
$modelo->setPrioridad(7);
$modelo->setIdFabricante(124);
$modelo->setIdMarca(124);
$modelo->update();

echo "Cantidad modelos en categoria 3: ".count(ENModelo::getByCategoria(3, 1, 10))."<br />";
echo "Cantidad modelos en categoria 3: ".ENModelo::countByCategoria(3)."<br />";

echo "Cantidad modelos en fabricante 3: ".count(ENModelo::getByFabricante(3, 1, 10))."<br />";
echo "Cantidad modelos en fabricante 3: ".ENModelo::countByFabricante(3)."<br />";

echo "Cantidad modelos en marca 3: ".count(ENModelo::getByMarca(3, 1, 10))."<br />";
echo "Cantidad modelos en marca 3: ".ENModelo::countByMarca(3)."<br />";

echo "Cantidad modelos: ".count(ENModelo::get())."<br />";

ENModelo::getById(5)->setCategoriasToDB(array(1, 5, 6, 7, 8, 9, 10));
echo "Cantidad categorias modelo 5: ".count(ENModelo::getById(5)->getCategoriasFromDB())."<br />";

?>
