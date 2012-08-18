<?php

require_once ('minilibreria.php');

$nuevo = new ENFabricante();
$nuevo->setNombre("Antonio");
$nuevo->setTelefono("Holaaaa");
$nuevo->setDescripcion("pollicaaa'''!");
$nuevo->setEmail("cristian@gmal.com");
echo $nuevo->save();

echo "<br/>".count(ENCategoria::getByPadre(4, false));

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
$usuario->update();

?>
