<?php

require_once ('minilibreria.php');

$nuevo = new ENFabricante();
$nuevo->setNombre("Antonio");
$nuevo->setTelefono("Holaaaa");
$nuevo->setDescripcion("pollicaaa'''!");
$nuevo->setEmail("cristian@gmal.com");
echo $nuevo->save();

ENCategoria::get();

?>
